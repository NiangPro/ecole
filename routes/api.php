<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — NiangProgrammeur
|--------------------------------------------------------------------------
|
| Exemple d'utilisation (token préalablement généré via `php artisan token:generate {email}`) :
|
|   curl -s -X POST https://www.niangprogrammeur.com/api/articles \
|     -H "Authorization: Bearer VOTRE_TOKEN" \
|     -H "Content-Type: application/json" \
|     -d '{
|           "title":            "Mon titre d'\''article",
|           "content":          "<p>Contenu HTML de l'\''article</p>",
|           "excerpt":          "Résumé court",
|           "category_id":      1,
|           "status":           "published",
|           "cover_image_url":  "https://example.com/image.jpg",
|           "meta_title":       "SEO title",
|           "meta_description": "Description SEO",
|           "meta_keywords":    ["mot-clé1", "mot-clé2"]
|         }'
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // Infos de l'utilisateur authentifié
    Route::get('/user', fn (Request $request) => $request->user());

    // Articles emplois/opportunités
    Route::post('/articles', [ArticleController::class, 'store']);

});
