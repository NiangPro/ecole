<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use Illuminate\Support\Facades\DB;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = Newsletter::query();
        
        // Recherche par email
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }
        
        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $subscribers = $query->paginate(20)->withQueryString();
        
        // Statistiques
        $totalSubscribers = Newsletter::where('is_active', true)->count();
        $totalInactive = Newsletter::where('is_active', false)->count();
        $totalAll = Newsletter::count();
        
        // Statistiques par mois (6 derniers mois)
        $monthlyStats = Newsletter::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();
        
        return view('admin.newsletter.index', compact(
            'subscribers', 
            'totalSubscribers', 
            'totalInactive',
            'totalAll',
            'monthlyStats'
        ));
    }
    
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return redirect()->route('admin.newsletter.index')
                ->with('error', 'Aucun abonné sélectionné.');
        }
        
        switch ($action) {
            case 'activate':
                Newsletter::whereIn('id', $ids)->update(['is_active' => true]);
                return redirect()->route('admin.newsletter.index')
                    ->with('success', count($ids) . ' abonné(s) activé(s) avec succès.');
                    
            case 'deactivate':
                Newsletter::whereIn('id', $ids)->update(['is_active' => false]);
                return redirect()->route('admin.newsletter.index')
                    ->with('success', count($ids) . ' abonné(s) désactivé(s) avec succès.');
                    
            case 'delete':
                Newsletter::whereIn('id', $ids)->delete();
                return redirect()->route('admin.newsletter.index')
                    ->with('success', count($ids) . ' abonné(s) supprimé(s) avec succès.');
                    
            default:
                return redirect()->route('admin.newsletter.index')
                    ->with('error', 'Action invalide.');
        }
    }

    public function export(Request $request)
    {
        $query = Newsletter::query();
        
        // Filtre par statut si spécifié
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $subscribers = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'newsletter_subscribers_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Ajouter BOM pour Excel
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // En-têtes
        fputcsv($output, ['Email', 'Date d\'inscription', 'Date de création', 'Statut', 'Token'], ';');
        
        // Données
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber->email,
                $subscriber->subscribed_at ? $subscriber->subscribed_at->format('d/m/Y H:i') : '-',
                $subscriber->created_at->format('d/m/Y H:i'),
                $subscriber->is_active ? 'Actif' : 'Inactif',
                $subscriber->token ?? '-'
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    public function destroy($id)
    {
        $subscriber = Newsletter::findOrFail($id);
        $subscriber->delete();
        
        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Abonné supprimé avec succès.');
    }

    public function toggleStatus($id)
    {
        $subscriber = Newsletter::findOrFail($id);
        $subscriber->is_active = !$subscriber->is_active;
        $subscriber->save();
        
        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Statut modifié avec succès.');
    }
}
