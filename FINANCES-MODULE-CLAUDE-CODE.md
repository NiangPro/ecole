# Module Gestion Financière — Prompt Claude Code
## NiangProgrammeur.com (Laravel)

> **Nouveau module admin — sans lien avec le système existant**  
> Objectif : suivi des dépenses et revenus, récurrents configurables, notifications dashboard, reporting avancé

---

## SPÉCIFICATIONS RETENUES

| Paramètre | Choix |
|---|---|
| Saisie des données | 100% manuelle |
| Récurrence | Jour X du mois OU toutes les N jours (les deux) |
| Rappels | Notification dashboard uniquement |
| Délai de rappel | Configurable par transaction récurrente |
| Catégories | Prédéfinies + personnalisables |
| Budget cible | Par catégorie, avec alerte si dépassé |
| Rapports | Avancés (graphiques, prévisions, export PDF/Excel, comparaison mois) |
| Accès | Admin uniquement (rôle `is_admin`) |
| Devise | XOF principal + EUR + USD (multi-devise) |
| Ajout rapide | Modal "Quick Add" sur le dashboard |
| Graphiques | Bar chart annuel + Donut par catégorie |
| Rapports | Période personnalisable + tri date/montant |
| Modèles | Ajout rapide depuis les récurrents (templates) |

---

## PHASE 1 — Migrations (nouvelles tables, sans toucher à l'existant)

Crée les 4 migrations suivantes dans l'ordre exact :

### Migration 1 : `create_finance_categories_table`

> ⚠️ **Correction rôle** : Dans tout ce document, remplacer `is_super_admin` par `is_admin` — la plateforme n'a que deux rôles : admin et utilisateur.

```bash
php artisan make:migration create_finance_categories_table
```

