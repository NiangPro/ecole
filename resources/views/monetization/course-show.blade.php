@extends('layouts.app')

@section('title', $course->title . ' - NiangProgrammeur')

@section('content')
<div style="min-height: 100vh; padding: 40px 20px; background: linear-gradient(to bottom right, #0f172a, #1e293b, #0f172a);">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Hero Section -->
        <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 20px; padding: 40px; margin-bottom: 40px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(6, 182, 212, 0.2), transparent); border-radius: 50%;"></div>
            
            <div style="display: grid; grid-template-columns: 1fr 400px; gap: 40px; position: relative; z-index: 1;">
                <div>
                    @if($course->hasDiscount())
                    <div style="display: inline-block; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 6px 15px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; margin-bottom: 15px;">
                        -{{ $course->discount_percentage }}% OFF
                    </div>
                    @endif
                    
                    <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 20px; line-height: 1.2;">
                        {{ $course->title }}
                    </h1>
                    
                    @if($course->description)
                    <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.8); margin-bottom: 30px; line-height: 1.6;">
                        {{ $course->description }}
                    </p>
                    @endif

                    <div style="display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 30px;">
                        @if($course->duration_hours)
                        <div style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.9);">
                            <i class="fas fa-clock" style="font-size: 1.5rem; color: #06b6d4; margin-right: 10px;"></i>
                            <div>
                                <div style="font-weight: 600;">{{ $course->duration_hours }} heures</div>
                                <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">Durée estimée</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($course->students_count > 0)
                        <div style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.9);">
                            <i class="fas fa-users" style="font-size: 1.5rem; color: #10b981; margin-right: 10px;"></i>
                            <div>
                                <div style="font-weight: 600;">{{ $course->students_count }} étudiants</div>
                                <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">Inscrits</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($course->rating > 0)
                        <div style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.9);">
                            <i class="fas fa-star" style="font-size: 1.5rem; color: #fbbf24; margin-right: 10px;"></i>
                            <div>
                                <div style="font-weight: 600;">{{ number_format($course->rating, 1) }}/5</div>
                                <div style="font-size: 0.85rem; color: rgba(255, 255, 255, 0.6);">{{ $course->reviews_count }} avis</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card d'Achat -->
                <div style="background: rgba(15, 23, 42, 0.9); border: 2px solid rgba(6, 182, 212, 0.4); border-radius: 16px; padding: 30px; position: sticky; top: 100px; height: fit-content;">
                    @if($hasPurchased || $isPremium)
                    <div style="text-align: center; padding: 20px; background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 12px; margin-bottom: 20px;">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #10b981; margin-bottom: 10px;"></i>
                        <div style="font-weight: 700; color: white; font-size: 1.1rem;">
                            @if($hasPurchased)
                            Vous avez déjà acheté ce cours
                            @else
                            Accès inclus avec votre abonnement premium
                            @endif
                        </div>
                    </div>
                    <a href="#course-content" style="display: block; width: 100%; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; text-align: center; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-play" style="margin-right: 8px;"></i>
                        Accéder au cours
                    </a>
                    @else
                    <div style="text-align: center; margin-bottom: 25px;">
                        @if($course->hasDiscount())
                        <div style="margin-bottom: 10px;">
                            <span style="font-size: 0.9rem; color: rgba(255, 255, 255, 0.6); text-decoration: line-through;">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div style="font-size: 2.5rem; font-weight: 800; color: #06b6d4; margin-bottom: 5px;">
                            {{ number_format($course->current_price, 0, ',', ' ') }} FCFA
                        </div>
                        <div style="font-size: 0.9rem; color: #10b981; font-weight: 600;">
                            Économisez {{ number_format($course->price - $course->current_price, 0, ',', ' ') }} FCFA
                        </div>
                        @else
                        <div style="font-size: 2.5rem; font-weight: 800; color: #06b6d4; margin-bottom: 5px;">
                            {{ number_format($course->price, 0, ',', ' ') }} FCFA
                        </div>
                        @endif
                    </div>

                    @auth
                    <form action="{{ route('payment.course', $course->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" value="mobile_money">
                        @if(request()->has('ref'))
                        <input type="hidden" name="ref_code" value="{{ request()->get('ref') }}">
                        @endif
                        <button type="submit" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3); margin-bottom: 15px;">
                            <i class="fas fa-shopping-cart" style="margin-right: 8px;"></i>
                            Acheter maintenant
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" style="display: block; width: 100%; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; text-align: center; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                        Se connecter pour acheter
                    </a>
                    @endauth
                    @endif
                </div>
            </div>
        </div>

        <!-- Contenu du Cours -->
        @if($hasPurchased || $isPremium)
        <div id="course-content" style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 20px; padding: 40px; margin-bottom: 40px;">
            <h2 style="font-size: 2rem; font-weight: 700; color: white; margin-bottom: 30px;">
                <i class="fas fa-book-open" style="color: #06b6d4; margin-right: 10px;"></i>
                Contenu du Cours
            </h2>
            
            @if($course->content)
            <div style="color: rgba(255, 255, 255, 0.9); line-height: 1.8; font-size: 1.05rem;">
                {!! nl2br(e($course->content)) !!}
            </div>
            @else
            <p style="color: rgba(255, 255, 255, 0.7); font-size: 1.1rem;">
                Le contenu du cours sera disponible prochainement.
            </p>
            @endif
        </div>
        @endif

        <!-- Ce que vous allez apprendre -->
        @if($course->what_you_learn && count($course->what_you_learn) > 0)
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 20px; padding: 40px; margin-bottom: 40px;">
            <h2 style="font-size: 2rem; font-weight: 700; color: white; margin-bottom: 30px;">
                <i class="fas fa-check-circle" style="color: #10b981; margin-right: 10px;"></i>
                Ce que vous allez apprendre
            </h2>
            <ul style="list-style: none; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                @foreach($course->what_you_learn as $item)
                <li style="display: flex; align-items: start; padding: 15px; background: rgba(15, 23, 42, 0.6); border-radius: 10px; border-left: 3px solid #10b981;">
                    <i class="fas fa-check" style="color: #10b981; margin-right: 12px; margin-top: 4px; flex-shrink: 0;"></i>
                    <span style="color: rgba(255, 255, 255, 0.9);">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Prérequis -->
        @if($course->requirements && count($course->requirements) > 0)
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 20px; padding: 40px;">
            <h2 style="font-size: 2rem; font-weight: 700; color: white; margin-bottom: 30px;">
                <i class="fas fa-list-check" style="color: #06b6d4; margin-right: 10px;"></i>
                Prérequis
            </h2>
            <ul style="list-style: none; padding: 0;">
                @foreach($course->requirements as $requirement)
                <li style="padding: 12px 0; color: rgba(255, 255, 255, 0.9); display: flex; align-items: center; border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                    <i class="fas fa-circle" style="color: #06b6d4; margin-right: 12px; font-size: 0.5rem;"></i>
                    <span>{{ $requirement }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>
</div>

<style>
    @media (max-width: 968px) {
        .course-hero-grid { grid-template-columns: 1fr !important; }
        .purchase-card { position: static !important; }
    }
</style>
@endsection



