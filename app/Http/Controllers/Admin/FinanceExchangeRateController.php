<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceExchangeRate;
use Illuminate\Http\Request;

class FinanceExchangeRateController extends Controller
{
    public function index()
    {
        $current = FinanceExchangeRate::where('is_current', true)->get()->keyBy('currency_from');
        $rates = collect(['EUR', 'USD'])->mapWithKeys(fn ($code) => [$code => $current->get($code)]);

        return view('admin.finances.exchange-rates.index', compact('rates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'currency_from' => 'required|in:EUR,USD',
            'rate' => 'required|numeric|min:1',
        ]);

        FinanceExchangeRate::where('currency_from', $data['currency_from'])
            ->where('is_current', true)
            ->update(['is_current' => false]);

        FinanceExchangeRate::create([
            'currency_from' => $data['currency_from'],
            'currency_to' => 'XOF',
            'rate' => $data['rate'],
            'rate_date' => now()->toDateString(),
            'is_current' => true,
        ]);

        FinanceExchangeRate::forgetRateCache($data['currency_from']);

        return back()->with('success', 'Taux mis à jour.');
    }
}