```php
Schema::create('finance_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');                          // Ex: "Hébergement", "AdSense"
    $table->enum('type', ['income', 'expense']);     // Revenu ou dépense
    $table->string('color', 7)->default('#06B6D4'); // Hex couleur pour l'UI
    $table->string('icon', 50)->default('💰');       // Emoji ou nom d'icône
    $table->decimal('monthly_budget', 12, 2)->nullable(); // Budget mensuel cible (nullable)
    $table->boolean('is_default')->default(false);  // Catégorie système (non supprimable)
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### Migration 2 : `create_finance_transactions_table`

```bash
php artisan make:migration create_finance_transactions_table
```

```php
Schema::create('finance_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('finance_category_id')->constrained('finance_categories')->onDelete('restrict');
    $table->enum('type', ['income', 'expense']);
    $table->decimal('amount', 12, 2);               // Montant dans la devise saisie
    $table->string('currency', 3)->default('XOF');  // XOF | EUR | USD
    $table->decimal('amount_xof', 12, 2)->nullable();// Montant converti en XOF (rapports unifiés)
    $table->decimal('exchange_rate', 10, 4)->default(1); // Taux de change utilisé
    $table->string('label');                         // Description courte
    $table->text('notes')->nullable();               // Notes optionnelles
    $table->date('transaction_date');                // Date effective de la transaction
    $table->boolean('is_recurring_instance')->default(false); // Générée par un récurrent ?
    $table->foreignId('finance_recurring_id')->nullable()->constrained('finance_recurrings')->nullOnDelete();
    $table->timestamps();
});
```

> ⚠️ La migration 2 référence `finance_recurrings` — créer la migration 3 D'ABORD, puis revenir ici.  
> **Ordre correct : migration 1 → migration 3 → migration 2 → migration 4**

### Migration 3 : `create_finance_recurrings_table`

```bash
php artisan make:migration create_finance_recurrings_table
```

```php
Schema::create('finance_recurrings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('finance_category_id')->constrained('finance_categories')->onDelete('restrict');
    $table->enum('type', ['income', 'expense']);
    $table->decimal('amount', 12, 2);
    $table->string('label');
    $table->text('notes')->nullable();
    
    // Type de récurrence
    $table->enum('recurrence_type', ['day_of_month', 'every_n_days']);
    // Si day_of_month : jour du mois (1-31)
    // Si every_n_days : nombre de jours entre chaque occurrence
    $table->integer('recurrence_value');
    
    $table->integer('reminder_days_before')->default(3); // Délai rappel configurable
    $table->date('next_due_date');                       // Prochaine échéance calculée
    $table->date('last_generated_date')->nullable();     // Dernière fois qu'une transaction a été générée
    $table->boolean('auto_create_transaction')->default(false); // Créer la transaction automatiquement ?
    $table->boolean('is_active')->default(true);
    $table->date('start_date');
    $table->date('end_date')->nullable();                // Null = indéfini
    $table->timestamps();
});
```

### Migration 4 : `create_finance_notifications_table`

```bash
php artisan make:migration create_finance_notifications_table
```

```php
Schema::create('finance_notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('finance_recurring_id')->constrained('finance_recurrings')->onDelete('cascade');
    $table->string('title');
    $table->text('message');
    $table->date('due_date');
    $table->boolean('is_read')->default(false);
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});
```

**Exécuter dans cet ordre :**
```bash
php artisan migrate
```

---

## PHASE 2 — Modèles Eloquent

### `app/Models/FinanceCategory.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinanceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'color', 'icon',
        'monthly_budget', 'is_default', 'is_active'
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

    // Dépenses du mois en cours pour cette catégorie
    public function currentMonthAmount()
    {
        return $this->transactions()
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');
    }

    // Alerte budget dépassé ?
    public function isBudgetExceeded(): bool
    {
        if (!$this->monthly_budget) return false;
        return $this->currentMonthAmount() > $this->monthly_budget;
    }
}
```

### `app/Models/FinanceTransaction.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'finance_category_id', 'type', 'amount',
        'label', 'notes', 'transaction_date',
        'is_recurring_instance', 'finance_recurring_id'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring_instance' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function recurring()
    {
        return $this->belongsTo(FinanceRecurring::class, 'finance_recurring_id');
    }
}
```

### `app/Models/FinanceRecurring.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FinanceRecurring extends Model
{
    protected $fillable = [
        'finance_category_id', 'type', 'amount', 'label', 'notes',
        'recurrence_type', 'recurrence_value', 'reminder_days_before',
        'next_due_date', 'last_generated_date', 'auto_create_transaction',
        'is_active', 'start_date', 'end_date'
    ];

    protected $casts = [
        'next_due_date' => 'date',
        'last_generated_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'auto_create_transaction' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function notifications()
    {
        return $this->hasMany(FinanceNotification::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    // Calculer la prochaine échéance après une date donnée
    public function calculateNextDueDate(Carbon $from = null): Carbon
    {
        $from = $from ?? now();

        if ($this->recurrence_type === 'day_of_month') {
            $day = $this->recurrence_value;
            $next = $from->copy()->day($day);
            if ($next->lte($from)) {
                $next->addMonth();
            }
            // Gérer les mois courts (ex: jour 31 en février)
            while ($next->day !== $day && $day > 28) {
                $next->endOfMonth();
                break;
            }
            return $next;
        }

        // every_n_days
        return $from->copy()->addDays($this->recurrence_value);
    }
}
```

### `app/Models/FinanceNotification.php`

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceNotification extends Model
{
    protected $fillable = [
        'finance_recurring_id', 'title', 'message',
        'due_date', 'is_read', 'read_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function recurring()
    {
        return $this->belongsTo(FinanceRecurring::class);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }
}
```

---

## PHASE 3 — Seeder Catégories par défaut

```bash
php artisan make:seeder FinanceCategorySeeder
```

```php
<?php
namespace Database\Seeders;

use App\Models\FinanceCategory;
use Illuminate\Database\Seeder;

class FinanceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // DÉPENSES
            ['name' => 'Hébergement',          'type' => 'expense', 'color' => '#EF4444', 'icon' => '🖥️',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Nom de domaine',        'type' => 'expense', 'color' => '#F97316', 'icon' => '🌐',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Publicité / Ads',       'type' => 'expense', 'color' => '#8B5CF6', 'icon' => '📣',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Abonnements logiciels', 'type' => 'expense', 'color' => '#3B82F6', 'icon' => '💾',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Frais bancaires/Wave',  'type' => 'expense', 'color' => '#F59E0B', 'icon' => '🏦',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Freelances / Équipe',   'type' => 'expense', 'color' => '#EC4899', 'icon' => '👥',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Autre dépense',         'type' => 'expense', 'color' => '#6B7280', 'icon' => '💸',  'monthly_budget' => null, 'is_default' => true],
            // REVENUS
            ['name' => 'AdSense / Ezoic',       'type' => 'income',  'color' => '#22C55E', 'icon' => '📈',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Ventes documents',      'type' => 'income',  'color' => '#06B6D4', 'icon' => '📄',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Ventes formations',     'type' => 'income',  'color' => '#14B8A6', 'icon' => '🎓',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Dons',                  'type' => 'income',  'color' => '#84CC16', 'icon' => '🙏',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Affiliation',           'type' => 'income',  'color' => '#10B981', 'icon' => '🤝',  'monthly_budget' => null, 'is_default' => true],
            ['name' => 'Autre revenu',          'type' => 'income',  'color' => '#6B7280', 'icon' => '💰',  'monthly_budget' => null, 'is_default' => true],
        ];

        foreach ($categories as $cat) {
            FinanceCategory::firstOrCreate(['name' => $cat['name'], 'type' => $cat['type']], $cat);
        }
    }
}
```

```bash
php artisan db:seed --class=FinanceCategorySeeder
```

---

## PHASE 4 — Console Command (Scheduler de rappels)

```bash
php artisan make:command CheckFinanceReminders
```

```php
<?php
namespace App\Console\Commands;

use App\Models\FinanceNotification;
use App\Models\FinanceRecurring;
use App\Models\FinanceTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckFinanceReminders extends Command
{
    protected $signature   = 'finance:check-reminders';
    protected $description = 'Vérifie les dépenses/revenus récurrents et génère les notifications dashboard';

    public function handle(): int
    {
        $today    = now()->toDateString();
        $actives  = FinanceRecurring::where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        $generated = 0;

        foreach ($actives as $recurring) {
            $dueDate      = $recurring->next_due_date;
            $reminderDate = $dueDate->copy()->subDays($recurring->reminder_days_before);

            // Générer notification si on est dans la fenêtre de rappel
            if (now()->gte($reminderDate) && now()->lt($dueDate->copy()->addDay())) {
                // Éviter les doublons : pas de notification si déjà créée pour cette échéance
                $exists = FinanceNotification::where('finance_recurring_id', $recurring->id)
                    ->where('due_date', $dueDate->toDateString())
                    ->exists();

                if (!$exists) {
                    $typeLabel = $recurring->type === 'expense' ? 'Dépense' : 'Revenu';
                    FinanceNotification::create([
                        'finance_recurring_id' => $recurring->id,
                        'title'   => "⏰ {$typeLabel} récurrent : {$recurring->label}",
                        'message' => "Échéance le " . $dueDate->format('d/m/Y') . " — " . number_format($recurring->amount, 0, ',', ' ') . " XOF",
                        'due_date' => $dueDate->toDateString(),
                        'is_read'  => false,
                    ]);
                    $generated++;
                }
            }

            // Si la date d'échéance est passée, calculer la prochaine
            if (now()->gt($dueDate)) {
                // Créer la transaction si auto_create est activé
                if ($recurring->auto_create_transaction) {
                    $alreadyCreated = FinanceTransaction::where('finance_recurring_id', $recurring->id)
                        ->whereDate('transaction_date', $dueDate->toDateString())
                        ->exists();

                    if (!$alreadyCreated) {
                        FinanceTransaction::create([
                            'finance_category_id'  => $recurring->finance_category_id,
                            'type'                 => $recurring->type,
                            'amount'               => $recurring->amount,
                            'label'                => $recurring->label,
                            'notes'                => 'Générée automatiquement',
                            'transaction_date'     => $dueDate->toDateString(),
                            'is_recurring_instance' => true,
                            'finance_recurring_id'  => $recurring->id,
                        ]);
                    }
                }

                // Mettre à jour next_due_date
                $recurring->update([
                    'last_generated_date' => $dueDate->toDateString(),
                    'next_due_date'       => $recurring->calculateNextDueDate($dueDate)->toDateString(),
                ]);
            }
        }

        $this->info("Finances check done: {$generated} notification(s) générée(s).");
        return Command::SUCCESS;
    }
}
```

**Enregistrer dans `app/Console/Kernel.php` :**

```php
protected function schedule(Schedule $schedule): void
{
    // ... autres tâches existantes ...
    $schedule->command('finance:check-reminders')->dailyAt('07:00');
}
```

---

## PHASE 5 — Routes Admin

Dans `routes/web.php` (ou `routes/admin.php`), dans le groupe middleware admin :

```php
// === MODULE FINANCES ===
Route::prefix('admin/finances')->name('admin.finances.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard principal
    Route::get('/', [App\Http\Controllers\Admin\FinanceController::class, 'dashboard'])->name('dashboard');

    // Transactions
    Route::resource('transactions', App\Http\Controllers\Admin\FinanceTransactionController::class)
        ->except(['show']);
    Route::get('transactions/export', [App\Http\Controllers\Admin\FinanceTransactionController::class, 'export'])
        ->name('transactions.export');

    // Récurrents
    Route::resource('recurring', App\Http\Controllers\Admin\FinanceRecurringController::class)
        ->except(['show']);
    Route::patch('recurring/{recurring}/toggle', [App\Http\Controllers\Admin\FinanceRecurringController::class, 'toggle'])
        ->name('recurring.toggle');

    // Catégories
    Route::resource('categories', App\Http\Controllers\Admin\FinanceCategoryController::class)
        ->except(['show']);

    // Notifications
    Route::get('notifications', [App\Http\Controllers\Admin\FinanceNotificationController::class, 'index'])
        ->name('notifications.index');
    Route::patch('notifications/{notification}/read', [App\Http\Controllers\Admin\FinanceNotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::patch('notifications/read-all', [App\Http\Controllers\Admin\FinanceNotificationController::class, 'markAllRead'])
        ->name('notifications.read-all');

    // API JSON pour graphiques
    Route::get('api/chart-data', [App\Http\Controllers\Admin\FinanceController::class, 'chartData'])
        ->name('api.chart');
    Route::get('api/donut-data', [App\Http\Controllers\Admin\FinanceController::class, 'donutData'])
        ->name('api.donut');

    // Taux de change
    Route::get('exchange-rates', [App\Http\Controllers\Admin\FinanceExchangeRateController::class, 'index'])
        ->name('exchange-rates.index');
    Route::post('exchange-rates', [App\Http\Controllers\Admin\FinanceExchangeRateController::class, 'store'])
        ->name('exchange-rates.store');

    // Quick Add (POST depuis le dashboard via modal)
    Route::post('quick-add', [App\Http\Controllers\Admin\FinanceTransactionController::class, 'quickStore'])
        ->name('transactions.quick-store');

    // Rapports par période
    Route::get('reports', [App\Http\Controllers\Admin\FinanceController::class, 'reports'])
        ->name('reports');
});
```

---

## PHASE 6 — Contrôleurs

### `app/Http/Controllers/Admin/FinanceController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceNotification;
use App\Models\FinanceRecurring;
use App\Models\FinanceTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function dashboard()
    {
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        // Totaux du mois
        $monthIncome  = FinanceTransaction::where('type', 'income')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $monthExpense = FinanceTransaction::where('type', 'expense')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $balance = $monthIncome - $monthExpense;

        // Totaux année
        $yearIncome  = FinanceTransaction::where('type', 'income')
            ->whereYear('transaction_date', $currentYear)->sum('amount');
        $yearExpense = FinanceTransaction::where('type', 'expense')
            ->whereYear('transaction_date', $currentYear)->sum('amount');

        // Catégories avec budget dépassé
        $budgetAlerts = FinanceCategory::where('type', 'expense')
            ->whereNotNull('monthly_budget')
            ->get()
            ->filter(fn($cat) => $cat->isBudgetExceeded());

        // Prochaines échéances (30 prochains jours)
        $upcomingRecurrings = FinanceRecurring::where('is_active', true)
            ->where('next_due_date', '<=', now()->addDays(30))
            ->orderBy('next_due_date')
            ->with('category')
            ->get();

        // Notifications non lues
        $unreadNotifications = FinanceNotification::where('is_read', false)
            ->with('recurring.category')
            ->orderBy('due_date')
            ->get();

        // Dernières transactions
        $recentTransactions = FinanceTransaction::with('category')
            ->orderByDesc('transaction_date')
            ->limit(10)
            ->get();

        // Données par catégorie (mois en cours)
        $categoryBreakdown = FinanceCategory::with(['transactions' => function ($q) use ($currentYear, $currentMonth) {
            $q->whereYear('transaction_date', $currentYear)
              ->whereMonth('transaction_date', $currentMonth);
        }])->get()->map(function ($cat) {
            return [
                'name'   => $cat->name,
                'type'   => $cat->type,
                'color'  => $cat->color,
                'icon'   => $cat->icon,
                'amount' => $cat->transactions->sum('amount'),
                'budget' => $cat->monthly_budget,
            ];
        })->filter(fn($c) => $c['amount'] > 0);

        return view('admin.finances.dashboard', compact(
            'monthIncome', 'monthExpense', 'balance',
            'yearIncome', 'yearExpense',
            'budgetAlerts', 'upcomingRecurrings',
            'unreadNotifications', 'recentTransactions',
            'categoryBreakdown'
        ));
    }

    public function chartData(Request $request)
    {
        $year = $request->get('year', now()->year);
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $income  = FinanceTransaction::where('type', 'income')
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $m)
                ->sum('amount');
            $expense = FinanceTransaction::where('type', 'expense')
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $m)
                ->sum('amount');

            $data[] = [
                'month'   => Carbon::createFromDate($year, $m, 1)->translatedFormat('M'),
                'income'  => (float) $income,
                'expense' => (float) $expense,
                'balance' => (float) ($income - $expense),
            ];
        }

        return response()->json($data);
    }
}
```

### `app/Http/Controllers/Admin/FinanceTransactionController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;

class FinanceTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = FinanceTransaction::with('category')->orderByDesc('transaction_date');

        if ($request->type)     $query->where('type', $request->type);
        if ($request->category) $query->where('finance_category_id', $request->category);
        if ($request->month)    $query->whereMonth('transaction_date', $request->month);
        if ($request->year)     $query->whereYear('transaction_date', $request->year);

        $transactions = $query->paginate(25);
        $categories   = FinanceCategory::where('is_active', true)->get();

        return view('admin.finances.transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = FinanceCategory::where('is_active', true)->get()->groupBy('type');
        return view('admin.finances.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'type'                => 'required|in:income,expense',
            'amount'              => 'required|numeric|min:1',
            'label'               => 'required|string|max:255',
            'notes'               => 'nullable|string',
            'transaction_date'    => 'required|date',
        ]);

        FinanceTransaction::create($data);
        return redirect()->route('admin.finances.transactions.index')
            ->with('success', 'Transaction ajoutée avec succès.');
    }

    public function edit(FinanceTransaction $transaction)
    {
        $categories = FinanceCategory::where('is_active', true)->get()->groupBy('type');
        return view('admin.finances.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, FinanceTransaction $transaction)
    {
        $data = $request->validate([
            'finance_category_id' => 'required|exists:finance_categories,id',
            'type'                => 'required|in:income,expense',
            'amount'              => 'required|numeric|min:1',
            'label'               => 'required|string|max:255',
            'notes'               => 'nullable|string',
            'transaction_date'    => 'required|date',
        ]);

        $transaction->update($data);
        return redirect()->route('admin.finances.transactions.index')
            ->with('success', 'Transaction modifiée.');
    }

    public function destroy(FinanceTransaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'Transaction supprimée.');
    }

    public function export(Request $request)
    {
        // Export CSV simple (à améliorer avec Laravel Excel si installé)
        $transactions = FinanceTransaction::with('category')
            ->when($request->year, fn($q) => $q->whereYear('transaction_date', $request->year))
            ->when($request->month, fn($q) => $q->whereMonth('transaction_date', $request->month))
            ->orderByDesc('transaction_date')
            ->get();

        $filename = 'finances-' . now()->format('Y-m') . '.csv';
        $headers  = ['Content-Type' => 'text/csv; charset=UTF-8'];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
            fputcsv($file, ['Date', 'Type', 'Catégorie', 'Libellé', 'Montant (XOF)', 'Notes'], ';');
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->transaction_date->format('d/m/Y'),
                    $t->type === 'income' ? 'Revenu' : 'Dépense',
                    $t->category->name,
                    $t->label,
                    number_format($t->amount, 0, ',', ' '),
                    $t->notes ?? '',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers + ['Content-Disposition' => "attachment; filename={$filename}"]);
    }
}
```

### `app/Http/Controllers/Admin/FinanceRecurringController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceCategory;
use App\Models\FinanceRecurring;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinanceRecurringController extends Controller
{
    public function index()
    {
        $recurrings = FinanceRecurring::with('category')->orderBy('next_due_date')->get();
        return view('admin.finances.recurring.index', compact('recurrings'));
    }

    public function create()
    {
        $categories = FinanceCategory::where('is_active', true)->get()->groupBy('type');
        return view('admin.finances.recurring.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'finance_category_id'   => 'required|exists:finance_categories,id',
            'type'                  => 'required|in:income,expense',
            'amount'                => 'required|numeric|min:1',
            'label'                 => 'required|string|max:255',
            'notes'                 => 'nullable|string',
            'recurrence_type'       => 'required|in:day_of_month,every_n_days',
            'recurrence_value'      => 'required|integer|min:1|max:365',
            'reminder_days_before'  => 'required|integer|min:0|max:30',
            'auto_create_transaction' => 'boolean',
            'start_date'            => 'required|date',
            'end_date'              => 'nullable|date|after:start_date',
        ]);

        $data['is_active'] = true;
        $data['auto_create_transaction'] = $request->boolean('auto_create_transaction');

        // Calculer next_due_date
        $temp = new FinanceRecurring($data);
        $data['next_due_date'] = $temp->calculateNextDueDate(Carbon::parse($data['start_date']))->toDateString();

        FinanceRecurring::create($data);
        return redirect()->route('admin.finances.recurring.index')
            ->with('success', 'Récurrent créé avec succès.');
    }

    public function edit(FinanceRecurring $recurring)
    {
        $categories = FinanceCategory::where('is_active', true)->get()->groupBy('type');
        return view('admin.finances.recurring.edit', compact('recurring', 'categories'));
    }

    public function update(Request $request, FinanceRecurring $recurring)
    {
        $data = $request->validate([
            'finance_category_id'   => 'required|exists:finance_categories,id',
            'type'                  => 'required|in:income,expense',
            'amount'                => 'required|numeric|min:1',
            'label'                 => 'required|string|max:255',
            'notes'                 => 'nullable|string',
            'recurrence_type'       => 'required|in:day_of_month,every_n_days',
            'recurrence_value'      => 'required|integer|min:1|max:365',
            'reminder_days_before'  => 'required|integer|min:0|max:30',
            'auto_create_transaction' => 'boolean',
            'start_date'            => 'required|date',
            'end_date'              => 'nullable|date|after:start_date',
        ]);

        $data['auto_create_transaction'] = $request->boolean('auto_create_transaction');

        // Recalculer next_due_date si les paramètres de récurrence changent
        if ($data['recurrence_type'] !== $recurring->recurrence_type ||
            $data['recurrence_value'] !== $recurring->recurrence_value) {
            $temp = new FinanceRecurring($data);
            $data['next_due_date'] = $temp->calculateNextDueDate()->toDateString();
        }

        $recurring->update($data);
        return redirect()->route('admin.finances.recurring.index')
            ->with('success', 'Récurrent mis à jour.');
    }

    public function destroy(FinanceRecurring $recurring)
    {
        $recurring->delete();
        return back()->with('success', 'Récurrent supprimé.');
    }

    public function toggle(FinanceRecurring $recurring)
    {
        $recurring->update(['is_active' => !$recurring->is_active]);
        return back()->with('success', $recurring->is_active ? 'Activé.' : 'Désactivé.');
    }
}
```

### `app/Http/Controllers/Admin/FinanceNotificationController.php`

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceNotification;

class FinanceNotificationController extends Controller
{
    public function index()
    {
        $notifications = FinanceNotification::with('recurring.category')
            ->orderBy('is_read')
            ->orderBy('due_date')
            ->paginate(20);

        return view('admin.finances.notifications.index', compact('notifications'));
    }

    public function markRead(FinanceNotification $notification)
    {
        $notification->markAsRead();
        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllRead()
    {
        FinanceNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return back()->with('success', 'Toutes les notifications ont été lues.');
    }
}
```

