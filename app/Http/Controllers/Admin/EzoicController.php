<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EzoicSetting;
use Illuminate\Http\Request;

class EzoicController extends Controller
{
    /**
     * Afficher la page de configuration Ezoic
     */
    public function index()
    {
        $settings = EzoicSetting::first();
        
        return view('admin.ezoic.index', compact('settings'));
    }
    
    /**
     * Mettre à jour la configuration Ezoic
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_id' => 'nullable|string|max:255',
            'privacy_scripts' => 'nullable|string',
            'ezoic_code' => 'nullable|string',
            'ezoic_body_code' => 'nullable|string',
            'ezoic_footer_code' => 'nullable|string',
        ]);
        
        $settings = EzoicSetting::first();
        
        if (!$settings) {
            $settings = new EzoicSetting();
        }
        
        $settings->site_id = $request->site_id;
        $settings->privacy_scripts = $request->privacy_scripts;
        $settings->ezoic_code = $request->ezoic_code;
        $settings->ezoic_body_code = $request->ezoic_body_code;
        $settings->ezoic_footer_code = $request->ezoic_footer_code;
        $settings->header_banner = $request->has('header_banner');
        $settings->sidebar_banner = $request->has('sidebar_banner');
        $settings->footer_banner = $request->has('footer_banner');
        $settings->in_content = $request->has('in_content');
        $settings->auto_ads = $request->has('auto_ads');
        $settings->save();
        
        // Invalider le cache
        EzoicSetting::clearCache();
        
        return redirect()->route('admin.ezoic.index')->with('success', 'Configuration Ezoic mise à jour avec succès!');
    }
}
