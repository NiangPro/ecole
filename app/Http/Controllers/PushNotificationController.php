<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur pour gérer les notifications push
 */
class PushNotificationController extends Controller
{
    /**
     * Enregistrer un abonnement push
     */
    public function subscribe(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $validator = Validator::make($request->all(), [
            'subscription.endpoint' => 'required|url',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Données d\'abonnement invalides'], 400);
        }

        $subscription = $request->input('subscription');
        $userId = Auth::id();

        // Stocker l'abonnement dans la base de données
        \App\Models\PushSubscription::updateOrCreate(
            [
                'user_id' => $userId,
                'endpoint' => $subscription['endpoint'],
            ],
            [
                'keys' => json_encode($subscription['keys']),
                'user_agent' => $request->userAgent(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Abonnement enregistré']);
    }

    /**
     * Désabonner des notifications push
     */
    public function unsubscribe(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $endpoint = $request->input('endpoint');

        if ($endpoint) {
            \App\Models\PushSubscription::where('user_id', Auth::id())
                ->where('endpoint', $endpoint)
                ->delete();
        } else {
            // Supprimer tous les abonnements de l'utilisateur
            \App\Models\PushSubscription::where('user_id', Auth::id())->delete();
        }

        return response()->json(['success' => true, 'message' => 'Désabonnement réussi']);
    }
}
