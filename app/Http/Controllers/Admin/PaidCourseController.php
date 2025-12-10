<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaidCourse;
use App\Models\CourseChapter;
use App\Models\CoursePurchase;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaidCourseController extends Controller
{
    /**
     * Afficher la liste des cours payants
     */
    public function index(Request $request)
    {
        $query = PaidCourse::withCount('purchases')
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->has('price_min') && $request->price_min !== '') {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && $request->price_max !== '') {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $courses = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total' => PaidCourse::count(),
            'published' => PaidCourse::where('status', 'published')->count(),
            'draft' => PaidCourse::where('status', 'draft')->count(),
            'archived' => PaidCourse::where('status', 'archived')->count(),
            'total_revenue' => CoursePurchase::where('status', 'completed')
                ->sum('amount_paid'),
            'total_sales' => CoursePurchase::where('status', 'completed')->count(),
        ];

        return view('admin.monetization.courses.index', compact('courses', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.monetization.courses.create');
    }

    /**
     * Enregistrer un nouveau cours
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:paid_courses,slug',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cover_image_url' => 'nullable|url|max:500',
            'cover_type' => 'required|in:internal,external',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after:discount_start',
            'status' => 'required|in:draft,published,archived',
            'duration_hours' => 'nullable|integer|min:1',
            'what_you_learn' => 'nullable|array',
            'what_you_learn.*' => 'string|max:255',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Générer le slug si non fourni
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
            // S'assurer que le slug est unique
            $originalSlug = $data['slug'];
            $counter = 1;
            while (PaidCourse::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Gérer l'upload de l'image
        if ($data['cover_type'] === 'internal' && $request->hasFile('cover_image_file')) {
            $image = $request->file('cover_image_file');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('courses', $imageName, 'public');
            $data['cover_image'] = $imagePath;
        } elseif ($data['cover_type'] === 'external' && $request->has('cover_image_url')) {
            $url = trim($request->input('cover_image_url', ''));
            if (!empty($url) && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
                $data['cover_image'] = $url;
            } else {
                unset($data['cover_image']);
            }
        } else {
            unset($data['cover_image']);
        }

        // Convertir les tableaux en JSON
        if (isset($data['what_you_learn']) && is_array($data['what_you_learn'])) {
            $data['what_you_learn'] = array_filter($data['what_you_learn']);
        }
        if (isset($data['requirements']) && is_array($data['requirements'])) {
            $data['requirements'] = array_filter($data['requirements']);
        }

        $course = PaidCourse::create($data);

        return redirect()->route('admin.monetization.courses.show', $course->id)
            ->with('success', 'Cours créé avec succès !');
    }

    /**
     * Afficher les détails d'un cours
     */
    public function show($id)
    {
        $course = PaidCourse::withCount('purchases')
            ->with(['purchases' => function($query) {
                $query->with('user')->latest()->take(10);
            }])
            ->findOrFail($id);

        // Statistiques du cours
        $courseStats = [
            'total_sales' => $course->purchases()->where('status', 'completed')->count(),
            'total_revenue' => $course->purchases()->where('status', 'completed')->sum('amount_paid'),
            'pending_sales' => $course->purchases()->where('status', 'pending')->count(),
            'failed_sales' => $course->purchases()->where('status', 'failed')->count(),
        ];

        // Revenus par mois
        $revenueByMonth = CoursePurchase::where('paid_course_id', $course->id)
            ->where('status', 'completed')
            ->selectRaw('DATE_FORMAT(purchased_at, "%Y-%m") as month, SUM(amount_paid) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('admin.monetization.courses.show', compact('course', 'courseStats', 'revenueByMonth'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $course = PaidCourse::with('chapters')->findOrFail($id);
        return view('admin.monetization.courses.edit', compact('course'));
    }

    /**
     * Mettre à jour un cours
     */
    public function update(Request $request, $id)
    {
        $course = PaidCourse::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:paid_courses,slug,' . $id,
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'cover_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cover_image_url' => 'nullable|url|max:500',
            'cover_type' => 'required|in:internal,external',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after:discount_start',
            'status' => 'required|in:draft,published,archived',
            'duration_hours' => 'nullable|integer|min:1',
            'what_you_learn' => 'nullable|array',
            'what_you_learn.*' => 'string|max:255',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();

        // Générer le slug si non fourni
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
            // S'assurer que le slug est unique
            $originalSlug = $data['slug'];
            $counter = 1;
            while (PaidCourse::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Gérer l'upload de l'image
        $imageChanged = false;
        
        if ($data['cover_type'] === 'internal' && $request->hasFile('cover_image_file')) {
            // Nouveau fichier uploadé
            $imageChanged = true;
            // Supprimer l'ancienne image si elle existe
            if ($course->cover_image && ($course->cover_type ?? 'internal') === 'internal' && Storage::disk('public')->exists($course->cover_image)) {
                Storage::disk('public')->delete($course->cover_image);
            }
            $image = $request->file('cover_image_file');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('courses', $imageName, 'public');
            $data['cover_image'] = $imagePath;
        } elseif ($data['cover_type'] === 'external' && $request->has('cover_image_url')) {
            // Nouvelle URL externe fournie
            $newUrl = trim($request->input('cover_image_url', ''));
            if (!empty($newUrl) && (str_starts_with($newUrl, 'http://') || str_starts_with($newUrl, 'https://'))) {
                if ($newUrl !== $course->cover_image || ($course->cover_type ?? 'internal') !== 'external') {
                    $imageChanged = true;
                    // Supprimer l'ancienne image interne si on passe à externe
                    if (($course->cover_type ?? 'internal') === 'internal' && $course->cover_image && Storage::disk('public')->exists($course->cover_image)) {
                        Storage::disk('public')->delete($course->cover_image);
                    }
                    $data['cover_image'] = $newUrl;
                }
            } elseif (empty($newUrl) && ($course->cover_type ?? 'internal') === 'external' && $course->cover_image) {
                // URL vide mais on garde l'ancienne URL
                $data['cover_image'] = $course->cover_image;
            }
        }
        
        // Gérer la suppression de l'image
        if ($request->has('delete_image') && $request->delete_image == '1') {
            if ($course->cover_image && ($course->cover_type ?? 'internal') === 'internal' && Storage::disk('public')->exists($course->cover_image)) {
                Storage::disk('public')->delete($course->cover_image);
            }
            $data['cover_image'] = null;
            $imageChanged = true;
        }
        
        // Si l'image n'a pas changé, conserver l'image existante
        if (!$imageChanged) {
            if ($course->cover_image) {
                $data['cover_image'] = $course->cover_image;
            } else {
                unset($data['cover_image']);
            }
        }
        
        // Si le type change mais pas l'image, gérer la transition
        if (($data['cover_type'] ?? 'internal') !== ($course->cover_type ?? 'internal') && !$imageChanged) {
            // Si on passe de interne à externe, supprimer l'image interne
            if (($course->cover_type ?? 'internal') === 'internal' && $course->cover_image && Storage::disk('public')->exists($course->cover_image)) {
                Storage::disk('public')->delete($course->cover_image);
                $data['cover_image'] = null;
            }
        }

        // Convertir les tableaux en JSON
        if (isset($data['what_you_learn']) && is_array($data['what_you_learn'])) {
            $data['what_you_learn'] = array_filter($data['what_you_learn']);
        }
        if (isset($data['requirements']) && is_array($data['requirements'])) {
            $data['requirements'] = array_filter($data['requirements']);
        }

        $course->update($data);

        // Gérer les chapitres
        if ($request->has('chapters')) {
            $chapters = $request->input('chapters', []);
            $existingChapterIds = [];
            
            foreach ($chapters as $index => $chapterData) {
                if (empty($chapterData['title'])) {
                    continue; // Ignorer les chapitres sans titre
                }
                
                $chapterData['order'] = $index + 1;
                
                if (isset($chapterData['id']) && $chapterData['id']) {
                    // Mettre à jour un chapitre existant
                    $chapter = CourseChapter::where('id', $chapterData['id'])
                        ->where('paid_course_id', $course->id)
                        ->first();
                    
                    if ($chapter) {
                        $chapter->update([
                            'title' => $chapterData['title'],
                            'description' => $chapterData['description'] ?? null,
                            'content' => $chapterData['content'] ?? null,
                            'order' => $chapterData['order'],
                            'duration_minutes' => isset($chapterData['duration_minutes']) && $chapterData['duration_minutes'] ? (int)$chapterData['duration_minutes'] : null,
                        ]);
                        $existingChapterIds[] = $chapter->id;
                    }
                } else {
                    // Créer un nouveau chapitre
                    $chapter = CourseChapter::create([
                        'paid_course_id' => $course->id,
                        'title' => $chapterData['title'],
                        'description' => $chapterData['description'] ?? null,
                        'content' => $chapterData['content'] ?? null,
                        'order' => $chapterData['order'],
                        'duration_minutes' => isset($chapterData['duration_minutes']) && $chapterData['duration_minutes'] ? (int)$chapterData['duration_minutes'] : null,
                    ]);
                    $existingChapterIds[] = $chapter->id;
                }
            }
            
            // Supprimer les chapitres qui ne sont plus dans la liste
            CourseChapter::where('paid_course_id', $course->id)
                ->whereNotIn('id', $existingChapterIds)
                ->delete();
        } else {
            // Si aucun chapitre n'est envoyé, supprimer tous les chapitres existants
            CourseChapter::where('paid_course_id', $course->id)->delete();
        }

        return redirect()->route('admin.monetization.courses.show', $course->id)
            ->with('success', 'Cours mis à jour avec succès !');
    }

    /**
     * Supprimer un cours
     */
    public function destroy($id)
    {
        $course = PaidCourse::findOrFail($id);

        // Vérifier s'il y a des achats
        $purchasesCount = $course->purchases()->count();
        if ($purchasesCount > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce cours car il a ' . $purchasesCount . ' achat(s) associé(s). Archivez-le plutôt.');
        }

        // Supprimer l'image
        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.monetization.courses.index')
            ->with('success', 'Cours supprimé avec succès !');
    }

    /**
     * Actions en masse
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,archive,delete',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:paid_courses,id',
        ]);

        $courseIds = $request->course_ids;
        $action = $request->action;

        switch ($action) {
            case 'publish':
                PaidCourse::whereIn('id', $courseIds)->update(['status' => 'published']);
                $message = count($courseIds) . ' cours publié(s) avec succès !';
                break;

            case 'archive':
                PaidCourse::whereIn('id', $courseIds)->update(['status' => 'archived']);
                $message = count($courseIds) . ' cours archivé(s) avec succès !';
                break;

            case 'delete':
                // Vérifier qu'aucun cours n'a d'achats
                $coursesWithPurchases = PaidCourse::whereIn('id', $courseIds)
                    ->has('purchases')
                    ->count();
                
                if ($coursesWithPurchases > 0) {
                    return redirect()->back()
                        ->with('error', 'Certains cours ont des achats associés et ne peuvent pas être supprimés.');
                }

                // Supprimer les images
                $courses = PaidCourse::whereIn('id', $courseIds)->get();
                foreach ($courses as $course) {
                    if ($course->image && Storage::disk('public')->exists($course->image)) {
                        Storage::disk('public')->delete($course->image);
                    }
                }

                PaidCourse::whereIn('id', $courseIds)->delete();
                $message = count($courseIds) . ' cours supprimé(s) avec succès !';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Exporter les cours en CSV
     */
    public function export(Request $request)
    {
        $query = PaidCourse::withCount('purchases');

        // Appliquer les mêmes filtres que l'index
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->get();

        $filename = 'cours_payants_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($courses) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID',
                'Titre',
                'Slug',
                'Prix',
                'Devise',
                'Prix réduit',
                'Statut',
                'Durée (heures)',
                'Étudiants',
                'Note',
                'Avis',
                'Ventes',
                'Date de création'
            ]);

            // Données
            foreach ($courses as $course) {
                fputcsv($file, [
                    $course->id,
                    $course->title,
                    $course->slug,
                    $course->price,
                    $course->currency,
                    $course->discount_price ?? '',
                    $course->status,
                    $course->duration_hours ?? '',
                    $course->students_count,
                    $course->rating,
                    $course->reviews_count,
                    $course->purchases_count,
                    $course->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dupliquer un cours
     */
    public function duplicate($id)
    {
        $originalCourse = PaidCourse::findOrFail($id);
        
        $newCourse = $originalCourse->replicate();
        $newCourse->title = $originalCourse->title . ' (Copie)';
        $newCourse->slug = $originalCourse->slug . '-copie-' . time();
        $newCourse->status = 'draft';
        $newCourse->students_count = 0;
        $newCourse->rating = 0;
        $newCourse->reviews_count = 0;
        
        // S'assurer que le slug est unique
        $counter = 1;
        while (PaidCourse::where('slug', $newCourse->slug)->exists()) {
            $newCourse->slug = $originalCourse->slug . '-copie-' . time() . '-' . $counter;
            $counter++;
        }
        
        $newCourse->save();

        return redirect()->route('admin.monetization.courses.show', $newCourse->id)
            ->with('success', 'Cours dupliqué avec succès !');
    }

    /**
     * Basculer le statut d'un cours
     */
    public function toggleStatus($id)
    {
        $course = PaidCourse::findOrFail($id);
        
        $newStatus = $course->status === 'published' ? 'draft' : 'published';
        $course->update(['status' => $newStatus]);

        $statusText = $newStatus === 'published' ? 'publié' : 'dépublié';
        
        return redirect()->back()
            ->with('success', "Cours {$statusText} avec succès !");
    }
}

