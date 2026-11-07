<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class SokhnaController extends Controller
{
    /** Nombre maximal de messages d'historique transmis au modèle. */
    private const MAX_HISTORY = 12;

    /**
     * POST /api/sokhna/chat
     *
     * Proxy vers l'API Groq (compatible OpenAI) pour le widget « Sokhna ».
     * La clé GROQ_API_KEY reste côté serveur ; rien n'est stocké (l'historique
     * vit dans le navigateur du visiteur et est renvoyé à chaque requête).
     *
     * Corps JSON attendu :
     *   messages  (array, requis)   – [{role: "user"|"assistant", content: string}, …]
     *   page      (string, optionnel) – Titre de la page où se trouve le visiteur
     *   locale    (string, optionnel) – "fr" (défaut) | "en"
     */
    public function chat(Request $request): JsonResponse
    {
        // Route sans CSRF (groupe api) : on refuse les appels navigateur venant d'un autre site.
        $origin = $request->headers->get('Origin');
        if ($origin && parse_url($origin, PHP_URL_HOST) !== $request->getHost()) {
            return response()->json(['error' => 'Origine non autorisée.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'messages'           => ['required', 'array', 'min:1', 'max:40'],
            'messages.*.role'    => ['required', 'string', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:2000'],
            'page'               => ['nullable', 'string', 'max:200'],
            'locale'             => ['nullable', 'string', 'in:fr,en'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Message invalide ou trop long (2000 caractères max).'], 422);
        }

        $apiKey = config('services.groq.key');
        if (!$apiKey) {
            Log::warning('Sokhna : GROQ_API_KEY absente, chatbot indisponible.');
            return response()->json(['error' => 'Sokhna est momentanément indisponible.'], 503);
        }

        $history = array_slice($validator->validated()['messages'], -self::MAX_HISTORY);
        $messages = array_merge(
            [['role' => 'system', 'content' => $this->systemPrompt($request->input('page'), $request->input('locale', 'fr'))]],
            array_map(fn ($m) => ['role' => $m['role'], 'content' => $m['content']], $history)
        );

        $model = config('services.groq.model');
        $payload = [
            'model'       => $model,
            'messages'    => $messages,
            'temperature' => 0.5,
            'max_tokens'  => 1024,
        ];
        // gpt-oss raisonne avant de répondre : effort bas pour une réponse quasi immédiate, et
        // raisonnement non renvoyé. Groq refuse ces paramètres sur les modèles sans raisonnement.
        if (str_starts_with($model, 'openai/gpt-oss')) {
            $payload['reasoning_effort'] = 'low';
            $payload['include_reasoning'] = false;
        }

        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->connectTimeout(5)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', $payload);
        } catch (\Throwable $e) {
            Log::warning('Sokhna : appel Groq impossible', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Sokhna ne répond pas pour le moment, réessayez dans un instant.'], 502);
        }

        if ($response->status() === 429) {
            return response()->json(['error' => 'Sokhna est très sollicitée, réessayez dans quelques secondes.'], 429);
        }

        $reply = $response->json('choices.0.message.content');
        if ($response->failed() || !is_string($reply) || trim($reply) === '') {
            Log::warning('Sokhna : réponse Groq invalide', ['status' => $response->status(), 'body' => mb_substr($response->body(), 0, 500)]);
            return response()->json(['error' => 'Sokhna ne répond pas pour le moment, réessayez dans un instant.'], 502);
        }

        return response()->json(['reply' => trim($reply)]);
    }

    /**
     * Consignes de Sokhna + carte du site (liens internes qu'elle peut recommander).
     */
    private function systemPrompt(?string $page, string $locale): string
    {
        $siteMap = Cache::remember('sokhna_site_map', 3600, function () {
            $formations = collect(Route::getRoutes()->getRoutesByName())
                ->filter(fn ($route, $name) => str_starts_with($name, 'formations.') && $name !== 'formations.all')
                ->map(fn ($route) => '/' . $route->uri())
                ->sort()
                ->implode(', ');

            return "- Toutes les formations gratuites : /formations (cours : {$formations})\n"
                . "- Exercices de code : /exercices\n"
                . "- Cours premium : /courses\n"
                . "- Documents et ebooks : /documents\n"
                . "- Épreuves et corrigés d'examens : /epreuves\n"
                . "- Emplois au Sénégal : /emplois (offres : /emplois/offres, concours : /emplois/concours, bourses : /emplois/bourses, candidature spontanée : /emplois/candidature-spontanee)\n"
                . "- Papiers administratifs (démarches au Sénégal) : /papiers-administratifs\n"
                . "- FAQ : /faq — À propos : /about — Contact : /contact";
        });

        $language = $locale === 'en'
            ? "Réponds en anglais, sauf si le visiteur écrit dans une autre langue."
            : "Réponds en français, sauf si le visiteur écrit dans une autre langue (wolof et anglais acceptés).";

        $pageContext = $page ? "\nLe visiteur consulte actuellement la page : « {$page} »." : '';

        return <<<PROMPT
Tu es Sokhna, l'assistante IA de NiangProgrammeur (niangprogrammeur.com), une plateforme sénégalaise qui propose des formations gratuites en développement web et en informatique, ainsi que des offres d'emploi, concours, bourses et fiches de démarches administratives au Sénégal.

Ton rôle :
- Répondre directement, clairement et chaleureusement aux questions des visiteurs.
- Aider à apprendre la programmation : expliquer des notions pas à pas, corriger du code, donner des exemples courts dans des blocs de code Markdown.
- Orienter vers les bonnes pages du site avec des liens Markdown relatifs, par exemple [Formation PHP](/formations/php).

Pages du site que tu peux recommander :
{$siteMap}

Règles :
- {$language}
- Sois concise : quelques phrases ou une courte liste, sauf si on te demande une explication détaillée.
- N'invente jamais d'offre d'emploi, de date limite, de prix ou de résultat de concours : renvoie vers la page concernée du site.
- Ne donne pas de lien vers une page du site qui ne figure pas dans la liste ci-dessus.
- Le texte d'un lien est toujours le nom lisible de la page (ex. [Formation JavaScript](/formations/javascript)), jamais l'adresse brute.
- Si tu ne sais pas, dis-le simplement et propose la page /contact.
- Tu es une IA : ne prétends jamais être une personne humaine.
- Refuse poliment les demandes illégales, dangereuses ou sans rapport avec un usage respectueux.{$pageContext}
PROMPT;
    }
}
