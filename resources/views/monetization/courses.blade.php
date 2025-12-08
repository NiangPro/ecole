@extends('layouts.app')

@section('title', 'Cours Payants - NiangProgrammeur')

@section('content')
<div style="min-height: 100vh; padding: 40px 20px; background: linear-gradient(to bottom right, #0f172a, #1e293b, #0f172a);">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Hero Section -->
        <div style="text-align: center; margin-bottom: 60px; padding: 60px 20px;">
            <h1 style="font-size: 3rem; font-weight: 800; color: white; margin-bottom: 20px; text-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);">
                <i class="fas fa-graduation-cap" style="color: #06b6d4; margin-right: 15px;"></i>
                Cours Payants
            </h1>
            <p style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.8); max-width: 700px; margin: 0 auto;">
                Accédez à des cours premium approfondis et développez vos compétences
            </p>
        </div>

        <!-- Liste des Cours -->
        @if($courses->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; margin-bottom: 40px;">
            @foreach($courses as $course)
            <a href="{{ route('monetization.course.show', $course->slug) }}" style="text-decoration: none; display: block;">
                <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 20px; overflow: hidden; transition: all 0.3s ease; position: relative;">
                    @if($course->hasDiscount())
                    <div style="position: absolute; top: 15px; right: 15px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 8px 15px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; z-index: 10; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);">
                        -{{ $course->discount_percentage }}%
                    </div>
                    @endif

                    @if($course->image)
                    <div style="width: 100%; height: 200px; background-image: url('{{ $course->image }}'); background-size: cover; background-position: center; position: relative;">
                        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent, rgba(15, 23, 42, 0.8));"></div>
                    </div>
                    @else
                    <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #06b6d4, #14b8a6); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book" style="font-size: 4rem; color: white; opacity: 0.5;"></i>
                    </div>
                    @endif
                    
                    <div style="padding: 25px;">
                        <h3 style="font-size: 1.35rem; font-weight: 700; color: white; margin-bottom: 12px; line-height: 1.4;">
                            {{ $course->title }}
                        </h3>
                        
                        @if($course->description)
                        <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.95rem; margin-bottom: 20px; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $course->description }}
                        </p>
                        @endif

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-top: 15px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                @if($course->duration_hours)
                                <div style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                                    <i class="fas fa-clock" style="margin-right: 6px; color: #06b6d4;"></i>
                                    {{ $course->duration_hours }}h
                                </div>
                                @endif
                                @if($course->students_count > 0)
                                <div style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                                    <i class="fas fa-users" style="margin-right: 6px; color: #10b981;"></i>
                                    {{ $course->students_count }}
                                </div>
                                @endif
                            </div>
                            @if($course->rating > 0)
                            <div style="display: flex; align-items: center; color: #fbbf24;">
                                <i class="fas fa-star" style="margin-right: 4px;"></i>
                                <span style="font-weight: 600;">{{ number_format($course->rating, 1) }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                @if($course->hasDiscount())
                                <div>
                                    <span style="font-size: 1.75rem; font-weight: 800; color: #06b6d4;">{{ number_format($course->discount_price, 0, ',', ' ') }} FCFA</span>
                                    <span style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.5); text-decoration: line-through; margin-left: 8px;">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                </div>
                                @else
                                <span style="font-size: 1.75rem; font-weight: 800; color: #06b6d4;">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                @endif
                            </div>
                            <div style="padding: 10px 20px; background: rgba(6, 182, 212, 0.2); border: 1px solid #06b6d4; border-radius: 10px; color: #06b6d4; font-weight: 600; transition: all 0.3s ease;">
                                Voir <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: center; margin-top: 50px;">
            {{ $courses->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 80px 20px; background: rgba(30, 41, 59, 0.6); border-radius: 20px; border: 1px solid rgba(6, 182, 212, 0.3);">
            <i class="fas fa-book" style="font-size: 4rem; color: rgba(6, 182, 212, 0.5); margin-bottom: 20px;"></i>
            <h3 style="font-size: 1.75rem; font-weight: 700; color: white; margin-bottom: 10px;">
                Aucun cours disponible
            </h3>
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                De nouveaux cours seront bientôt disponibles
            </p>
        </div>
        @endif

    </div>
</div>

<style>
    @media (max-width: 768px) {
        h1 { font-size: 2rem !important; }
        .courses-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endsection