---

## PHASE 7 — Vues Blade (structure)

Crée l'arborescence suivante :
```
resources/views/admin/finances/
├── dashboard.blade.php         ← Vue principale avec graphiques Chart.js
├── transactions/
│   ├── index.blade.php         ← Liste filtrée + export CSV
│   ├── create.blade.php        ← Formulaire ajout
│   └── edit.blade.php          ← Formulaire édition
├── recurring/
│   ├── index.blade.php         ← Liste des récurrents
│   ├── create.blade.php        ← Formulaire ajout récurrent
│   └── edit.blade.php          ← Formulaire édition récurrent
├── categories/
│   ├── index.blade.php         ← Liste + gestion budget
│   ├── create.blade.php
│   └── edit.blade.php
└── notifications/
    └── index.blade.php         ← Toutes les notifications
```

### Template `dashboard.blade.php` (structure à remplir)

```blade
@extends('layouts.admin')

@section('title', 'Gestion Financière')

@section('content')
<div class="p-6">

    {{-- En-tête --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">💰 Gestion Financière</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.finances.transactions.create') }}" 
               class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                + Transaction
            </a>
            <a href="{{ route('admin.finances.recurring.create') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">
                + Récurrent
            </a>
        </div>
    </div>

    {{-- Alertes budget --}}
    @if($budgetAlerts->count())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
        <p class="font-semibold text-red-700">⚠️ Budget dépassé ce mois :</p>
        @foreach($budgetAlerts as $cat)
        <p class="text-red-600 text-sm">{{ $cat->icon }} {{ $cat->name }} —
            {{ number_format($cat->currentMonthAmount(), 0, ',', ' ') }} XOF
            / {{ number_format($cat->monthly_budget, 0, ',', ' ') }} XOF
        </p>
        @endforeach
    </div>
    @endif

    {{-- Notifications non lues --}}
    @if($unreadNotifications->count())
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex justify-between">
            <p class="font-semibold text-yellow-800">🔔 {{ $unreadNotifications->count() }} rappel(s) en attente</p>
            <form method="POST" action="{{ route('admin.finances.notifications.read-all') }}">
                @csrf @method('PATCH')
                <button class="text-xs text-yellow-600 underline">Tout marquer lu</button>
            </form>
        </div>
        @foreach($unreadNotifications as $notif)
        <div class="flex justify-between items-center mt-2 text-sm">
            <span class="text-yellow-700">{{ $notif->title }} — {{ $notif->due_date->format('d/m/Y') }}</span>
            <form method="POST" action="{{ route('admin.finances.notifications.read', $notif) }}">
                @csrf @method('PATCH')
                <button class="text-xs text-gray-500">✓ Lu</button>
            </form>
        </div>
        @endforeach
    </div>
    @endif

    {{-- KPIs du mois --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="text-sm text-green-600 font-medium">Revenus ce mois</p>
            <p class="text-3xl font-bold text-green-700">{{ number_format($monthIncome, 0, ',', ' ') }} <span class="text-lg">XOF</span></p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <p class="text-sm text-red-600 font-medium">Dépenses ce mois</p>
            <p class="text-3xl font-bold text-red-700">{{ number_format($monthExpense, 0, ',', ' ') }} <span class="text-lg">XOF</span></p>
        </div>
        <div class="rounded-xl p-5 {{ $balance >= 0 ? 'bg-cyan-50 border border-cyan-200' : 'bg-orange-50 border border-orange-200' }}">
            <p class="text-sm font-medium {{ $balance >= 0 ? 'text-cyan-600' : 'text-orange-600' }}">Solde ce mois</p>
            <p class="text-3xl font-bold {{ $balance >= 0 ? 'text-cyan-700' : 'text-orange-700' }}">
                {{ $balance >= 0 ? '+' : '' }}{{ number_format($balance, 0, ',', ' ') }} <span class="text-lg">XOF</span>
            </p>
        </div>
    </div>

    {{-- Graphique annuel --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-700 dark:text-gray-200">📊 Revenus vs Dépenses — {{ date('Y') }}</h2>
            <select id="chartYear" class="text-sm border rounded px-2 py-1">
                @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <canvas id="financeChart" height="100"></canvas>
    </div>

    {{-- Prochaines échéances --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <h2 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">📅 Prochaines échéances (30 jours)</h2>
        @forelse($upcomingRecurrings as $r)
        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <span>{{ $r->category->icon }}</span>
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $r->label }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $r->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $r->type === 'income' ? 'Revenu' : 'Dépense' }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold {{ $r->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($r->amount, 0, ',', ' ') }} XOF
                </p>
                <p class="text-xs text-gray-500">{{ $r->next_due_date->format('d/m/Y') }}</p>
            </div>
        </div>
        @empty
        <p class="text-gray-400 text-sm">Aucune échéance dans les 30 prochains jours.</p>
        @endforelse
    </div>

    {{-- Dernières transactions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-700 dark:text-gray-200">📋 Dernières transactions</h2>
            <a href="{{ route('admin.finances.transactions.index') }}" class="text-sm text-cyan-500 hover:underline">Voir tout</a>
        </div>
        @foreach($recentTransactions as $t)
        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
            <div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $t->label }}</p>
                <p class="text-xs text-gray-400">{{ $t->category->icon }} {{ $t->category->name }} · {{ $t->transaction_date->format('d/m/Y') }}</p>
            </div>
            <p class="font-semibold {{ $t->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                {{ $t->type === 'income' ? '+' : '-' }}{{ number_format($t->amount, 0, ',', ' ') }} XOF
            </p>
        </div>
        @endforeach
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart;

async function loadChart(year) {
    const res  = await fetch(`{{ route('admin.finances.api.chart') }}?year=${year}`);
    const data = await res.json();

    const labels   = data.map(d => d.month);
    const incomes  = data.map(d => d.income);
    const expenses = data.map(d => d.expense);
    const balances = data.map(d => d.balance);

    if (chart) chart.destroy();

    chart = new Chart(document.getElementById('financeChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: 'Revenus',  data: incomes,  backgroundColor: 'rgba(34,197,94,0.7)',  borderColor: '#22C55E', borderWidth: 1 },
                { label: 'Dépenses', data: expenses, backgroundColor: 'rgba(239,68,68,0.7)',  borderColor: '#EF4444', borderWidth: 1 },
                { label: 'Solde',    data: balances, type: 'line', borderColor: '#06B6D4', borderWidth: 2, pointBackgroundColor: '#06B6D4', fill: false },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('fr-FR') + ' XOF' } }
            }
        }
    });
}

loadChart({{ date('Y') }});
document.getElementById('chartYear').addEventListener('change', e => loadChart(e.target.value));
</script>
@endpush

@endsection
```

