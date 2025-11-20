<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\JobArticle;
use Carbon\Carbon;

class ArticleSeederController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => JobArticle::count(),
            'published' => JobArticle::where('status', 'published')->count(),
            'draft' => JobArticle::where('status', 'draft')->count(),
            'today' => JobArticle::whereDate('created_at', today())->count(),
            'this_week' => JobArticle::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => JobArticle::whereMonth('created_at', Carbon::now()->month)->count(),
        ];

        return view('admin.articles.seeder', compact('stats'));
    }

    public function seed(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'days' => 'nullable|integer|min:0|max:30',
        ]);

        $count = $request->input('count', 5);
        $categoryId = $request->input('category_id');
        $days = $request->input('days', 3);

        try {
            $command = "articles:seed --count={$count} --days={$days}";
            if ($categoryId) {
                $command .= " --category={$categoryId}";
            }

            Artisan::call($command);
            $output = Artisan::output();

            return back()->with('success', "✅ {$count} article(s) créé(s) avec succès !\n" . $output);
        } catch (\Exception $e) {
            return back()->with('error', '❌ Erreur lors de la création des articles : ' . $e->getMessage());
        }
    }
}

