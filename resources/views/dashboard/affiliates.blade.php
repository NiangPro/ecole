@extends('dashboard.layout')

@section('dashboard-content')
<!-- Messages Flash -->
@if(session('success'))
<div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; border-radius: 12px; padding: 15px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 15px;">
    <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.2rem;"></i>
    <div style="flex: 1; color: #10b981;">
        <strong>{{ session('success') }}</strong>
    </div>
    <button onclick="this.parentElement.remove()" style="background: none; border: none; color: #10b981; cursor: pointer; font-size: 1.2rem;">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

<!-- Stats Cards -->
<div class="affiliate-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="affiliate-stat-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; margin-bottom: 10px; font-weight: 600;">{{ trans('app.affiliates.dashboard.total_earnings') }}</div>
        <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ number_format($stats['total_earnings'], 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="affiliate-stat-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; margin-bottom: 10px; font-weight: 600;">{{ trans('app.affiliates.dashboard.paid_earnings') }}</div>
        <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">{{ number_format($stats['paid_earnings'], 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="affiliate-stat-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; margin-bottom: 10px; font-weight: 600;">{{ trans('app.affiliates.dashboard.pending_earnings') }}</div>
        <div style="font-size: 2.5rem; font-weight: 800; color: #fbbf24;">{{ number_format($stats['pending_earnings'], 0, ',', ' ') }} FCFA</div>
    </div>
    <div class="affiliate-stat-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; margin-bottom: 10px; font-weight: 600;">{{ trans('app.affiliates.dashboard.commission_rate') }}</div>
        <div style="font-size: 2.5rem; font-weight: 800; color: #06b6d4;">{{ number_format($stats['commission_rate'], 2) }}%</div>
    </div>
</div>

<!-- Lien d'Affiliation -->
<div class="affiliate-link-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: rgba(30, 41, 59, 0.9); margin-bottom: 20px;">
        <i class="fas fa-link" style="color: #06b6d4; margin-right: 10px;"></i>
        {{ trans('app.affiliates.dashboard.affiliate_link_title') }}
    </h2>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <input type="text" id="affiliateLink" value="{{ $affiliate->referral_url }}" readonly style="flex: 1; min-width: 300px; padding: 12px 15px; background: rgba(255, 255, 255, 0.8); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 8px; color: rgba(30, 41, 59, 0.9); font-size: 0.95rem; font-family: monospace;">
        <button onclick="copyAffiliateLink()" id="copyButton" style="padding: 12px 24px; background: #06b6d4; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
            <i class="fas fa-copy" style="margin-right: 8px;"></i>
            <span id="copyButtonText">{{ trans('app.affiliates.dashboard.copy_button') }}</span>
        </button>
    </div>
    <div style="margin-top: 15px; color: rgba(30, 41, 59, 0.6); font-size: 0.9rem;">
        <i class="fas fa-info-circle" style="margin-right: 8px; color: #06b6d4;"></i>
        {{ trans('app.affiliates.dashboard.affiliate_code_label') }}: <code style="background: rgba(6, 182, 212, 0.1); padding: 3px 8px; border-radius: 4px; color: #06b6d4; font-weight: 600;">{{ $affiliate->affiliate_code }}</code>
    </div>
    
    <!-- Boutons de Partage sur les Réseaux Sociaux -->
    <div style="margin-top: 25px; padding-top: 25px; border-top: 1px solid rgba(6, 182, 212, 0.2);">
        <h3 style="font-size: 1.1rem; font-weight: 600; color: rgba(30, 41, 59, 0.9); margin-bottom: 15px;">
            <i class="fas fa-share-alt" style="color: #06b6d4; margin-right: 8px;"></i>
            {{ trans('app.affiliates.dashboard.share_title') }}
        </h3>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button onclick="shareOnFacebook()" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: #1877f2; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                <i class="fab fa-facebook-f"></i>
                <span>{{ trans('app.affiliates.dashboard.share_facebook') }}</span>
            </button>
            <button onclick="shareOnTwitter()" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: #1da1f2; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                <i class="fab fa-twitter"></i>
                <span>{{ trans('app.affiliates.dashboard.share_twitter') }}</span>
            </button>
            <button onclick="shareOnLinkedIn()" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: #0077b5; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                <i class="fab fa-linkedin-in"></i>
                <span>{{ trans('app.affiliates.dashboard.share_linkedin') }}</span>
            </button>
            <button onclick="shareOnWhatsApp()" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: #25d366; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                <i class="fab fa-whatsapp"></i>
                <span>{{ trans('app.affiliates.dashboard.share_whatsapp') }}</span>
            </button>
            <button onclick="shareOnTelegram()" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: #0088cc; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                <i class="fab fa-telegram-plane"></i>
                <span>{{ trans('app.affiliates.dashboard.share_telegram') }}</span>
            </button>
            <button onclick="shareByEmail()" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: #06b6d4; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem;">
                <i class="fas fa-envelope"></i>
                <span>{{ trans('app.affiliates.dashboard.share_email') }}</span>
            </button>
        </div>
    </div>
</div>

<!-- Statistiques des Références -->
<div class="referral-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="referral-stats-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 2rem; font-weight: 800; color: rgba(30, 41, 59, 0.9); margin-bottom: 5px;">{{ $stats['total_referrals'] }}</div>
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.total_referrals') }}</div>
    </div>
    <div class="referral-stats-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(251, 191, 36, 0.3); border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 2rem; font-weight: 800; color: #fbbf24; margin-bottom: 5px;">{{ $stats['pending_referrals'] }}</div>
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.pending_referrals') }}</div>
    </div>
    <div class="referral-stats-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 2rem; font-weight: 800; color: #06b6d4; margin-bottom: 5px;">{{ $stats['approved_referrals'] }}</div>
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.approved_referrals') }}</div>
    </div>
    <div class="referral-stats-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(16, 185, 129, 0.3); border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <div style="font-size: 2rem; font-weight: 800; color: #10b981; margin-bottom: 5px;">{{ $stats['paid_referrals'] }}</div>
        <div style="color: rgba(30, 41, 59, 0.6); font-size: 0.9rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.paid_referrals') }}</div>
    </div>
</div>

<!-- Liste des Références -->
<div class="referrals-table-card" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 250, 252, 0.9)); border: 2px solid rgba(6, 182, 212, 0.3); border-radius: 16px; padding: 30px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: rgba(30, 41, 59, 0.9); margin-bottom: 20px;">
        <i class="fas fa-list" style="color: #06b6d4; margin-right: 10px;"></i>
        {{ trans('app.affiliates.dashboard.referrals_list_title') }} ({{ $referrals->total() }})
    </h2>

    @if($referrals->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: rgba(6, 182, 212, 0.1);">
                    <th style="padding: 15px; text-align: left; color: rgba(30, 41, 59, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">{{ trans('app.affiliates.dashboard.table_date') }}</th>
                    <th style="padding: 15px; text-align: left; color: rgba(30, 41, 59, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">{{ trans('app.affiliates.dashboard.table_type') }}</th>
                    <th style="padding: 15px; text-align: left; color: rgba(30, 41, 59, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">{{ trans('app.affiliates.dashboard.table_amount') }}</th>
                    <th style="padding: 15px; text-align: left; color: rgba(30, 41, 59, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">{{ trans('app.affiliates.dashboard.table_commission') }}</th>
                    <th style="padding: 15px; text-align: left; color: rgba(30, 41, 59, 0.9); font-weight: 600; border-bottom: 2px solid rgba(6, 182, 212, 0.3);">{{ trans('app.affiliates.dashboard.table_status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($referrals as $referral)
                <tr style="border-bottom: 1px solid rgba(6, 182, 212, 0.1);">
                    <td style="padding: 15px; color: rgba(30, 41, 59, 0.9);">{{ $referral->created_at->format('d/m/Y H:i') }}</td>
                    <td style="padding: 15px; color: rgba(30, 41, 59, 0.9);">
                        @if($referral->referral_type === 'subscription')
                            <span style="padding: 4px 8px; background: rgba(6, 182, 212, 0.2); color: #06b6d4; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.type_subscription') }}</span>
                        @elseif($referral->referral_type === 'course_purchase')
                            <span style="padding: 4px 8px; background: rgba(139, 92, 246, 0.2); color: #8b5cf6; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.type_course') }}</span>
                        @elseif($referral->referral_type === 'donation')
                            <span style="padding: 4px 8px; background: rgba(239, 68, 68, 0.2); color: #ef4444; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.type_donation') }}</span>
                        @else
                            <span style="padding: 4px 8px; background: rgba(107, 114, 128, 0.2); color: #6b7280; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.type_other') }}</span>
                        @endif
                    </td>
                    <td style="padding: 15px; color: rgba(30, 41, 59, 0.9); font-weight: 600;">{{ number_format($referral->amount, 0, ',', ' ') }} FCFA</td>
                    <td style="padding: 15px; color: #10b981; font-weight: 600;">{{ number_format($referral->commission, 0, ',', ' ') }} FCFA</td>
                    <td style="padding: 15px;">
                        <span style="padding: 5px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;
                            background: {{ $referral->status === 'paid' ? 'rgba(16, 185, 129, 0.2)' : ($referral->status === 'approved' ? 'rgba(6, 182, 212, 0.2)' : ($referral->status === 'rejected' ? 'rgba(239, 68, 68, 0.2)' : 'rgba(251, 191, 36, 0.2)')) }};
                            color: {{ $referral->status === 'paid' ? '#10b981' : ($referral->status === 'approved' ? '#06b6d4' : ($referral->status === 'rejected' ? '#ef4444' : '#fbbf24')) }};
                            border: 1px solid {{ $referral->status === 'paid' ? '#10b981' : ($referral->status === 'approved' ? '#06b6d4' : ($referral->status === 'rejected' ? '#ef4444' : '#fbbf24')) }};
                        ">
                            @if($referral->status === 'pending')
                                {{ trans('app.affiliates.dashboard.status_pending') }}
                            @elseif($referral->status === 'approved')
                                {{ trans('app.affiliates.dashboard.status_approved') }}
                            @elseif($referral->status === 'paid')
                                {{ trans('app.affiliates.dashboard.status_paid') }}
                            @else
                                {{ trans('app.affiliates.dashboard.status_rejected') }}
                            @endif
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $referrals->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 40px; color: rgba(30, 41, 59, 0.6);">
        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5; color: #06b6d4;"></i>
        <p style="font-size: 1.1rem; font-weight: 600;">{{ trans('app.affiliates.dashboard.empty_title') }}</p>
        <p style="margin-top: 10px;">{{ trans('app.affiliates.dashboard.empty_text') }}</p>
    </div>
    @endif
</div>

<script>
const affiliateUrl = '{{ $affiliate->referral_url }}';
const shareText = '{{ trans('app.affiliates.dashboard.share_title') }}';

function copyAffiliateLink() {
    const linkInput = document.getElementById('affiliateLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        const button = document.getElementById('copyButton');
        const buttonText = document.getElementById('copyButtonText');
        const originalText = buttonText.textContent;
        button.innerHTML = '<i class="fas fa-check" style="margin-right: 8px;"></i><span id="copyButtonText">{{ trans('app.affiliates.dashboard.copied_button') }}</span>';
        button.style.background = '#10b981';
        
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-copy" style="margin-right: 8px;"></i><span id="copyButtonText">' + originalText + '</span>';
            button.style.background = '#06b6d4';
        }, 2000);
    } catch (err) {
        alert('{{ trans('app.affiliates.dashboard.copy_error') }}');
    }
}

function shareOnFacebook() {
    const url = encodeURIComponent(affiliateUrl);
    const text = encodeURIComponent(shareText);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const url = encodeURIComponent(affiliateUrl);
    const text = encodeURIComponent(shareText);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function shareOnLinkedIn() {
    const url = encodeURIComponent(affiliateUrl);
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(affiliateUrl);
    const text = encodeURIComponent(shareText + ' ' + affiliateUrl);
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareOnTelegram() {
    const url = encodeURIComponent(affiliateUrl);
    const text = encodeURIComponent(shareText + ' ' + affiliateUrl);
    window.open(`https://t.me/share/url?url=${url}&text=${text}`, '_blank');
}

function shareByEmail() {
    const subject = encodeURIComponent(shareText);
    const body = encodeURIComponent(shareText + '\n\n' + affiliateUrl);
    window.location.href = `mailto:?subject=${subject}&body=${body}`;
}
</script>

<style>
body.dark-mode .affiliate-stat-card,
body.dark-mode .affiliate-link-card,
body.dark-mode .referral-stats-card,
body.dark-mode .referrals-table-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(51, 65, 85, 0.8)) !important;
    border-color: rgba(6, 182, 212, 0.3) !important;
}

body.dark-mode .affiliate-stat-card div:first-child,
body.dark-mode .affiliate-link-card h2,
body.dark-mode .affiliate-link-card h3,
body.dark-mode .referrals-table-card h2,
body.dark-mode .referral-stats-card div:first-child {
    color: rgba(255, 255, 255, 0.9) !important;
}

body.dark-mode .affiliate-stat-card div:last-child {
    color: inherit !important;
}

body.dark-mode .affiliate-link-card input {
    background: rgba(15, 23, 42, 0.6) !important;
    color: white !important;
    border-color: rgba(6, 182, 212, 0.3) !important;
}

body.dark-mode .referrals-table-card table th,
body.dark-mode .referrals-table-card table td {
    color: rgba(255, 255, 255, 0.9) !important;
}

body.dark-mode .referrals-table-card .fas.fa-inbox,
body.dark-mode .referrals-table-card p {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Styles pour les boutons de partage */
button[onclick^="share"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .referrals-table-card table {
        font-size: 0.85rem;
    }
    .referrals-table-card th,
    .referrals-table-card td {
        padding: 10px 8px !important;
    }
    
    button[onclick^="share"] {
        font-size: 0.85rem;
        padding: 8px 14px;
    }
    
    button[onclick^="share"] span {
        display: none;
    }
}
</style>
@endsection
