<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use App\Models\FinanceExchangeRate;
use Illuminate\Database\Seeder;

class FinanceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // DÉPENSES
            ['name' => 'Hébergement',          'type' => 'expense', 'color' => '#EF4444', 'icon' => '🖥️', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Nom de domaine',        'type' => 'expense', 'color' => '#F97316', 'icon' => '🌐', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Publicité / Ads',       'type' => 'expense', 'color' => '#8B5CF6', 'icon' => '📣', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Abonnements logiciels', 'type' => 'expense', 'color' => '#3B82F6', 'icon' => '💾', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Frais bancaires/Wave',  'type' => 'expense', 'color' => '#F59E0B', 'icon' => '🏦', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Freelances / Équipe',   'type' => 'expense', 'color' => '#EC4899', 'icon' => '👥', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Autre dépense',         'type' => 'expense', 'color' => '#6B7280', 'icon' => '💸', 'monthly_budget' => null, 'is_default' => true],
            // REVENUS
            ['name' => 'AdSense / Ezoic',       'type' => 'income',  'color' => '#22C55E', 'icon' => '📈', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Ventes documents',      'type' => 'income',  'color' => '#06B6D4', 'icon' => '📄', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Ventes formations',     'type' => 'income',  'color' => '#14B8A6', 'icon' => '🎓', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Dons',                  'type' => 'income',  'color' => '#84CC16', 'icon' => '🙏', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Affiliation',           'type' => 'income',  'color' => '#10B981', 'icon' => '🤝', 'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Autre revenu',          'type' => 'income',  'color' => '#6B7280', 'icon' => '💰', 'monthly_budget' => null, 'is_default' => true],
        ];

        foreach ($categories as $cat) {
            FinanceCategory::firstOrCreate(['name' => $cat['name'], 'type' => $cat['type']], $cat);
        }

        // Taux de change par défaut (mis à jour manuellement ensuite depuis /admin/finances/exchange-rates)
        $rates = [
            ['currency_from' => 'EUR', 'rate' => 655.957],
            ['currency_from' => 'USD', 'rate' => 600],
        ];

        foreach ($rates as $rate) {
            FinanceExchangeRate::firstOrCreate(
                ['currency_from' => $rate['currency_from'], 'currency_to' => 'XOF', 'is_current' => true],
                ['rate' => $rate['rate'], 'rate_date' => now()->toDateString()]
            );
        }
    }
}
