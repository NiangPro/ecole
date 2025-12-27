@extends('admin.layout')

@section('title', 'Statistiques Documents')

@section('styles')
<style>
    .statistics-page {
        padding: 2rem;
    }

    .stats-header {
        margin-bottom: 2rem;
    }

    .stats-header h1 {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(6, 182, 212, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.2);
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-card-title {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(6, 182, 212, 0.1);
        border-radius: 12px;
        color: #06b6d4;
        font-size: 1.5rem;
    }

    .stat-card-value {
        font-size: 2rem;
        font-weight: 900;
        color: white;
        margin-bottom: 0.25rem;
    }

    .stat-card-label {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.6);
    }

    .chart-section {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(6, 182, 212, 0.2);
        margin-bottom: 2rem;
    }

    .chart-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .chart-title i {
        color: #06b6d4;
    }

    .filters-section {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(6, 182, 212, 0.2);
        margin-bottom: 2rem;
    }

    .filters-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
    }

    .filter-group select,
    .filter-group input {
        padding: 0.75rem 1rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 8px;
        color: white;
        font-size: 0.9375rem;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
    }

    .btn-filter {
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(6, 182, 212, 0.4);
    }

    .table-section {
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid rgba(6, 182, 212, 0.2);
        overflow-x: auto;
    }

    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }

    .table-modern thead {
        background: rgba(6, 182, 212, 0.1);
    }

    .table-modern th {
        padding: 1rem;
        text-align: left;
        color: #06b6d4;
        font-weight: 700;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }

    .table-modern td {
        padding: 1rem;
        border-bottom: 1px solid rgba(6, 182, 212, 0.1);
        color: rgba(255, 255, 255, 0.8);
    }

    .table-modern tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-success {
        background: rgba(34, 197, 94, 0.2);
        color: #22c55e;
    }

    .badge-warning {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }
    
    /* Dark Mode Styles */
    body.light-mode .statistics-page {
        background: #f8f9fa;
    }
    
    body.light-mode .stats-header h1 {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    body.light-mode .stats-header p {
        color: #64748b;
    }
    
    body.light-mode .stat-card {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    body.light-mode .stat-card:hover {
        box-shadow: 0 8px 30px rgba(6, 182, 212, 0.2);
    }
    
    body.light-mode .stat-card-title {
        color: #64748b;
    }
    
    body.light-mode .stat-card-value {
        color: #1e293b;
    }
    
    body.light-mode .stat-card-label {
        color: #64748b;
    }
    
    body.light-mode .chart-section,
    body.light-mode .filters-section,
    body.light-mode .table-section {
        background: #ffffff;
        border-color: rgba(6, 182, 212, 0.3);
    }
    
    body.light-mode .chart-title {
        color: #1e293b;
    }
    
    body.light-mode .filter-group label {
        color: #64748b;
    }
    
    body.light-mode .filter-group select,
    body.light-mode .filter-group input {
        background: #f8f9fa;
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }
    
    body.light-mode .filter-group select:focus,
    body.light-mode .filter-group input:focus {
        background: #ffffff;
        border-color: #06b6d4;
    }
    
    body.light-mode .table-modern th {
        color: #06b6d4;
    }
    
    body.light-mode .table-modern td {
        color: #1e293b;
    }
    
    body.light-mode .table-modern tbody tr:hover {
        background: rgba(6, 182, 212, 0.05);
    }
</style>
@endsection

@section('content')
<div class="statistics-page">
    <div class="stats-header">
        <h1><i class="fas fa-chart-bar"></i> Statistiques Documents</h1>
        <p style="color: rgba(255, 255, 255, 0.7);">Analysez les performances et les ventes de vos documents</p>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.documents.statistics') }}" class="filters-form">
            <div class="filter-group">
                <label>Période</label>
                <select name="filter" onchange="this.form.submit()">
                    <option value="day" {{ $filter === 'day' ? 'selected' : '' }}>Jour</option>
                    <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>Mois</option>
                    <option value="year" {{ $filter === 'year' ? 'selected' : '' }}>Année</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Année</label>
                <select name="year" onchange="this.form.submit()">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            @if($filter === 'month' || $filter === 'day')
            <div class="filter-group">
                <label>Mois</label>
                <select name="month" onchange="this.form.submit()">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ Carbon\Carbon::create(null, $m)->locale('fr')->monthName }}</option>
                    @endfor
                </select>
            </div>
            @endif
            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filtrer
            </button>
        </form>
    </div>

    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Revenus Totaux</span>
                <div class="stat-card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} FCFA</div>
            <div class="stat-card-label">Tous les achats complétés</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Documents Vendus</span>
                <div class="stat-card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['total_sales'], 0, ',', ' ') }}</div>
            <div class="stat-card-label">Achats complétés</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Documents Publiés</span>
                <div class="stat-card-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['published_documents'], 0, ',', ' ') }}</div>
            <div class="stat-card-label">Sur {{ $stats['total_documents'] }} documents</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Téléchargements</span>
                <div class="stat-card-icon">
                    <i class="fas fa-download"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['total_downloads'], 0, ',', ' ') }}</div>
            <div class="stat-card-label">Total des téléchargements</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Vues Total</span>
                <div class="stat-card-icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['total_views'], 0, ',', ' ') }}</div>
            <div class="stat-card-label">Vues de documents</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Taux de Conversion</span>
                <div class="stat-card-icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ $conversionRate }}%</div>
            <div class="stat-card-label">Vues → Achats</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Achats en Attente</span>
                <div class="stat-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($stats['pending_purchases'], 0, ',', ' ') }}</div>
            <div class="stat-card-label">En attente de validation</div>
        </div>
    </div>

    <!-- Statistiques de Téléchargements -->
    <div class="stats-grid" style="margin-top: 2rem;">
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Téléchargements Aujourd'hui</span>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($downloadsStats['today'], 0, ',', ' ') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Cette Semaine</span>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($downloadsStats['this_week'], 0, ',', ' ') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-title">Ce Mois</span>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-card-value">{{ number_format($downloadsStats['this_month'], 0, ',', ' ') }}</div>
        </div>
    </div>

    <!-- Top 10 Documents -->
    <div class="table-section">
        <h2 class="chart-title">
            <i class="fas fa-trophy"></i>
            Top 10 Documents les Plus Vendus
        </h2>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Document</th>
                    <th>Catégorie</th>
                    <th>Ventes</th>
                    <th>Revenus</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topDocuments as $index => $document)
                <tr>
                    <td>
                        <span class="badge {{ $index < 3 ? 'badge-success' : 'badge-warning' }}">
                            #{{ $index + 1 }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $document->title }}</strong>
                    </td>
                    <td>{{ $document->category->name }}</td>
                    <td>{{ $document->purchases_count }}</td>
                    <td>
                        @php
                            $revenue = $document->purchases()->where('status', 'completed')->sum('amount_paid');
                        @endphp
                        {{ number_format($revenue, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: rgba(255, 255, 255, 0.6);">
                        Aucun document vendu pour le moment
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Revenus par Catégorie -->
    @if($revenueByCategory->count() > 0)
    <div class="table-section">
        <h2 class="chart-title">
            <i class="fas fa-folder"></i>
            Revenus par Catégorie
        </h2>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Catégorie</th>
                    <th>Ventes</th>
                    <th>Revenus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueByCategory as $index => $category)
                <tr>
                    <td>
                        <span class="badge {{ $index < 3 ? 'badge-success' : 'badge-warning' }}">
                            #{{ $index + 1 }}
                        </span>
                    </td>
                    <td><strong>{{ $category['name'] }}</strong></td>
                    <td>{{ $category['sales'] }}</td>
                    <td><strong>{{ number_format($category['revenue'], 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Graphique Revenus par Mois -->
    @if($revenueByMonth->count() > 0)
    <div class="chart-section">
        <h2 class="chart-title">
            <i class="fas fa-chart-line"></i>
            Évolution des Revenus (12 derniers mois)
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
            @foreach($revenueByMonth as $monthData)
            <div style="text-align: center; padding: 1rem; background: rgba(6, 182, 212, 0.05); border-radius: 12px; border: 1px solid rgba(6, 182, 212, 0.2);">
                <div style="font-size: 1.25rem; font-weight: 800; color: #06b6d4; margin-bottom: 0.5rem;">
                    {{ number_format($monthData->total, 0, ',', ' ') }} FCFA
                </div>
                <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.875rem; margin-bottom: 0.25rem;">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $monthData->month)->locale('fr')->format('M Y') }}
                </div>
                <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.75rem;">
                    {{ $monthData->count }} vente(s)
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

