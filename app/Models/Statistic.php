<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Statistic extends Model
{
    protected $fillable = [
        'page_url',
        'page_title',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'browser',
        'device',
        'visit_date'
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    // Statistiques par jour
    public static function getByDay($date = null)
    {
        $date = $date ?? Carbon::today();
        return self::whereDate('visit_date', $date)->count();
    }

    // Statistiques par mois
    public static function getByMonth($year = null, $month = null)
    {
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;
        
        return self::whereYear('visit_date', $year)
                   ->whereMonth('visit_date', $month)
                   ->count();
    }

    // Statistiques par année
    public static function getByYear($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        return self::whereYear('visit_date', $year)->count();
    }

    // Pages les plus visitées
    public static function getTopPages($limit = 10, $period = 'month')
    {
        $query = self::select('page_url', 'page_title')
                     ->selectRaw('COUNT(*) as visits');

        if ($period == 'day') {
            $query->whereDate('visit_date', Carbon::today());
        } elseif ($period == 'month') {
            $query->whereMonth('visit_date', Carbon::now()->month)
                  ->whereYear('visit_date', Carbon::now()->year);
        } elseif ($period == 'year') {
            $query->whereYear('visit_date', Carbon::now()->year);
        }

        return $query->groupBy('page_url', 'page_title')
                     ->orderByDesc('visits')
                     ->limit($limit)
                     ->get();
    }

    // Statistiques par jour pour un graphique
    public static function getDailyStats($days = 30)
    {
        return self::selectRaw('DATE(visit_date) as date, COUNT(*) as visits')
                   ->where('visit_date', '>=', Carbon::now()->subDays($days))
                   ->groupBy('date')
                   ->orderBy('date')
                   ->get();
    }

    // Statistiques par pays
    public static function getByCountry($period = 'month', $year = null, $month = null)
    {
        $query = self::select('country')
                     ->selectRaw('COUNT(*) as visits');

        if ($period == 'day') {
            $query->whereDate('visit_date', Carbon::today());
        } elseif ($period == 'month') {
            $year = $year ?? Carbon::now()->year;
            $month = $month ?? Carbon::now()->month;
            $query->whereYear('visit_date', $year)
                  ->whereMonth('visit_date', $month);
        } elseif ($period == 'year') {
            $year = $year ?? Carbon::now()->year;
            $query->whereYear('visit_date', $year);
        }

        return $query->whereNotNull('country')
                     ->groupBy('country')
                     ->orderByDesc('visits')
                     ->limit(10)
                     ->get();
    }

    // Statistiques par navigateur
    public static function getByBrowser($period = 'month', $year = null, $month = null)
    {
        $query = self::select('browser')
                     ->selectRaw('COUNT(*) as visits');

        if ($period == 'day') {
            $query->whereDate('visit_date', Carbon::today());
        } elseif ($period == 'month') {
            $year = $year ?? Carbon::now()->year;
            $month = $month ?? Carbon::now()->month;
            $query->whereYear('visit_date', $year)
                  ->whereMonth('visit_date', $month);
        } elseif ($period == 'year') {
            $year = $year ?? Carbon::now()->year;
            $query->whereYear('visit_date', $year);
        }

        return $query->whereNotNull('browser')
                     ->groupBy('browser')
                     ->orderByDesc('visits')
                     ->get();
    }

    // Statistiques par source (referer)
    public static function getBySource($period = 'month', $year = null, $month = null)
    {
        $query = self::selectRaw('
                CASE 
                    WHEN referer IS NULL OR referer = "" THEN "Direct"
                    WHEN referer LIKE "%google%" THEN "Google"
                    WHEN referer LIKE "%facebook%" THEN "Facebook"
                    WHEN referer LIKE "%twitter%" THEN "Twitter"
                    WHEN referer LIKE "%linkedin%" THEN "LinkedIn"
                    WHEN referer LIKE "%instagram%" THEN "Instagram"
                    ELSE "Other"
                END as source
            ')
            ->selectRaw('COUNT(*) as visits');

        if ($period == 'day') {
            $query->whereDate('visit_date', Carbon::today());
        } elseif ($period == 'month') {
            $year = $year ?? Carbon::now()->year;
            $month = $month ?? Carbon::now()->month;
            $query->whereYear('visit_date', $year)
                  ->whereMonth('visit_date', $month);
        } elseif ($period == 'year') {
            $year = $year ?? Carbon::now()->year;
            $query->whereYear('visit_date', $year);
        }

        return $query->groupBy('source')
                     ->orderByDesc('visits')
                     ->get();
    }

    // Obtenir les années disponibles
    public static function getAvailableYears()
    {
        return self::selectRaw('DISTINCT YEAR(visit_date) as year')
                   ->orderByDesc('year')
                   ->pluck('year');
    }

    // Statistiques mensuelles pour une année
    public static function getMonthlyStatsForYear($year)
    {
        return self::selectRaw('MONTH(visit_date) as month, COUNT(*) as visits')
                   ->whereYear('visit_date', $year)
                   ->groupBy('month')
                   ->orderBy('month')
                   ->get();
    }
}