---

## PHASE 8 — Badge notification dans le layout admin

Dans le menu du layout admin (sidebar ou topbar), ajoute un badge pour les notifications non lues :

```blade
{{-- Dans le layout admin, dans le menu de navigation --}}
@php
    $financeUnreadCount = \App\Models\FinanceNotification::where('is_read', false)->count();
@endphp

<a href="{{ route('admin.finances.dashboard') }}" class="flex items-center gap-2 ...">
    💰 Finances
    @if($financeUnreadCount > 0)
    <span class="bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 font-bold">
        {{ $financeUnreadCount }}
    </span>
    @endif
</a>
```

---

## PHASE 9 — Middleware de protection (super admin uniquement)

Vérifier que le middleware `admin` existant dans le projet contrôle bien que seul le super admin a accès. Si ce n'est pas le cas, ajouter une vérification dans `FinanceController::__construct()` :

```php
public function __construct()
{
    $this->middleware(function ($request, $next) {
        // Adapter selon le système d'auth existant du projet
        if (!auth()->user()?->is_super_admin && !auth()->user()?->hasRole('super_admin')) {
            abort(403, 'Accès réservé au super administrateur.');
        }
        return $next($request);
    });
}
```

---

## PHASE 10 — Test et vérification

```bash
# 1. Vérifier qu'il n'y a pas d'erreurs
php artisan route:list | grep finances
php artisan view:clear && php artisan cache:clear

# 2. Tester le command manuellement
php artisan finance:check-reminders

# 3. Vérifier les logs
tail -20 storage/logs/laravel.log | grep -i "error\|exception"

# 4. Tester le scheduler en local
php artisan schedule:run
```

Accéder à `/admin/finances` et vérifier :
- ✅ Dashboard s'affiche sans erreur
- ✅ Graphique Chart.js se charge
- ✅ Formulaire création transaction fonctionne
- ✅ Catégories par défaut présentes
- ✅ Formulaire récurrent : les deux types de récurrence fonctionnent

---

## Résumé des fichiers créés/modifiés

