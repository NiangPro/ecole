<?php

namespace App\Http\Controllers;

use App\Models\AdSenseSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdsTxtController extends Controller
{
    /**
     * Générer et servir le fichier ads.txt
     */
    public function index()
    {
        $settings = AdSenseSetting::first();
        $publisherId = $settings?->publisher_id;
        
        // Si aucun Publisher ID n'est configuré, retourner un fichier vide ou un message
        if (!$publisherId || $publisherId === 'pub-0000000000000000') {
            $content = "# Google AdSense\n";
            $content .= "# Veuillez configurer votre Publisher ID dans l'administration\n";
            $content .= "# google.com, pub-0000000000000000, DIRECT, f08c47fec0942fa0\n";
        } else {
            // Générer le contenu ads.txt avec le Publisher ID réel
            $content = "# Google AdSense\n";
            $content .= "google.com, {$publisherId}, DIRECT, f08c47fec0942fa0\n";
        }
        
        return response($content, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('X-Accel-Buffering', 'no')
            ->header('X-Cache-Control', 'no-transform');
    }
    
    /**
     * Mettre à jour le fichier ads.txt physique
     */
    public static function updateFile()
    {
        $settings = AdSenseSetting::first();
        $publisherId = $settings?->publisher_id;
        
        $filePath = public_path('ads.txt');
        
        if (!$publisherId || $publisherId === 'pub-0000000000000000') {
            $content = "# Google AdSense\n";
            $content .= "# Remplacez pub-0000000000000000 par votre ID éditeur AdSense\n";
            $content .= "google.com, pub-0000000000000000, DIRECT, f08c47fec0942fa0\n";
        } else {
            $content = "# Google AdSense\n";
            $content .= "google.com, {$publisherId}, DIRECT, f08c47fec0942fa0\n";
        }
        
        File::put($filePath, $content);
    }
}
