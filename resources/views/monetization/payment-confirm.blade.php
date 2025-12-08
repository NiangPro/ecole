@extends('layouts.app')

@section('title', 'Confirmation de Paiement - NiangProgrammeur')

@section('content')
<div style="min-height: 100vh; padding: 40px 20px; background: linear-gradient(to bottom right, #0f172a, #1e293b, #0f172a);">
    <div style="max-width: 800px; margin: 0 auto;">
        
        <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 20px; padding: 50px 40px; text-align: center;">
            
            @if($payment->status === 'pending')
            <div style="font-size: 5rem; margin-bottom: 30px;">
                ⏳
            </div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 20px;">
                Paiement en Attente
            </h1>
            <p style="font-size: 1.2rem; color: rgba(255, 255, 255, 0.8); margin-bottom: 40px;">
                Votre paiement est en cours de traitement. Vous recevrez une confirmation par email une fois le paiement validé.
            </p>

            <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 30px; text-align: left;">
                <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 25px; text-align: center;">
                    Détails du Paiement
                </h3>
                
                <div style="display: grid; gap: 20px;">
                    <div style="display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Type :</span>
                        <span style="color: white; font-weight: 700;">
                            @if($payment->paymentable_type === 'App\Models\Subscription')
                                Abonnement
                            @elseif($payment->paymentable_type === 'App\Models\CoursePurchase')
                                Achat de cours
                            @elseif($payment->paymentable_type === 'App\Models\Donation')
                                Don
                            @endif
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Montant :</span>
                        <span style="color: #06b6d4; font-weight: 700; font-size: 1.25rem;">
                            {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Méthode :</span>
                        <span style="color: white; font-weight: 600;">
                            @if($payment->payment_method === 'mobile_money')
                                Mobile Money
                            @elseif($payment->payment_method === 'bank_transfer')
                                Virement bancaire
                            @elseif($payment->payment_method === 'stripe')
                                Carte bancaire
                            @elseif($payment->payment_method === 'paypal')
                                PayPal
                            @else
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                            @endif
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid rgba(6, 182, 212, 0.2);">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Référence :</span>
                        <span style="color: white; font-family: monospace; font-weight: 600;">
                            {{ $payment->payment_reference }}
                        </span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; padding: 15px 0;">
                        <span style="color: rgba(255, 255, 255, 0.7); font-weight: 600;">Statut :</span>
                        <span style="padding: 6px 15px; background: rgba(251, 191, 36, 0.2); border: 1px solid #fbbf24; border-radius: 20px; color: #fbbf24; font-weight: 600; font-size: 0.9rem;">
                            En attente
                        </span>
                    </div>
                </div>
            </div>

            <div style="background: rgba(6, 182, 212, 0.1); border-left: 4px solid #06b6d4; border-radius: 8px; padding: 20px; margin-bottom: 30px; text-align: left;">
                <div style="display: flex; align-items: start;">
                    <i class="fas fa-info-circle" style="color: #06b6d4; font-size: 1.5rem; margin-right: 15px; margin-top: 2px;"></i>
                    <div>
                        <h4 style="color: white; font-weight: 700; margin-bottom: 10px;">Instructions de Paiement</h4>
                        <p style="color: rgba(255, 255, 255, 0.8); line-height: 1.6;">
                            @if($payment->payment_method === 'mobile_money')
                                Effectuez le paiement via votre application Mobile Money en utilisant la référence ci-dessus. 
                                Le paiement sera validé automatiquement une fois reçu.
                            @elseif($payment->payment_method === 'bank_transfer')
                                Effectuez un virement bancaire en utilisant la référence ci-dessus comme motif de virement. 
                                Le paiement sera validé sous 24-48h.
                            @else
                                Suivez les instructions de votre méthode de paiement pour compléter la transaction.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('monetization.index') }}" style="padding: 12px 30px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>
                    Retour
                </a>
                @auth
                <a href="{{ route('dashboard.overview') }}" style="padding: 12px 30px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i>
                    Mon Dashboard
                </a>
                @endauth
            </div>

            @elseif($payment->status === 'completed')
            <div style="font-size: 5rem; margin-bottom: 30px;">
                ✅
            </div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 20px;">
                Paiement Confirmé !
            </h1>
            <p style="font-size: 1.2rem; color: rgba(255, 255, 255, 0.8); margin-bottom: 40px;">
                Votre paiement a été confirmé avec succès. Vous pouvez maintenant accéder à votre contenu.
            </p>

            <div style="background: rgba(16, 185, 129, 0.2); border: 2px solid #10b981; border-radius: 16px; padding: 30px; margin-bottom: 30px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <span style="color: rgba(255, 255, 255, 0.9); font-weight: 600;">Montant payé :</span>
                    <span style="color: #10b981; font-weight: 800; font-size: 1.5rem;">
                        {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: rgba(255, 255, 255, 0.9); font-weight: 600;">Référence :</span>
                    <span style="color: white; font-family: monospace; font-weight: 600;">
                        {{ $payment->payment_reference }}
                    </span>
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                @if($payment->paymentable_type === 'App\Models\Subscription')
                <a href="{{ route('dashboard.overview') }}" style="padding: 15px 40px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-crown" style="margin-right: 8px;"></i>
                    Accéder au contenu Premium
                </a>
                @elseif($payment->paymentable_type === 'App\Models\CoursePurchase')
                <a href="{{ route('monetization.course.show', $payment->paymentable->course->slug) }}" style="padding: 15px 40px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-play" style="margin-right: 8px;"></i>
                    Accéder au cours
                </a>
                @endif
                <a href="{{ route('monetization.index') }}" style="padding: 15px 40px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    Retour à la page de monétisation
                </a>
            </div>

            @else
            <div style="font-size: 5rem; margin-bottom: 30px;">
                ❌
            </div>
            <h1 style="font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 20px;">
                Paiement Échoué
            </h1>
            <p style="font-size: 1.2rem; color: rgba(255, 255, 255, 0.8); margin-bottom: 40px;">
                Votre paiement n'a pas pu être traité. Veuillez réessayer ou contacter le support.
            </p>

            @if($payment->failure_reason)
            <div style="background: rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; border-radius: 8px; padding: 20px; margin-bottom: 30px; text-align: left;">
                <div style="color: #ef4444; font-weight: 700; margin-bottom: 10px;">Raison :</div>
                <div style="color: rgba(255, 255, 255, 0.9);">{{ $payment->failure_reason }}</div>
            </div>
            @endif

            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('monetization.index') }}" style="padding: 15px 40px; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: white; border: none; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);">
                    <i class="fas fa-redo" style="margin-right: 8px;"></i>
                    Réessayer
                </a>
                <a href="{{ route('contact') }}" style="padding: 15px 40px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border: 2px solid #06b6d4; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-headset" style="margin-right: 8px;"></i>
                    Contacter le support
                </a>
            </div>
            @endif

        </div>

    </div>
</div>
@endsection



