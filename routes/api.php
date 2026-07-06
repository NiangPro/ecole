<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\McpController;
use App\Http\Middleware\VerifyMcpConnectorToken;
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

/*
|--------------------------------------------------------------------------
| Endpoint MCP (Streamable HTTP, mode JSON — exécuté par requête, sans
| processus persistant : hébergement mutualisé, pas de daemon possible)
|--------------------------------------------------------------------------
| URL à coller dans les réglages de connecteur MCP : https://www.niangprogrammeur.com/api/mcp
| Authentification : en-tête "Authorization: Bearer <MCP_CONNECTOR_TOKEN>"
*/
Route::get('/mcp/health', [McpController::class, 'health']);

Route::middleware(VerifyMcpConnectorToken::class)->group(function () {
    Route::post('/mcp', [McpController::class, 'handle']);
    Route::get('/mcp', [McpController::class, 'methodNotAllowed']);
    Route::delete('/mcp', [McpController::class, 'methodNotAllowed']);
});
