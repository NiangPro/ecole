<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Audit SEO-04 : une page de liste au-delà de la dernière (ex. ?page=9999) renvoyait une
 * page vide en 200, indexable. On renvoie une 404 si la vue contient un paginateur dont
 * la page courante (> 1) dépasse la dernière page.
 */
class PaginationOutOfRange
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') || ! $request->has('page') || $response->getStatusCode() !== 200) {
            return $response;
        }

        $view = $response->original ?? null;
        if (! $view instanceof View) {
            return $response;
        }

        foreach ($view->getData() as $value) {
            if ($value instanceof LengthAwarePaginator
                && $value->currentPage() > 1
                && $value->currentPage() > $value->lastPage()) {
                abort(404);
            }
        }

        return $response;
    }
}
