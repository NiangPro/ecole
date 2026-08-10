<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class FinanceCategory extends Model
{
    use HasFactory;

    private const ACTIVE_CACHE_KEY = 'finance_categories_active';

    protected $fillable = [
        'name', 'type', 'color', 'icon',
        'monthly_budget', 'is_default', 'is_active',
    ];

    protected $casts = [
        'monthly_budget' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    public function recurrings()
    {
        return $this->hasMany(FinanceRecurring::class);
    }

    public function currentMonthAmount()
    {
        return $this->transactions()
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount_xof');
    }

    public function isBudgetExceeded(): bool
    {
        if (!$this->monthly_budget) {
            return false;
        }

        return $this->currentMonthAmount() > $this->monthly_budget;
    }

    /**
     * Catégories actives, mises en cache 1h (invalidée via forgetActiveCache()
     * à chaque création/modification/suppression de catégorie).
     */
    public static function activeCached()
    {
        return Cache::remember(self::ACTIVE_CACHE_KEY, 3600, function () {
            return static::where('is_active', true)->get();
        });
    }

    public static function forgetActiveCache(): void
    {
        Cache::forget(self::ACTIVE_CACHE_KEY);
    }
}
