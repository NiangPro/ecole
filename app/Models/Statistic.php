<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
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

    // Statistiques par jour - Cache 5 minutes
    public static function getByDay($date = null)
    {
        $date = $date ?? Carbon::today();
        $cacheKey = "statistics_day_{$date->format('Y-m-d')}";
        return Cache::remember($cacheKey, 300, function () use ($date) {
            return self::whereDate('visit_date', $date)->count();
        });
    }

    // Statistiques par mois - Cache 5 minutes
    public static function getByMonth($year = null, $month = null)
    {
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;
        $cacheKey = "statistics_month_{$year}_{$month}";
        
        return Cache::remember($cacheKey, 300, function () use ($year, $month) {
            return self::whereYear('visit_date', $year)
                       ->whereMonth('visit_date', $month)
                       ->count();
        });
    }

    // Statistiques par année - Cache 5 minutes
    public static function getByYear($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $cacheKey = "statistics_year_{$year}";
        
        return Cache::remember($cacheKey, 300, function () use ($year) {
            return self::whereYear('visit_date', $year)->count();
        });
    }

    // Pages les plus visitées - Cache 5 minutes
    public static function getTopPages($limit = 10, $period = 'month')
    {
        $cacheKey = "top_pages_{$period}_{$limit}";
        
        return Cache::remember($cacheKey, 300, function () use ($limit, $period) {
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
        });
    }

    // Statistiques par jour pour un graphique - Cache 5 minutes
    public static function getDailyStats($days = 30)
    {
        $cacheKey = "daily_stats_{$days}_days";
        
        return Cache::remember($cacheKey, 300, function () use ($days) {
            return self::selectRaw('DATE(visit_date) as date, COUNT(*) as visits')
                       ->where('visit_date', '>=', Carbon::now()->subDays($days))
                       ->groupBy('date')
                       ->orderBy('date')
                       ->get();
        });
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

    // Statistiques par appareil
    public static function getByDevice($period = 'month', $year = null, $month = null)
    {
        $query = self::select('device')
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

        return $query->whereNotNull('device')
                     ->groupBy('device')
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

    // Statistiques hebdomadaires du mois actuel - Cache 5 minutes
    public static function getWeeklyStatsForCurrentMonth()
    {
        $now = Carbon::now();
        $cacheKey = "weekly_stats_{$now->year}_{$now->month}";
        
        return Cache::remember($cacheKey, 300, function () use ($now) {
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();
            $daysInMonth = $startOfMonth->copy()->daysInMonth;
            
            // Calculer les semaines du mois (4-5 semaines)
            $weeks = [];
            $weekCounter = 1;
            $currentDay = 1;
            
            while ($currentDay <= $daysInMonth) {
                $weekStartDate = $startOfMonth->copy()->addDays($currentDay - 1);
                $weekEndDay = min($currentDay + 6, $daysInMonth);
                $weekEndDate = $startOfMonth->copy()->addDays($weekEndDay - 1);
                
                // Compter les visites pour cette semaine
                $visits = self::whereBetween('visit_date', [
                    $weekStartDate->format('Y-m-d'), 
                    $weekEndDate->format('Y-m-d')
                ])->count();
                
                // Ajouter la semaine à la liste
                $weeks[] = [
                    'week' => $weekCounter,
                    'week_start' => $weekStartDate->format('Y-m-d'),
                    'week_end' => $weekEndDate->format('Y-m-d'),
                    'visits' => $visits,
                    'label' => 'Sem ' . $weekCounter . ' (' . $weekStartDate->format('d/m') . ' - ' . $weekEndDate->format('d/m') . ')'
                ];
                
                // Passer à la semaine suivante
                $currentDay += 7;
                $weekCounter++;
            }
            
            return collect($weeks);
        });
    }

    /**
     * Boot du modèle - Invalider le cache lors de la création d'une nouvelle statistique
     */
    protected static function boot()
    {
        parent::boot();

        // Invalider les caches de statistiques lors de la création
        // Utiliser un système de cache avec délai pour éviter trop d'invalidations
        static::created(function ($statistic) {
            $visitDate = Carbon::parse($statistic->visit_date);
            $today = Carbon::today();
            $month = $today->month;
            $year = $today->year;
            
            // Invalider les caches seulement si la visite est d'aujourd'hui
            // Pour éviter d'invalider les caches des jours/mois/années passés
            if ($visitDate->isToday()) {
                $todayKey = $today->format('Y-m-d');
                Cache::forget("statistics_day_{$todayKey}");
                Cache::forget("top_pages_day");
                Cache::forget("dashboard_today_visits");
            }
            
            // Invalider les caches du mois (seulement si c'est le mois actuel)
            if ($visitDate->month == $month && $visitDate->year == $year) {
                Cache::forget("statistics_month_{$year}_{$month}");
                Cache::forget("top_pages_month");
                Cache::forget("dashboard_month_visits");
                Cache::forget("weekly_stats_{$year}_{$month}");
            }
            
            // Invalider les caches de l'année (seulement si c'est l'année actuelle)
            if ($visitDate->year == $year) {
                Cache::forget("statistics_year_{$year}");
                Cache::forget("top_pages_year");
            }
            
            // Invalider les caches des statistiques détaillées (seulement si récent)
            if ($visitDate->gte(Carbon::now()->subDays(30))) {
                Cache::forget("daily_stats_30_days");
            }
        });
    }
}
