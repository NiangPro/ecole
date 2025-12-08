@extends('admin.layout')

@section('title', 'Gestion des Cours Payants - Admin')

@section('content')
<div style="padding: 2rem;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 0.5rem;">
                <i class="fas fa-graduation-cap" style="color: #06b6d4; margin-right: 15px;"></i>
                Gestion des Cours Payants
            </h1>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Gérez tous les cours payants de la plateforme
            </p>
        </div>
        <a href="{{ route('admin.monetization.dashboard') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
            <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
            Retour au dashboard
        </a>
    </div>

    @if($courses->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px;">
        @foreach($courses as $course)
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px; position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <h3 style="font-size: 1.35rem; font-weight: 700; color: white; margin-bottom: 10px; flex: 1;">
                    {{ $course->title }}
                </h3>
                <span style="padding: 4px 10px; background: {{ $course->status === 'published' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(107, 114, 128, 0.2)' }}; border: 1px solid {{ $course->status === 'published' ? '#10b981' : '#6b7280' }}; border-radius: 6px; color: {{ $course->status === 'published' ? '#10b981' : '#6b7280' }}; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                    {{ $course->status }}
                </span>
            </div>

            @if($course->description)
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.95rem; margin-bottom: 20px; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $course->description }}
            </p>
            @endif

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 20px; padding-top: 20px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">Prix</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #06b6d4;">
                        {{ number_format($course->price, 0, ',', ' ') }} FCFA
                    </div>
                </div>
                <div>
                    <div style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-bottom: 5px;">Ventes</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: #10b981;">
                        {{ $course->purchases_count }}
                    </div>
                </div>
            </div>

            @if($course->hasDiscount())
            <div style="padding: 10px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">Réduction active</span>
                    <span style="color: #ef4444; font-weight: 700;">-{{ $course->discount_percentage }}%</span>
                </div>
            </div>
            @endif

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="{{ route('monetization.course.show', $course->slug) }}" target="_blank" style="flex: 1; padding: 10px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 1px solid #06b6d4; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 600; font-size: 0.9rem; transition: all 0.3s ease;">
                    <i class="fas fa-eye" style="margin-right: 6px;"></i>
                    Voir
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $courses->links() }}
    </div>
    @else
    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 60px; text-align: center;">
        <i class="fas fa-graduation-cap" style="font-size: 4rem; color: rgba(6, 182, 212, 0.5); margin-bottom: 20px;"></i>
        <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 10px;">
            Aucun cours payant
        </h3>
        <p style="color: rgba(255, 255, 255, 0.7);">
            Aucun cours payant n'a été créé pour le moment.
        </p>
    </div>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        .courses-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endsection