| Fichier | Action |
|---|---|
| `database/migrations/*_create_finance_categories_table.php` | CRÉER |
| `database/migrations/*_create_finance_recurrings_table.php` | CRÉER |
| `database/migrations/*_create_finance_transactions_table.php` | CRÉER |
| `database/migrations/*_create_finance_notifications_table.php` | CRÉER |
| `database/seeders/FinanceCategorySeeder.php` | CRÉER |
| `app/Models/FinanceCategory.php` | CRÉER |
| `app/Models/FinanceRecurring.php` | CRÉER |
| `app/Models/FinanceTransaction.php` | CRÉER |
| `app/Models/FinanceNotification.php` | CRÉER |
| `app/Http/Controllers/Admin/FinanceController.php` | CRÉER |
| `app/Http/Controllers/Admin/FinanceTransactionController.php` | CRÉER |
| `app/Http/Controllers/Admin/FinanceRecurringController.php` | CRÉER |
| `app/Http/Controllers/Admin/FinanceNotificationController.php` | CRÉER |
| `app/Console/Commands/CheckFinanceReminders.php` | CRÉER |
| `app/Console/Kernel.php` | MODIFIER — ajouter schedule |
| `routes/web.php` (ou routes/admin.php) | MODIFIER — ajouter routes finances |
| `resources/views/admin/finances/**` | CRÉER (7 vues Blade) |
| Layout admin | MODIFIER — ajouter lien + badge notifications |

**Total : 18 nouveaux fichiers, 3 fichiers existants modifiés.**

---

---

## PHASE 11 — Fonctionnalités supplémentaires (inspirées de l'app de référence)

> Ces fonctionnalités s'ajoutent au dessus de tout ce qui précède. Implémenter dans l'ordre.

---

### 11.1 — Correction rôle admin

Dans **PHASE 9**, remplacer partout `is_super_admin` et `hasRole('super_admin')` par la vérification admin du projet existant. Inspecter d'abord comment le middleware `admin` est défini dans le projet :

```bash
grep -r "is_admin\|role.*admin\|admin.*role" app/Models/User.php app/Http/Middleware/
```

Utiliser la même logique que le reste du projet. Exemple probable :

```php
// Dans FinanceController::__construct()
$this->middleware(function ($request, $next) {
    if (!auth()->user()?->is_admin) {
        abort(403);
    }
    return $next($request);
});
```

---

### 11.2 — Multi-devise (XOF + EUR + USD)

**Pourquoi :** AdSense et Ezoic paient en EUR/USD. Il faut pouvoir saisir 45 EUR et voir l'équivalent en XOF dans les rapports.

#### Migration taux de change

```bash
php artisan make:migration create_finance_exchange_rates_table
```

```php
Schema::create('finance_exchange_rates', function (Blueprint $table) {
    $table->id();
    $table->string('currency_from', 3); // EUR | USD
    $table->string('currency_to', 3)->default('XOF');
    $table->decimal('rate', 12, 4);     // Ex: 655.957 pour EUR→XOF
    $table->date('rate_date');          // Date de ce taux
    $table->boolean('is_current')->default(true);
    $table->timestamps();
});
```

Taux par défaut à insérer (dans le seeder) :
- EUR → XOF : 655.957 (taux fixe FCFA/EUR)
- USD → XOF : ~600 (taux approximatif, mis à jour manuellement)

#### Contrôleur taux de change

```bash
php artisan make:controller Admin/FinanceExchangeRateController
```

```php
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceExchangeRate;
use Illuminate\Http\Request;

class FinanceExchangeRateController extends Controller
{
    public function index()
    {
        $rates = FinanceExchangeRate::where('is_current', true)->get();
        return view('admin.finances.exchange-rates.index', compact('rates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'currency_from' => 'required|in:EUR,USD',
            'rate'          => 'required|numeric|min:1',
        ]);

        // Désactiver l'ancien taux
        FinanceExchangeRate::where('currency_from', $data['currency_from'])
            ->where('is_current', true)
            ->update(['is_current' => false]);

        FinanceExchangeRate::create([
            'currency_from' => $data['currency_from'],
            'currency_to'   => 'XOF',
            'rate'          => $data['rate'],
            'rate_date'     => now()->toDateString(),
            'is_current'    => true,
        ]);

        return back()->with('success', 'Taux mis à jour.');
    }
}
```

#### Modèle FinanceExchangeRate

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceExchangeRate extends Model
{
    protected $fillable = ['currency_from', 'currency_to', 'rate', 'rate_date', 'is_current'];
    protected $casts = ['rate' => 'decimal:4', 'rate_date' => 'date', 'is_current' => 'boolean'];

    public static function getRate(string $from, string $to = 'XOF'): float
    {
        if ($from === $to) return 1.0;
        return (float) static::where('currency_from', $from)
            ->where('currency_to', $to)
            ->where('is_current', true)
            ->value('rate') ?? 1.0;
    }

    public static function convert(float $amount, string $from, string $to = 'XOF'): float
    {
        return round($amount * static::getRate($from, $to), 2);
    }
}
```

#### Mise à jour FinanceTransactionController::store()

Dans la méthode `store()`, calculer `amount_xof` automatiquement :

```php
// Après validation, avant FinanceTransaction::create()
use App\Models\FinanceExchangeRate;

$currency = $data['currency'] ?? 'XOF';
$data['currency']      = $currency;
$data['exchange_rate'] = FinanceExchangeRate::getRate($currency);
$data['amount_xof']    = FinanceExchangeRate::convert($data['amount'], $currency);
```

#### Formulaire de saisie — champ devise

Dans les vues `transactions/create.blade.php` et `transactions/edit.blade.php`, ajouter après le champ montant :

```blade
<div class="flex gap-2">
    <div class="flex-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">Montant</label>
        <input type="number" name="amount" step="1" min="1" required
               class="w-full border rounded-lg px-3 py-2 focus:ring-cyan-500"
               value="{{ old('amount', $transaction->amount ?? '') }}">
    </div>
    <div class="w-28">
        <label class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
        <select name="currency" class="w-full border rounded-lg px-3 py-2 focus:ring-cyan-500"
                id="currencySelect">
            <option value="XOF" {{ (old('currency', $transaction->currency ?? 'XOF') == 'XOF') ? 'selected' : '' }}>XOF</option>
            <option value="EUR" {{ (old('currency', $transaction->currency ?? '') == 'EUR') ? 'selected' : '' }}>EUR</option>
            <option value="USD" {{ (old('currency', $transaction->currency ?? '') == 'USD') ? 'selected' : '' }}>USD</option>
        </select>
    </div>
</div>
<p class="text-xs text-gray-400 mt-1" id="convertedAmount"></p>

<script>
document.getElementById('currencySelect').addEventListener('change', updateConversion);
document.querySelector('input[name="amount"]').addEventListener('input', updateConversion);

const rates = {
    XOF: 1,
    EUR: {{ \App\Models\FinanceExchangeRate::getRate('EUR') }},
    USD: {{ \App\Models\FinanceExchangeRate::getRate('USD') }},
};

