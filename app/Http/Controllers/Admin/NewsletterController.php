<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = Newsletter::orderBy('created_at', 'desc')->paginate(20);
        $totalSubscribers = Newsletter::where('is_active', true)->count();
        $totalInactive = Newsletter::where('is_active', false)->count();
        
        return view('admin.newsletter.index', compact('subscribers', 'totalSubscribers', 'totalInactive'));
    }

    public function export()
    {
        $subscribers = Newsletter::where('is_active', true)->get();
        
        $filename = 'newsletter_subscribers_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // En-têtes
        fputcsv($output, ['Email', 'Date d\'inscription', 'Statut']);
        
        // Données
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber->email,
                $subscriber->subscribed_at->format('d/m/Y H:i'),
                $subscriber->is_active ? 'Actif' : 'Inactif'
            ]);
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
