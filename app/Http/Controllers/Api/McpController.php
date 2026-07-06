<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\JobArticlePublisher;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;

/**
 * Endpoint MCP (Streamable HTTP, mode JSON stateless) exécuté par requête,
 * sans processus persistant — nécessaire sur un hébergement mutualisé qui ne
 * permet pas de daemon (pas de PM2/ReactPHP). Implémente uniquement le
 * sous-ensemble JSON-RPC 2.0 requis par un seul outil : publier_article.
 */
class McpController extends Controller
{
    private const PROTOCOL_VERSION = '2025-06-18';

    private const TOOL_NAME = 'publier_article';

    public function handle(Request $request, JobArticlePublisher $publisher): JsonResponse|HttpResponse
    {
        $payload = $request->all();

        // Notification JSON-RPC (pas de "id") : accusé de réception sans corps.
        if (!array_key_exists('id', $payload)) {
            return response('', 202);
        }

        $id     = $payload['id'] ?? null;
        $method = $payload['method'] ?? null;

        return match ($method) {
            'initialize' => $this->rpcResult($id, [
                'protocolVersion' => self::PROTOCOL_VERSION,
                'capabilities'    => ['tools' => ['listChanged' => false]],
                'serverInfo'      => ['name' => 'niangprogrammeur-publisher', 'version' => '1.0.0'],
            ]),
            'tools/list' => $this->rpcResult($id, ['tools' => [$this->toolDefinition()]]),
            'tools/call' => $this->handleToolCall($id, (array) ($payload['params'] ?? []), $publisher),
            'ping'       => $this->rpcResult($id, []),
            default      => $this->rpcError($id, -32601, "Méthode inconnue : {$method}"),
        };
    }

    public function methodNotAllowed(): JsonResponse
    {
        return response()->json([
            'jsonrpc' => '2.0',
            'error'   => ['code' => -32000, 'message' => 'Method not allowed.'],
            'id'      => null,
        ], 405);
    }

    public function health(): JsonResponse
    {
        return response()->json(['ok' => true]);
    }

    private function toolDefinition(): array
    {
        return [
            'name'        => self::TOOL_NAME,
            'title'       => 'Publier un article emploi/opportunité',
            'description' => "Crée un article emploi/opportunité sur niangprogrammeur.com (table job_articles). N'utilise jamais le mot de passe admin.",
            'inputSchema' => [
                'type'       => 'object',
                'properties' => [
                    'category_id'      => ['type' => 'integer', 'description' => "ID de la catégorie job_categories. 1=Offres d'emploi, 2=Bourses d'études, 3=Candidature spontanée, 6=Concours"],
                    'title'            => ['type' => 'string', 'description' => "Titre de l'article"],
                    'content'          => ['type' => 'string', 'description' => "Contenu HTML complet de l'article"],
                    'excerpt'          => ['type' => 'string', 'description' => 'Résumé court affiché sur les cartes'],
                    'cover_image_url'  => ['type' => 'string', 'format' => 'uri', 'description' => "URL de l'image de couverture"],
                    'meta_title'       => ['type' => 'string', 'description' => 'Titre SEO (max 70 caractères)'],
                    'meta_description' => ['type' => 'string', 'description' => 'Description SEO (max 160 caractères)'],
                    'meta_keywords'    => ['type' => 'string', 'description' => 'Mots-clés séparés par des virgules, ex: "emploi, sénégal"'],
                    'status'           => ['type' => 'string', 'enum' => ['draft', 'published', 'archived'], 'default' => 'published'],
                ],
                'required' => ['category_id', 'title', 'content'],
            ],
        ];
    }

    private function handleToolCall(mixed $id, array $params, JobArticlePublisher $publisher): JsonResponse
    {
        if (($params['name'] ?? null) !== self::TOOL_NAME) {
            return $this->rpcError($id, -32602, 'Outil inconnu : ' . ($params['name'] ?? ''));
        }

        $args = (array) ($params['arguments'] ?? []);

        $validator = Validator::make($args, [
            'category_id'      => ['required', 'integer', 'exists:job_categories,id'],
            'title'            => ['required', 'string', 'max:255'],
            'content'          => ['required', 'string'],
            'excerpt'          => ['nullable', 'string', 'max:500'],
            'cover_image_url'  => ['nullable', 'url', 'max:2048'],
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords'    => ['nullable', 'string'],
            'status'           => ['nullable', 'string', 'in:draft,published,archived'],
        ]);

        if ($validator->fails()) {
            return $this->toolError($id, 'Données invalides : ' . $validator->errors()->first());
        }

        $data               = $validator->validated();
        $data['cover_type'] = 'external';

        try {
            $article = $publisher->publish($data);
        } catch (UniqueConstraintViolationException $e) {
            return $this->toolError($id, 'Ce slug existe déjà.');
        }

        $url = route('emplois.article', $article->slug);

        return $this->rpcResult($id, [
            'content' => [[
                'type' => 'text',
                'text' => "Article publié avec succès (statut: {$article->status}) : {$url}",
            ]],
        ]);
    }

    private function toolError(mixed $id, string $message): JsonResponse
    {
        return $this->rpcResult($id, [
            'content' => [['type' => 'text', 'text' => $message]],
            'isError' => true,
        ]);
    }

    private function rpcResult(mixed $id, array $result): JsonResponse
    {
        return response()->json(['jsonrpc' => '2.0', 'id' => $id, 'result' => $result]);
    }

    private function rpcError(mixed $id, int $code, string $message): JsonResponse
    {
        return response()->json(['jsonrpc' => '2.0', 'id' => $id, 'error' => ['code' => $code, 'message' => $message]]);
    }
}
