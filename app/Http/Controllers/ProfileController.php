<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\FormationProgress;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        $progress = FormationProgress::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('profile', compact('user', 'progress'));
    }
}
