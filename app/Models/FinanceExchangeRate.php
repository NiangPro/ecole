<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FinanceExchangeRate extends Model
{
    protected $fillable = ['currency_from', 'currency_to', 'rate', 'rate_date', 'is_current'];

    protected $casts = [
        'rate' => 'decimal:4',
        'rate_date' => 'date',
        'is_current' => 'boolean',
    ];

    public static function getRate(string $from, string $to = 'XOF'): float
    {
        if ($from === $to) {
            return 1.0;
        }

        return Cache::remember(self::cacheKey($from, $to), 3600, function () use ($from, $to) {
            return (float) (static::where('currency_from', $from)
                ->where('currency_to', $to)
                ->where('is_current', true)
                ->value('rate') ?? 1.0);
        });
    }

    public static function forgetRateCache(string $from, string $to = 'XOF'): void
    {
        Cache::forget(self::cacheKey($from, $to));
    }

    private static function cacheKey(string $from, string $to): string
    {
        return "finance_rate_{$from}_{$to}";
    }

    public static function convert(float $amount, string $from, string $to = 'XOF'): float
    {
        return round($amount * static::getRate($from, $to), 2);
    }
}
