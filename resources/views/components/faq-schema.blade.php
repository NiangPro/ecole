{{--
    Composant FAQ Schema (Tâche 3 — Position Zéro Google)
    Usage : <x-faq-schema :faqs="$articleFaqs" />
    ou     : @include('components.faq-schema', ['faqs' => $articleFaqs])

    Chaque $faq doit avoir les clés 'question' et 'answer'.
    Le JSON-LD est placé dans le <head> via @push('meta').
--}}
@props(['faqs' => []])

{{-- JSON-LD FAQPage injecté directement depuis article.blade.php (@push('meta'))
     pour garantir son placement dans le <head> avant @stack('meta'). --}}

@if(!empty($faqs))
{{-- Section FAQ visible dans la page (renforce la position zéro) --}}
<section class="np-faq-section" aria-labelledby="faq-heading">
<style>
.np-faq-section{
    margin:2.5rem 0 0;
    padding:2rem 2.25rem;
    background:linear-gradient(135deg,rgba(5,150,105,.06),rgba(8,145,178,.06));
    border:1px solid rgba(8,145,178,.18);
    border-radius:16px;
}
.np-faq-section h2{
    font-size:1.15rem;font-weight:800;
    color:#0891b2;margin:0 0 1.2rem;
    display:flex;align-items:center;gap:.55rem;
}
.np-faq-item{margin-bottom:1rem;}
.np-faq-item:last-child{margin-bottom:0;}
.np-faq-q{
    font-size:.95rem;font-weight:700;
    color:rgba(15,23,42,.9);margin-bottom:.35rem;
    display:flex;align-items:flex-start;gap:.5rem;
}
.np-faq-q::before{content:"Q.";color:#0891b2;font-weight:900;flex-shrink:0;}
.np-faq-a{
    font-size:.875rem;line-height:1.65;
    color:rgba(30,41,59,.78);
    padding-left:1.5rem;
}
body.dark-mode .np-faq-section{
    background:rgba(8,145,178,.07);
    border-color:rgba(8,145,178,.25);
}
body.dark-mode .np-faq-q{color:rgba(255,255,255,.9);}
body.dark-mode .np-faq-a{color:rgba(255,255,255,.7);}
body.dark-mode .np-faq-section h2{color:#06b6d4;}
</style>
<h2 id="faq-heading"><i class="fas fa-question-circle"></i> Questions Fréquentes</h2>
@foreach($faqs as $faq)
<div class="np-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
    <div class="np-faq-q" itemprop="name">{{ $faq['question'] }}</div>
    <div class="np-faq-a" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
        <span itemprop="text">{{ $faq['answer'] }}</span>
    </div>
</div>
@endforeach
</section>
@endif