function updateConversion() {
    const amount   = parseFloat(document.querySelector('input[name="amount"]').value) || 0;
    const currency = document.getElementById('currencySelect').value;
    if (currency !== 'XOF' && amount > 0) {
        const xof = Math.round(amount * rates[currency]);
        document.getElementById('convertedAmount').textContent =
            `≈ ${xof.toLocaleString('fr-FR')} XOF au taux actuel`;
    } else {
        document.getElementById('convertedAmount').textContent = '';
    }
}
</script>
```

---

### 11.3 — Quick Add (ajout rapide depuis le dashboard)

Ajouter un bouton "⚡ Ajout rapide" sur le dashboard qui ouvre un modal minimaliste.

#### Méthode `quickStore` dans FinanceTransactionController

```php
public function quickStore(Request $request)
{
    $data = $request->validate([
        'finance_category_id' => 'required|exists:finance_categories,id',
        'type'                => 'required|in:income,expense',
        'amount'              => 'required|numeric|min:1',
        'currency'            => 'required|in:XOF,EUR,USD',
        'label'               => 'required|string|max:255',
        'transaction_date'    => 'required|date',
    ]);

    $data['exchange_rate'] = FinanceExchangeRate::getRate($data['currency']);
    $data['amount_xof']    = FinanceExchangeRate::convert($data['amount'], $data['currency']);

    FinanceTransaction::create($data);
    return back()->with('success', '✅ Transaction ajoutée rapidement.');
}
```

#### Modal Quick Add (à ajouter dans dashboard.blade.php)

```blade
{{-- Bouton dans l'en-tête du dashboard --}}
<button onclick="document.getElementById('quickAddModal').classList.remove('hidden')"
        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
    ⚡ Ajout rapide
</button>

{{-- Modal --}}
<div id="quickAddModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg">⚡ Ajout rapide</h3>
            <button onclick="document.getElementById('quickAddModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.finances.transactions.quick-store') }}">
            @csrf

            {{-- Type : Revenu / Dépense --}}
            <div class="flex gap-2 mb-4">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="income" class="sr-only peer" checked>
                    <div class="text-center py-2 rounded-lg border-2 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 text-gray-500 transition">
                        ↑ Revenu
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="expense" class="sr-only peer">
                    <div class="text-center py-2 rounded-lg border-2 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 text-gray-500 transition">
                        ↓ Dépense
                    </div>
                </label>
            </div>

            {{-- Catégorie --}}
            <select name="finance_category_id" required
                    class="w-full border rounded-lg px-3 py-2 mb-3 focus:ring-cyan-500">
                <option value="">— Catégorie —</option>
                @foreach(\App\Models\FinanceCategory::where('is_active', true)->get()->groupBy('type') as $type => $cats)
                <optgroup label="{{ $type === 'income' ? '↑ Revenus' : '↓ Dépenses' }}">
                    @foreach($cats as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>

            {{-- Libellé --}}
            <input type="text" name="label" placeholder="Libellé" required
                   class="w-full border rounded-lg px-3 py-2 mb-3 focus:ring-cyan-500">

            {{-- Montant + Devise --}}
            <div class="flex gap-2 mb-3">
                <input type="number" name="amount" placeholder="Montant" min="1" required
                       class="flex-1 border rounded-lg px-3 py-2 focus:ring-cyan-500">
                <select name="currency" class="w-24 border rounded-lg px-2 py-2 focus:ring-cyan-500">
                    <option value="XOF">XOF</option>
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                </select>
            </div>

            {{-- Date --}}
            <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required
                   class="w-full border rounded-lg px-3 py-2 mb-4 focus:ring-cyan-500">

            <button type="submit"
                    class="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-2 rounded-lg font-medium">
                Ajouter la transaction
            </button>
        </form>
    </div>
</div>
```

---

### 11.4 — Donut chart par catégorie

Ajouter dans `FinanceController` la méthode pour les données du donut :

```php
public function donutData(Request $request)
{
    $month = $request->get('month', now()->month);
    $year  = $request->get('year', now()->year);
    $type  = $request->get('type', 'expense'); // 'expense' ou 'income'

    $data = FinanceCategory::where('type', $type)->get()->map(function ($cat) use ($month, $year) {
        $amount = $cat->transactions()
            ->whereYear('transaction_date', $year)
            ->whereMonth('transaction_date', $month)
            ->sum('amount_xof'); // Utiliser amount_xof pour tout en XOF

        return [
            'name'   => $cat->name,
            'icon'   => $cat->icon,
            'color'  => $cat->color,
            'amount' => (float) $amount,
        ];
    })->filter(fn($c) => $c['amount'] > 0)->values();

    return response()->json($data);
}
```

Ajouter dans `dashboard.blade.php`, après le bar chart :

```blade
{{-- Donut charts --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    {{-- Dépenses par catégorie --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">🔴 Dépenses par catégorie</h2>
        <canvas id="donutExpense" height="200"></canvas>
        <div id="donutExpenseLegend" class="mt-3 space-y-1 text-sm"></div>
    </div>
    {{-- Revenus par catégorie --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">🟢 Revenus par catégorie</h2>
        <canvas id="donutIncome" height="200"></canvas>
        <div id="donutIncomeLegend" class="mt-3 space-y-1 text-sm"></div>
    </div>
</div>

{{-- Ajouter dans le bloc @push('scripts') --}}
async function loadDonut(canvasId, legendId, type) {
    const url = `{{ route('admin.finances.api.donut') }}?type=${type}&month={{ now()->month }}&year={{ now()->year }}`;
    const data = await fetch(url).then(r => r.json());

    if (!data.length) return;

    const existing = Chart.getChart(canvasId);
    if (existing) existing.destroy();

    new Chart(document.getElementById(canvasId), {
        type: 'doughnut',
        data: {
            labels: data.map(d => d.icon + ' ' + d.name),
            datasets: [{
                data: data.map(d => d.amount),
                backgroundColor: data.map(d => d.color),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed.toLocaleString('fr-FR')} XOF` } }
            }
        }
    });

    const legend = document.getElementById(legendId);
    legend.innerHTML = data.map(d =>
        `<div class="flex justify-between">
            <span style="color:${d.color}">${d.icon} ${d.name}</span>
            <span class="font-medium">${d.amount.toLocaleString('fr-FR')} XOF</span>
        </div>`
    ).join('');
}

loadDonut('donutExpense', 'donutExpenseLegend', 'expense');
loadDonut('donutIncome', 'donutIncomeLegend', 'income');
```

---

### 11.5 — Rapports par période personnalisable

Créer la vue `resources/views/admin/finances/reports.blade.php` et la méthode `reports()` dans `FinanceController` :

```php
public function reports(Request $request)
{
    $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
    $endDate   = $request->get('end_date', now()->toDateString());
    $sortBy    = $request->get('sort', 'date_desc'); // date_desc | date_asc | amount_desc | amount_asc

    $query = FinanceTransaction::with('category')
        ->whereBetween('transaction_date', [$startDate, $endDate]);

    match ($sortBy) {
        'date_asc'    => $query->orderBy('transaction_date', 'asc'),
        'amount_desc' => $query->orderBy('amount_xof', 'desc'),
        'amount_asc'  => $query->orderBy('amount_xof', 'asc'),
        default       => $query->orderBy('transaction_date', 'desc'),
    };

    $transactions = $query->paginate(50)->withQueryString();

    $totalIncome  = $query->clone()->where('type', 'income')->sum('amount_xof');
    $totalExpense = $query->clone()->where('type', 'expense')->sum('amount_xof');

    // Répartition par catégorie sur la période
    $byCategory = FinanceCategory::with(['transactions' => function ($q) use ($startDate, $endDate) {
        $q->whereBetween('transaction_date', [$startDate, $endDate]);
    }])->get()->map(fn($cat) => [
        'name'   => $cat->name,
        'icon'   => $cat->icon,
        'color'  => $cat->color,
        'type'   => $cat->type,
        'amount' => $cat->transactions->sum('amount_xof'),
    ])->filter(fn($c) => $c['amount'] > 0)->sortByDesc('amount')->values();

    return view('admin.finances.reports', compact(
        'transactions', 'startDate', 'endDate', 'sortBy',
        'totalIncome', 'totalExpense', 'byCategory'
    ));
}
```

Dans la vue `reports.blade.php`, inclure :
- Formulaire avec `start_date`, `end_date`, `sort` (par date/montant) → submit GET
- KPIs de la période (total revenus, dépenses, solde)
- Tableau des transactions avec colonnes : Date | Catégorie | Libellé | Montant | Devise | ≈XOF
- Bouton export CSV (réutiliser la méthode `export()` avec les paramètres de période)
- Tableau de répartition par catégorie

---

### 11.6 — Tri et filtres avancés sur la liste des transactions

Dans `FinanceTransactionController::index()`, étendre le tri :

```php
// Remplacer ->orderByDesc('transaction_date') par :
$sort = $request->get('sort', 'date_desc');
match ($sort) {
    'date_asc'    => $query->orderBy('transaction_date', 'asc'),
    'amount_desc' => $query->orderByDesc('amount_xof'),
    'amount_asc'  => $query->orderBy('amount_xof', 'asc'),
    default       => $query->orderByDesc('transaction_date'),
};
```

Dans la vue `transactions/index.blade.php`, ajouter les contrôles de tri en haut du tableau :

```blade
<div class="flex gap-2 items-center mb-3 text-sm">
    <span class="text-gray-500">Trier par :</span>
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_desc']) }}"
       class="{{ request('sort', 'date_desc') == 'date_desc' ? 'font-bold text-cyan-600' : 'text-gray-500 hover:text-cyan-500' }}">
       Date ↓
    </a>
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_asc']) }}"
       class="{{ request('sort') == 'date_asc' ? 'font-bold text-cyan-600' : 'text-gray-500 hover:text-cyan-500' }}">
       Date ↑
    </a>
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount_desc']) }}"
       class="{{ request('sort') == 'amount_desc' ? 'font-bold text-cyan-600' : 'text-gray-500 hover:text-cyan-500' }}">
       Montant ↓
    </a>
    <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount_asc']) }}"
       class="{{ request('sort') == 'amount_asc' ? 'font-bold text-cyan-600' : 'text-gray-500 hover:text-cyan-500' }}">
       Montant ↑
    </a>
</div>
```

---

### 11.7 — Templates de saisie rapide (depuis les récurrents)

Sur la page `/admin/finances/recurring/index`, ajouter un bouton "✚ Enregistrer maintenant" sur chaque récurrent actif. Ce bouton pré-remplit le formulaire Quick Add avec les données du récurrent.

Dans la vue `recurring/index.blade.php` :

```blade
@foreach($recurrings as $r)
<tr>
    {{-- ... autres colonnes ... --}}
    <td>
        <button onclick="quickAddFromTemplate({{ $r->id }}, '{{ addslashes($r->label) }}', {{ $r->amount }}, '{{ $r->type }}', {{ $r->finance_category_id }})"
                class="text-xs bg-cyan-50 text-cyan-600 hover:bg-cyan-100 px-2 py-1 rounded">
            ✚ Enregistrer
        </button>
    </td>
</tr>
@endforeach

<script>
function quickAddFromTemplate(id, label, amount, type, categoryId) {
    // Ouvrir le modal Quick Add et pré-remplir
    document.getElementById('quickAddModal').classList.remove('hidden');
    document.querySelector(`input[name="type"][value="${type}"]`).checked = true;
    document.querySelector('select[name="finance_category_id"]').value = categoryId;
    document.querySelector('input[name="label"]').value = label;
    document.querySelector('input[name="amount"]').value = amount;
    document.querySelector('input[name="transaction_date"]').value = new Date().toISOString().split('T')[0];
}
</script>
```

---

### 11.8 — Fichiers supplémentaires à créer

| Fichier | Action |
|---|---|
| `database/migrations/*_create_finance_exchange_rates_table.php` | CRÉER |
| `database/migrations/*_add_currency_to_finance_transactions.php` | CRÉER (si la table existe déjà) |
| `app/Models/FinanceExchangeRate.php` | CRÉER |
| `app/Http/Controllers/Admin/FinanceExchangeRateController.php` | CRÉER |
| `resources/views/admin/finances/exchange-rates/index.blade.php` | CRÉER |
| `resources/views/admin/finances/reports.blade.php` | CRÉER |
| `FinanceController` — ajouter `donutData()` et `reports()` | MODIFIER |
| `FinanceTransactionController` — ajouter `quickStore()` et tri avancé | MODIFIER |
| `FinanceRecurring/index.blade.php` — ajouter bouton template | MODIFIER |
| `dashboard.blade.php` — ajouter modal Quick Add + donut charts | MODIFIER |

---

## Récapitulatif complet de toutes les fonctionnalités

| Fonctionnalité | Source |
|---|---|
| Transactions manuelles (revenu/dépense) | Exigence utilisateur |
| Récurrents (jour du mois OU toutes les N jours) | Exigence utilisateur |
| Notifications dashboard avec badge | Exigence utilisateur |
| Délai de rappel configurable | Exigence utilisateur |
| Budget mensuel par catégorie + alerte | Exigence utilisateur |
| Graphique annuel bar chart | Exigence utilisateur |
| Export CSV | Exigence utilisateur |
| Accès admin uniquement | Exigence utilisateur |
| **Multi-devise XOF + EUR + USD** | App de référence |
| **Ajout rapide (Quick Add modal)** | App de référence |
| **Donut charts par catégorie** | App de référence |
| **Rapports par période personnalisable** | App de référence |
| **Tri par date et par montant** | App de référence |
| **Templates depuis les récurrents** | App de référence |
| **Taux de change configurables manuellement** | App de référence |

---

*Généré le 5 août 2026 — Module Finances NiangProgrammeur.com*
