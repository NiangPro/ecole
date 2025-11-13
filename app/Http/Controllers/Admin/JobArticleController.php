<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\JobArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobArticleController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $articles = JobArticle::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.jobs.articles.index', compact('articles'));
    }

    public function create()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.jobs.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:job_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:job_articles,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|string',
            'cover_type' => 'required|in:internal,external',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:draft,published,archived'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['cover_type'] === 'internal' && $request->hasFile('cover_image_file')) {
            $path = $request->file('cover_image_file')->store('job-covers', 'public');
            $validated['cover_image'] = $path;
        } elseif ($validated['cover_type'] === 'external' && $request->has('cover_image_url')) {
            $validated['cover_image'] = $request->input('cover_image_url');
        }

        // Convertir les mots-clés en array
        if (!empty($validated['meta_keywords'])) {
            $validated['meta_keywords'] = array_map('trim', explode(',', $validated['meta_keywords']));
        }

        // Calculer les scores SEO et lisibilité
        $validated['seo_score'] = $this->calculateSeoScore($validated);
        $validated['readability_score'] = $this->calculateReadabilityScore($validated['content']);

        // Toujours définir published_at à maintenant si publié
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        JobArticle::create($validated);

        return redirect()->route('admin.jobs.articles.index')
            ->with('success', 'Article créé avec succès');
    }

    public function show($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $article = JobArticle::with('category')->findOrFail($id);
        return view('admin.jobs.articles.show', compact('article'));
    }

    public function edit($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $article = JobArticle::findOrFail($id);
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.jobs.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $article = JobArticle::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:job_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:job_articles,slug,' . $id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|string',
            'cover_type' => 'required|in:internal,external',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:draft,published,archived'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['cover_type'] === 'internal' && $request->hasFile('cover_image_file')) {
            // Supprimer l'ancienne image si elle existe
            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $path = $request->file('cover_image_file')->store('job-covers', 'public');
            $validated['cover_image'] = $path;
        } elseif ($validated['cover_type'] === 'external' && $request->has('cover_image_url')) {
            // Supprimer l'ancienne image interne si on passe à externe
            if ($article->cover_type === 'internal' && $article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $validated['cover_image'] = $request->input('cover_image_url');
        }

        // Convertir les mots-clés en array
        if (!empty($validated['meta_keywords'])) {
            $validated['meta_keywords'] = array_map('trim', explode(',', $validated['meta_keywords']));
        }

        // Recalculer les scores SEO et lisibilité
        $validated['seo_score'] = $this->calculateSeoScore($validated);
        $validated['readability_score'] = $this->calculateReadabilityScore($validated['content']);

        // Toujours définir published_at à maintenant si publié
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.jobs.articles.index')
            ->with('success', 'Article mis à jour avec succès');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $article = JobArticle::findOrFail($id);

        // Supprimer l'image de couverture si elle existe
        if ($article->cover_image && $article->cover_type === 'internal') {
            if (Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }
        }

        $article->delete();

        return redirect()->route('admin.jobs.articles.index')
            ->with('success', 'Article supprimé avec succès');
    }

    private function calculateSeoScore($data)
    {
        $score = 0;
        $maxScore = 100;

        // Titre (20 points)
        if (!empty($data['title']) && strlen($data['title']) >= 30 && strlen($data['title']) <= 60) {
            $score += 20;
        } elseif (!empty($data['title'])) {
            $score += 10;
        }

        // Meta title (15 points)
        if (!empty($data['meta_title']) && strlen($data['meta_title']) >= 30 && strlen($data['meta_title']) <= 60) {
            $score += 15;
        } elseif (!empty($data['meta_title'])) {
            $score += 7;
        }

        // Meta description (15 points)
        if (!empty($data['meta_description']) && strlen($data['meta_description']) >= 120 && strlen($data['meta_description']) <= 160) {
            $score += 15;
        } elseif (!empty($data['meta_description'])) {
            $score += 7;
        }

        // Mots-clés (10 points)
        if (!empty($data['meta_keywords'])) {
            $keywords = is_array($data['meta_keywords']) ? $data['meta_keywords'] : explode(',', $data['meta_keywords']);
            if (count($keywords) >= 3 && count($keywords) <= 10) {
                $score += 10;
            } else {
                $score += 5;
            }
        }

        // Contenu (20 points)
        if (!empty($data['content'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            if ($wordCount >= 300) {
                $score += 20;
            } elseif ($wordCount >= 150) {
                $score += 10;
            } else {
                $score += 5;
            }
        }

        // Excerpt (10 points)
        if (!empty($data['excerpt']) && strlen($data['excerpt']) >= 100) {
            $score += 10;
        } elseif (!empty($data['excerpt'])) {
            $score += 5;
        }

        // Image de couverture (10 points)
        if (!empty($data['cover_image'])) {
            $score += 10;
        }

        return min($score, $maxScore);
    }

    private function calculateReadabilityScore($content)
    {
        if (empty($content)) {
            return 0;
        }

        $text = strip_tags($content);
        $words = str_word_count($text);
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount = count($sentences);
        $paragraphs = preg_split('/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $paragraphCount = count($paragraphs);

        if ($sentenceCount === 0 || $words === 0) {
            return 0;
        }

        $avgWordsPerSentence = $words / $sentenceCount;
        $avgSentencesPerParagraph = $paragraphCount > 0 ? $sentenceCount / $paragraphCount : 0;

        // Score basé sur la longueur des phrases et paragraphes
        $score = 100;

        // Pénalité pour phrases trop longues
        if ($avgWordsPerSentence > 20) {
            $score -= 20;
        } elseif ($avgWordsPerSentence > 15) {
            $score -= 10;
        }

        // Bonus pour phrases courtes
        if ($avgWordsPerSentence >= 10 && $avgWordsPerSentence <= 15) {
            $score += 10;
        }

        // Pénalité pour paragraphes trop longs
        if ($avgSentencesPerParagraph > 5) {
            $score -= 15;
        }

        // Bonus pour structure équilibrée
        if ($paragraphCount >= 3 && $paragraphCount <= 10) {
            $score += 10;
        }

        return max(0, min(100, $score));
    }
}
