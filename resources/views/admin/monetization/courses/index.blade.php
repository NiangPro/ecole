@extends('admin.layout')

@section('title', 'Gestion des Cours Payants - Admin')

@push('styles')
<style>
    /* Adaptation Dark Mode */
    body.light-mode .courses-page {
        background: linear-gradient(to bottom right, rgba(248, 250, 252, 1), rgba(241, 245, 249, 1));
    }

    body.light-mode .courses-page h1,
    body.light-mode .courses-page h3,
    body.light-mode .courses-page .page-title {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .courses-page p,
    body.light-mode .courses-page .page-subtitle {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    body.light-mode .courses-page label,
    body.light-mode .courses-page .filter-label {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .courses-page .stat-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .courses-page .stat-card .stat-value {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .courses-page .stat-card .stat-label {
        color: rgba(30, 41, 59, 0.6) !important;
    }

    body.light-mode .courses-page .stat-card .stat-amount {
        color: rgba(30, 41, 59, 0.8) !important;
    }

    body.light-mode .courses-page table th,
    body.light-mode .courses-page table td,
    body.light-mode .courses-page .table-cell {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .courses-page table thead tr {
        border-bottom-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .courses-page table tbody tr {
        border-bottom-color: rgba(6, 182, 212, 0.1) !important;
    }

    body.light-mode .courses-page .status-badge.status-published {
        background: rgba(16, 185, 129, 0.1) !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
        color: rgba(5, 150, 105, 0.9) !important;
    }

    body.light-mode .courses-page .status-badge.status-draft {
        background: rgba(251, 191, 36, 0.1) !important;
        border-color: rgba(251, 191, 36, 0.3) !important;
        color: rgba(217, 119, 6, 0.9) !important;
    }

    body.light-mode .courses-page .status-badge.status-archived {
        background: rgba(107, 114, 128, 0.1) !important;
        border-color: rgba(107, 114, 128, 0.3) !important;
        color: rgba(71, 85, 105, 0.9) !important;
    }

    body.light-mode .courses-page .filter-card,
    body.light-mode .courses-page .table-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .courses-page input,
    body.light-mode .courses-page select,
    body.light-mode .courses-page .filter-input,
    body.light-mode .courses-page .filter-select {
        background: rgba(255, 255, 255, 0.95) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    body.light-mode .courses-page input::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }

    body.light-mode .courses-page select option {
        background: white !important;
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .courses-page .empty-state {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }

    body.light-mode .courses-page .empty-state h3 {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    body.light-mode .courses-page .empty-state p {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    body.light-mode .courses-page .alert-success {
        background: rgba(16, 185, 129, 0.1) !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
        color: rgba(5, 150, 105, 0.9) !important;
    }

    body.light-mode .courses-page .alert-error {
        background: rgba(239, 68, 68, 0.1) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
        color: rgba(220, 38, 38, 0.9) !important;
    }
</style>
@endpush

@section('content')
<div class="courses-page" style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 class="page-title" style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-graduation-cap" style="color: #06b6d4; margin-right: 15px;"></i>
                Gestion des Cours Payants
            </h1>
            <p class="page-subtitle" style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Gérez tous les cours payants de la plateforme
            </p>
        </div>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="{{ route('admin.monetization.courses.create') }}" style="padding: 12px 24px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                <i class="fas fa-plus" style="margin-right: 8px;"></i>
                Nouveau Cours
            </a>
            <a href="{{ route('admin.monetization.courses.export', request()->all()) }}" style="padding: 12px 24px; background: rgba(16, 185, 129, 0.2); color: #10b981; border: 2px solid #10b981; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-download" style="margin-right: 8px;"></i>
                Exporter CSV
            </a>
            <a href="{{ route('admin.monetization.dashboard') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                Retour
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert-success" style="padding: 15px 20px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 12px; color: #10b981; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error" style="padding: 15px 20px; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 12px; color: #ef4444; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Statistiques Rapides -->
    <div class="stats-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Total Cours</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #06b6d4;">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Publiés</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #10b981;">{{ $stats['published'] ?? 0 }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2)); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Brouillons</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #fbbf24;">{{ $stats['draft'] ?? 0 }}</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(124, 58, 237, 0.2)); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 16px; padding: 20px;">
            <div class="stat-label" style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 8px;">Revenus Totaux</div>
            <div class="stat-value" style="font-size: 1.75rem; font-weight: 800; color: #8b5cf6;">
                {{ number_format($stats['total_revenue'] ?? 0, 0, ',', ' ') }} FCFA
            </div>
            <div class="stat-amount" style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.6); margin-top: 5px;">
                {{ $stats['total_sales'] ?? 0 }} ventes
            </div>
        </div>
    </div>

    <!-- Filtres et Actions en Masse -->
    <div class="filter-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px; margin-bottom: 30px;">
        <form method="GET" action="{{ route('admin.monetization.courses.index') }}" id="filterForm" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, description..." class="filter-input" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
            </div>
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Statut</label>
                <select name="status" class="filter-select" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                    <option value="">Tous</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publiés</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillons</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archivés</option>
                </select>
            </div>
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Prix Min</label>
                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="0" class="filter-input" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
            </div>
            <div>
                <label class="filter-label" style="display: block; color: white; font-weight: 600; margin-bottom: 8px; font-size: 0.9rem;">Prix Max</label>
                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="1000000" class="filter-input" style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="flex: 1; padding: 10px 20px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                    <i class="fas fa-search" style="margin-right: 6px;"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.monetization.courses.index') }}" style="padding: 10px 20px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border: 1px solid #6b7280; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>

        <!-- Actions en Masse -->
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
            <form method="POST" action="{{ route('admin.monetization.courses.bulk-action') }}" id="bulkActionForm" onsubmit="return confirm('Êtes-vous sûr de vouloir effectuer cette action sur les cours sélectionnés ?');">
                @csrf
                <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                    <select name="action" required style="padding: 10px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 0.95rem;">
                        <option value="">Action en masse...</option>
                        <option value="publish">Publier</option>
                        <option value="archive">Archiver</option>
                        <option value="delete">Supprimer</option>
                    </select>
                    <button type="submit" style="padding: 10px 20px; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border: 2px solid #8b5cf6; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                        <i class="fas fa-check-double" style="margin-right: 6px;"></i>
                        Appliquer
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($courses->count() > 0)
    <div class="table-card" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(6, 182, 212, 0.3);">
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this);" style="margin-right: 8px;">
                            Titre
                        </th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Prix</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Statut</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Ventes</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Date</th>
                        <th style="padding: 15px; text-align: left; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                        <td class="table-cell" style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-weight: 600;">
                            <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" form="bulkActionForm" style="margin-right: 8px;">
                            <a href="{{ route('admin.monetization.courses.show', $course->id) }}" style="color: #06b6d4; text-decoration: none; font-weight: 600;">
                                {{ $course->title }}
                            </a>
                        </td>
                        <td class="table-cell" style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            <div style="font-weight: 700; color: #10b981; font-size: 1.1rem;">
                                {{ number_format($course->price, 0, ',', ' ') }} {{ $course->currency }}
                            </div>
                            @if($course->hasDiscount())
                            <div style="font-size: 0.85rem; color: #ef4444; margin-top: 4px;">
                                <span style="text-decoration: line-through; color: rgba(255, 255, 255, 0.5);">
                                    {{ number_format($course->price, 0, ',', ' ') }}
                                </span>
                                <span style="margin-left: 8px;">-{{ $course->discount_percentage }}%</span>
                            </div>
                            @endif
                        </td>
                        <td class="table-cell" style="padding: 15px;">
                            @if($course->status === 'published')
                                <span class="status-badge status-published" style="padding: 4px 10px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 6px; color: #10b981; font-size: 0.85rem; font-weight: 600;">Publié</span>
                            @elseif($course->status === 'draft')
                                <span class="status-badge status-draft" style="padding: 4px 10px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 6px; color: #fbbf24; font-size: 0.85rem; font-weight: 600;">Brouillon</span>
                            @else
                                <span class="status-badge status-archived" style="padding: 4px 10px; background: rgba(107, 114, 128, 0.2); border: 1px solid #6b7280; border-radius: 6px; color: #6b7280; font-size: 0.85rem; font-weight: 600;">Archivé</span>
                            @endif
                        </td>
                        <td class="table-cell" style="padding: 15px; color: rgba(255, 255, 255, 0.8); font-weight: 600;">
                            {{ $course->purchases_count ?? 0 }}
                        </td>
                        <td class="table-cell" style="padding: 15px; color: rgba(255, 255, 255, 0.8);">
                            {{ $course->created_at->format('d/m/Y') }}
                        </td>
                        <td class="table-cell" style="padding: 15px;">
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                <a href="{{ route('admin.monetization.courses.show', $course->id) }}" style="padding: 6px 12px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 1px solid #06b6d4; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.3s ease;" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.monetization.courses.edit', $course->id) }}" style="padding: 6px 12px; background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid #fbbf24; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: all 0.3s ease;" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.monetization.courses.duplicate', $course->id) }}" style="display: inline;" onsubmit="return confirm('Dupliquer ce cours ?');">
                                    @csrf
                                    <button type="submit" style="padding: 6px 12px; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border: 1px solid #8b5cf6; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: all 0.3s ease;" title="Dupliquer">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.monetization.courses.destroy', $course->id) }}" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid #ef4444; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: all 0.3s ease;" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $courses->links() }}
    </div>
    @else
    <div class="empty-state" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 60px; text-align: center;">
        <i class="fas fa-graduation-cap" style="font-size: 4rem; color: rgba(6, 182, 212, 0.5); margin-bottom: 20px;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
            Aucun cours payant
        </h3>
        <p style="color: rgba(255, 255, 255, 0.7); margin-bottom: 25px;">
            Aucun cours payant ne correspond à vos critères de recherche.
        </p>
        <a href="{{ route('admin.monetization.courses.create') }}" style="padding: 12px 24px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-plus" style="margin-right: 8px;"></i>
            Créer un nouveau cours
        </a>
    </div>
    @endif
</div>

<script>
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('input[name="course_ids[]"]');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}
</script>
@endsection

