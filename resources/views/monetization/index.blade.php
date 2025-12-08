@extends('layouts.app')

@section('title', 'Monétisation - NiangProgrammeur')

@section('content')
<div style="min-height: 100vh; padding: 40px 20px; background: linear-gradient(to bottom right, #0f172a, #1e293b, #0f172a);">
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Hero Section -->
        <div style="text-align: center; margin-bottom: 60px; padding: 60px 20px;">
            <h1 style="font-size: 3rem; font-weight: 800; color: white; margin-bottom: 20px; text-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);">
                💰 Générer des Revenus
            </h1>
            <p style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.8); max-width: 700px; margin: 0 auto;">
                Soutenez la plateforme et accédez à du contenu premium exclusif
            </p>
        </div>

        <!-- Plans d'Abonnement -->
        <section style="margin-bottom: 80px;">
            <h2 style="font-size: 2.5rem; font-weight: 700; color: white; text-align: center; margin-bottom: 50px;">
                <i class="fas fa-crown" style="color: #fbbf24; margin-right: 10px;"></i>
                Abonnements Premium
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
                @foreach($subscriptionPlans as $planType => $plan)
                <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 20px; padding: 40px 30px; position: relative; overflow: hidden; transition: all 0.3s ease;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(6, 182, 212, 0.2), transparent); border-radius: 50%;"></div>
                    
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h3 style="font-size: 1.75rem; font-weight: 700; color: white; margin-bottom: 10px;">
                            {{ $plan['name'] }}
                        </h3>
                        <div style="margin: 20px 0;">
                            <span style="font-size: 3rem; font-weight: 800; color: #06b6d4;">{{ number_format($plan['price'], 0, ',', ' ') }}</span>
                            <span style="font-size: 1.25rem; color: rgba(255, 255, 255, 0.6);"> FCFA</span>
                        </div>
                        <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.95rem;">par mois</p>
                    </div>

                    <ul style="list-style: none; padding: 0; margin-bottom: 30px;">
                        @foreach($plan['features'] as $feature)
                        <li style="padding: 12px 0; color: rgba(255, 255, 255, 0.9); display: flex; align-items: center;">
                            <i class="fas fa-check-circle" style="color: #10b981; margin-right: 12px; font-size: 1.1rem;"></i>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>

                    @auth
                    <form action="{{ route('payment.subscription') }}" method="POST" style="margin-top: 30px;">
                        @csrf
                        <input type="hidden" name="plan_type" value="{{ $planType }}">
                        <input type="hidden" name="payment_method" value="mobile_money">
                        @if(request()->has('ref'))
                        <input type="hidden" name="ref_code" value="{{ request()->get('ref') }}">
                        @endif
                        <button type="submit" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                            <i class="fas fa-credit-card" style="margin-right: 8px;"></i>
                            S'abonner maintenant
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" style="display: block; width: 100%; padding: 15px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; text-align: center; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                        <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                        Se connecter pour s'abonner
                    </a>
                    @endauth
                </div>
                @endforeach
            </div>
        </section>

        <!-- Cours Payants -->
        @if($paidCourses->count() > 0)
        <section style="margin-bottom: 80px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
                <h2 style="font-size: 2.5rem; font-weight: 700; color: white;">
                    <i class="fas fa-graduation-cap" style="color: #06b6d4; margin-right: 10px;"></i>
                    Cours Payants
                </h2>
                <a href="{{ route('monetization.courses') }}" style="padding: 12px 24px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    Voir tous les cours <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
                @foreach($paidCourses as $course)
                <a href="{{ route('monetization.course.show', $course->slug) }}" style="text-decoration: none; display: block;">
                    <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; overflow: hidden; transition: all 0.3s ease; hover:transform: translateY(-5px);">
                        @if($course->image)
                        <div style="width: 100%; height: 180px; background-image: url('{{ $course->image }}'); background-size: cover; background-position: center;"></div>
                        @else
                        <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #06b6d4, #14b8a6); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-book" style="font-size: 3rem; color: white; opacity: 0.5;"></i>
                        </div>
                        @endif
                        
                        <div style="padding: 20px;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: white; margin-bottom: 10px; line-height: 1.4;">
                                {{ $course->title }}
                            </h3>
                            @if($course->description)
                            <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem; margin-bottom: 15px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $course->description }}
                            </p>
                            @endif
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                                <div>
                                    @if($course->hasDiscount())
                                    <span style="font-size: 1.5rem; font-weight: 700; color: #06b6d4;">{{ number_format($course->discount_price, 0, ',', ' ') }} FCFA</span>
                                    <span style="font-size: 1rem; color: rgba(255, 255, 255, 0.5); text-decoration: line-through; margin-left: 8px;">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                    @else
                                    <span style="font-size: 1.5rem; font-weight: 700; color: #06b6d4;">{{ number_format($course->price, 0, ',', ' ') }} FCFA</span>
                                    @endif
                                </div>
                                <i class="fas fa-arrow-right" style="color: #06b6d4;"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Section Donations -->
        <section style="margin-bottom: 80px;">
            <div style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border: 2px solid rgba(239, 68, 68, 0.3); border-radius: 20px; padding: 50px 40px; text-align: center;">
                <div style="font-size: 4rem; margin-bottom: 20px;">
                    ❤️
                </div>
                <h2 style="font-size: 2.5rem; font-weight: 700; color: white; margin-bottom: 20px;">
                    Faire un Don
                </h2>
                <p style="font-size: 1.1rem; color: rgba(255, 255, 255, 0.8); max-width: 600px; margin: 0 auto 30px;">
                    Votre soutien nous aide à continuer à fournir du contenu éducatif gratuit de qualité
                </p>

                <div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; flex-wrap: wrap;">
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ number_format($totalDonations, 0, ',', ' ') }}</div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">FCFA collectés</div>
                    </div>
                    <div style="width: 1px; background: rgba(255, 255, 255, 0.2);"></div>
                    <div style="text-align: center;">
                        <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ $donationsCount }}</div>
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Donateurs</div>
                    </div>
                </div>

                <a href="#donation-form" onclick="document.getElementById('donation-form').scrollIntoView({behavior: 'smooth'})" style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);">
                    <i class="fas fa-heart" style="margin-right: 8px;"></i>
                    Faire un don maintenant
                </a>
            </div>

            <!-- Formulaire de Don -->
            <div id="donation-form" style="margin-top: 50px; background: rgba(30, 41, 59, 0.6); border-radius: 16px; padding: 40px; border: 1px solid rgba(6, 182, 212, 0.3);">
                <h3 style="font-size: 1.75rem; font-weight: 700; color: white; margin-bottom: 30px; text-align: center;">
                    Formulaire de Don
                </h3>
                
                <form action="{{ route('payment.donation') }}" method="POST" style="max-width: 500px; margin: 0 auto;">
                    @csrf
                    @if(request()->has('ref'))
                    <input type="hidden" name="ref_code" value="{{ request()->get('ref') }}">
                    @endif

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                            Nom <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="donor_name" required style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                            Email (optionnel)
                        </label>
                        <input type="email" name="donor_email" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                            Montant (FCFA) <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="number" name="amount" required id="donation_amount" min="100" step="100" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                        <p id="amount_minimum_info" style="color: rgba(255, 255, 255, 0.6); font-size: 0.85rem; margin-top: 5px;">Minimum : 100 FCFA</p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                            Méthode de paiement <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="payment_method" required id="payment_method_select" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem;">
                            <option value="wave">Wave (Recommandé - Paiement instantané)</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Virement bancaire</option>
                            <option value="stripe">Carte bancaire (Stripe)</option>
                            <option value="paypal">PayPal</option>
                        </select>
                        <p id="wave_minimum_info" style="color: #06b6d4; font-size: 0.85rem; margin-top: 5px; display: none;">
                            <i class="fas fa-info-circle" style="margin-right: 5px;"></i>
                            Montant minimum pour Wave : 1,000 FCFA
                        </p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: white; font-weight: 600; margin-bottom: 8px;">
                            Message (optionnel)
                        </label>
                        <textarea name="message" rows="3" style="width: 100%; padding: 12px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: white; font-size: 1rem; resize: vertical;"></textarea>
                    </div>

                    <div style="margin-bottom: 25px;">
                        <label style="display: flex; align-items: center; color: white; cursor: pointer;">
                            <input type="checkbox" name="is_anonymous" style="margin-right: 10px; width: 18px; height: 18px; cursor: pointer;">
                            <span>Don anonyme</span>
                        </label>
                    </div>

                    <button type="submit" style="width: 100%; padding: 15px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);">
                        <i class="fas fa-heart" style="margin-right: 8px;"></i>
                        Confirmer le don
                    </button>
                </form>
            </div>

            <!-- Mur des Donateurs -->
            @if($recentDonations->count() > 0)
            <div style="margin-top: 50px;">
                <h3 style="font-size: 1.75rem; font-weight: 700; color: white; margin-bottom: 30px; text-align: center;">
                    <i class="fas fa-heart" style="color: #ef4444; margin-right: 10px;"></i>
                    Nos Généreux Donateurs
                </h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                    @foreach($recentDonations as $donation)
                    <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 20px; text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 10px;">❤️</div>
                        <div style="font-weight: 600; color: white; margin-bottom: 5px;">{{ $donation->display_name }}</div>
                        <div style="color: #10b981; font-weight: 700;">{{ number_format($donation->amount, 0, ',', ' ') }} FCFA</div>
                        @if($donation->message)
                        <div style="color: rgba(255, 255, 255, 0.7); font-size: 0.85rem; margin-top: 10px; font-style: italic;">
                            "{{ \Illuminate\Support\Str::limit($donation->message, 50) }}"
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </section>

    </div>
</div>

<style>
    @media (max-width: 768px) {
        h1 { font-size: 2rem !important; }
        h2 { font-size: 1.75rem !important; }
        .subscription-grid { grid-template-columns: 1fr !important; }
    }
</style>

<script>
    // Gérer l'affichage du minimum Wave
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodSelect = document.getElementById('payment_method_select');
        const donationAmount = document.getElementById('donation_amount');
        const waveMinimumInfo = document.getElementById('wave_minimum_info');
        const amountMinimumInfo = document.getElementById('amount_minimum_info');
        
        function updateMinimumInfo() {
            if (paymentMethodSelect.value === 'wave') {
                waveMinimumInfo.style.display = 'block';
                amountMinimumInfo.textContent = 'Minimum pour Wave : 1,000 FCFA';
                donationAmount.min = 1000;
                if (parseInt(donationAmount.value) < 1000) {
                    donationAmount.value = 1000;
                }
            } else {
                waveMinimumInfo.style.display = 'none';
                amountMinimumInfo.textContent = 'Minimum : 100 FCFA';
                donationAmount.min = 100;
            }
        }
        
        paymentMethodSelect.addEventListener('change', updateMinimumInfo);
        updateMinimumInfo(); // Initialiser
    });
</script>
@endsection

