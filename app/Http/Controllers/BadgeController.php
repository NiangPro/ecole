<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Services\BadgeService;

class BadgeController extends Controller
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * Afficher la galerie de badges de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Tous les badges disponibles
        $allBadges = Badge::active()->ordered()->get();
        
        // Si aucun badge n'existe, retourner la vue avec un message
        if ($allBadges->isEmpty()) {
            return view('dashboard.badges', [
                'user' => $user,
                'allBadges' => collect(),
                'earnedBadges' => collect(),
                'earnedBadgeIds' => [],
                'badgesByType' => [
                    'special' => collect(),
                    'formation' => collect(),
                    'exercise' => collect(),
                    'quiz' => collect(),
                    'streak' => collect(),
                ],
            ]);
        }
        
        // Vérifier et attribuer de nouveaux badges
        $this->badgeService->checkAndAwardBadges($user);
        
        // Recharger les badges de l'utilisateur
        $user->load('badges');
        
        // Badges obtenus par l'utilisateur
        $earnedBadges = $user->badges;
        $earnedBadgeIds = $earnedBadges->pluck('id')->toArray();
        
        // Grouper par type
        $badgesByType = [
            'special' => $allBadges->where('type', 'special'),
            'formation' => $allBadges->where('type', 'formation'),
            'exercise' => $allBadges->where('type', 'exercise'),
            'quiz' => $allBadges->where('type', 'quiz'),
            'streak' => $allBadges->where('type', 'streak'),
        ];

        return view('dashboard.badges', compact('user', 'allBadges', 'earnedBadges', 'earnedBadgeIds', 'badgesByType'));
    }
}
