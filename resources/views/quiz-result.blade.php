@extends('layouts.app')

@push('page_css')
@vite('resources/css/features/quiz-result.css')
@endpush

@php
  $langMap = [
    'html5'        => ['icon' => 'fab fa-html5',       'color' => 'orange'],
    'css3'         => ['icon' => 'fab fa-css3-alt',    'color' => 'blue'],
    'javascript'   => ['icon' => 'fab fa-js',           'color' => 'yellow'],
    'php'          => ['icon' => 'fab fa-php',          'color' => 'cyan'],
    'bootstrap'    => ['icon' => 'fab fa-bootstrap',   'color' => 'cyan'],
    'git'          => ['icon' => 'fab fa-git-alt',     'color' => 'red'],
    'wordpress'    => ['icon' => 'fab fa-wordpress',   'color' => 'blue'],
    'ia'           => ['icon' => 'fas fa-robot',        'color' => 'green'],
    'python'       => ['icon' => 'fab fa-python',       'color' => 'blue'],
    'java'         => ['icon' => 'fab fa-java',         'color' => 'orange'],
    'sql'          => ['icon' => 'fas fa-database',    'color' => 'blue'],
    'c'            => ['icon' => 'fab fa-c',            'color' => 'gray'],
    'cpp'          => ['icon' => 'fab fa-cuttlefish',  'color' => 'blue'],
    'csharp'       => ['icon' => 'fab fa-microsoft',   'color' => 'green'],
    'dart'         => ['icon' => 'fas fa-feather-alt', 'color' => 'blue'],
    'go'           => ['icon' => 'fab fa-golang',      'color' => 'blue'],
    'swift'        => ['icon' => 'fab fa-swift',       'color' => 'orange'],
    'perl'         => ['icon' => 'fas fa-code',        'color' => 'blue'],
    'typescript'   => ['icon' => 'fab fa-js-square',   'color' => 'blue'],
    'rust'         => ['icon' => 'fab fa-rust',        'color' => 'gray'],
    'ruby'         => ['icon' => 'fas fa-gem',         'color' => 'red'],
    'cybersecurite'=> ['icon' => 'fas fa-shield-alt',  'color' => 'orange'],
    'data-science' => ['icon' => 'fas fa-chart-line',  'color' => 'blue'],
    'big-data'     => ['icon' => 'fas fa-database',    'color' => 'cyan'],
  ];

  $pct   = (int) round($percentage);
  $wrong = $total - $score;

  /* Score tier */
  if ($pct >= 80) {
    $tier    = 'great';
    $tierKey = trans('app.quiz.result.excellent');
    $emoji   = '🏆';
  } elseif ($pct >= 60) {
    $tier    = 'good';
    $tierKey = trans('app.quiz.result.good');
    $emoji   = '👍';
  } elseif ($pct >= 40) {
    $tier    = 'medium';
    $tierKey = trans('app.quiz.result.continue');
    $emoji   = '💪';
  } else {
    $tier    = 'low';
    $tierKey = trans('app.quiz.result.dont_give_up');
    $emoji   = '🎯';
  }

  /* SVG ring: r=70, circumference ≈ 439.82 → use 440 */
  $ringCirc   = 440;
  $ringOffset = round($ringCirc * (1 - $pct / 100), 2);

  $langLabel = trans('app.formations.languages.' . $language, [], null, ucfirst($language));
@endphp

@section('title', trans('app.quiz.result.title') . ' ' . $langLabel . ' | NiangProgrammeur')
@section('meta_description', trans('app.quiz.result.got_score', [':score' => $score, ':total' => $total]))

@section('styles')
<style>
.qr-hero {
  padding-block-start: calc(var(--spacing-navbar, 76px) + 4rem) !important;
}
</style>
@endsection

@section('content')
<div class="qr-page">

  {{-- ── SCORE HERO ─────────────────────────────────────────── --}}
  <div class="qr-hero">
    <div class="qr-hero__inner">

      {{-- Ring SVG --}}
      <div class="qr-score-ring" aria-label="{{ $pct }}%">
        <svg viewBox="0 0 160 160" aria-hidden="true">
          <circle class="qr-score-ring__track" cx="80" cy="80" r="70"/>
          <circle class="qr-score-ring__fill qr-score-ring__fill--{{ $tier }}"
                  cx="80" cy="80" r="70"
                  id="ringFill"
                  stroke-dashoffset="{{ $ringCirc }}"/>
        </svg>
        <div class="qr-score-ring__center">
          <span class="qr-score-ring__pct">{{ $pct }}%</span>
          <span class="qr-score-ring__label">{{ trans('app.quiz.result.score') }}</span>
        </div>
      </div>

      {{-- Content --}}
      <div class="qr-hero__content">
        <div class="qr-hero__eyebrow qr-hero__eyebrow--{{ $tier }}">
          {{ $tierKey }}
        </div>
        <h1 class="qr-hero__title">
          {{ trans('app.quiz.title') }} {{ $langLabel }}
        </h1>
        <p class="qr-hero__sub">
          {{ trans('app.quiz.result.got_score', [':score' => $score, ':total' => $total]) }}
        </p>

        <div class="qr-actions">
          <a href="{{ route('quiz.language', $language) }}" class="qr-btn qr-btn--primary">
            <i class="fas fa-redo"></i>
            {{ trans('app.quiz.result.retry') }}
          </a>
          <a href="{{ route('quiz') }}" class="qr-btn qr-btn--ghost">
            <i class="fas fa-arrow-left"></i>
            {{ trans('app.quiz.result.back') }}
          </a>
          @php
            try { $exRoute = route('exercices.language', $language); } catch(\Exception $e) { $exRoute = route('exercices'); }
          @endphp
          <a href="{{ $exRoute }}" class="qr-btn qr-btn--ghost">
            <i class="fas fa-code"></i>
            {{ trans('app.quiz.result.do_exercices') }}
          </a>
        </div>
      </div>

    </div>
  </div>

  {{-- ── STAT CHIPS ──────────────────────────────────────────── --}}
  <div class="qr-stats">
    <div class="qr-stats__inner">
      <div class="qr-chip qr-chip--correct">
        <div class="qr-chip__icon"><i class="fas fa-check"></i></div>
        <div>
          <div class="qr-chip__val">{{ $score }}</div>
          <div class="qr-chip__lbl">{{ trans('app.quiz.result.correct_answers') }}</div>
        </div>
      </div>
      <div class="qr-chip qr-chip--wrong">
        <div class="qr-chip__icon"><i class="fas fa-times"></i></div>
        <div>
          <div class="qr-chip__val">{{ $wrong }}</div>
          <div class="qr-chip__lbl">{{ trans('app.quiz.result.wrong_answers') }}</div>
        </div>
      </div>
      <div class="qr-chip qr-chip--pct">
        <div class="qr-chip__icon"><i class="fas fa-chart-pie"></i></div>
        <div>
          <div class="qr-chip__val">{{ $pct }}%</div>
          <div class="qr-chip__lbl">{{ trans('app.quiz.result.score') }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── ANSWERS DETAIL ──────────────────────────────────────── --}}
  <div class="qr-main">
    <div class="qr-container">

      <h2 class="qr-section-title">
        <i class="fas fa-list-check"></i>
        {{ trans('app.quiz.result.details') }}
      </h2>

      @foreach($results as $index => $result)
      <div class="qr-answer qr-answer--{{ $result['isCorrect'] ? 'correct' : 'wrong' }}">
        <div class="qr-answer__header">

          <div class="qr-answer__icon">
            @if($result['isCorrect'])
              <i class="fas fa-check"></i>
            @else
              <i class="fas fa-times"></i>
            @endif
          </div>

          <div class="qr-answer__body">
            <div class="qr-answer__num">{{ trans('app.quiz.result.question') }} {{ $index + 1 }}</div>
            <p class="qr-answer__question">{{ $result['question'] }}</p>

            <div class="qr-answer__feedback">
              @if($result['isCorrect'])
                <div class="qr-answer__row qr-answer__row--correct">
                  <span class="qr-answer__row-label">{{ trans('app.quiz.result.good_answer') }}</span>
                  <span class="qr-answer__row-val">{{ $result['options'][$result['correctAnswer']] }}</span>
                </div>
              @else
                <div class="qr-answer__row qr-answer__row--wrong">
                  <span class="qr-answer__row-label">{{ trans('app.quiz.result.your_answer') }}</span>
                  <span class="qr-answer__row-val">{{ $result['options'][$result['userAnswer']] ?? trans('app.quiz.result.no_answer') }}</span>
                </div>
                <div class="qr-answer__row qr-answer__row--correct">
                  <span class="qr-answer__row-label">{{ trans('app.quiz.result.correct_answer') }}</span>
                  <span class="qr-answer__row-val">{{ $result['options'][$result['correctAnswer']] }}</span>
                </div>
              @endif
            </div>
          </div>

        </div>
      </div>
      @endforeach

      {{-- CTA bottom --}}
      <div class="qr-cta">
        <h3 class="qr-cta__title">{{ trans('app.quiz.result.continue_learning') }}</h3>
        <p class="qr-cta__sub">{{ trans('app.quiz.result.continue_learning_desc') }}</p>
        <div class="qr-cta__actions">
          <a href="{{ route('exercices') }}" class="qr-btn qr-btn--primary">
            <i class="fas fa-code"></i>
            {{ trans('app.exercices.title') }}
          </a>
          <a href="{{ route('quiz') }}" class="qr-btn qr-btn--ghost">
            <i class="fas fa-question-circle"></i>
            {{ trans('app.quiz.title') }}
          </a>
        </div>
      </div>

    </div>
  </div>

</div>

<script>
(function () {
  /* Animate the SVG ring on load */
  const fill   = document.getElementById('ringFill');
  const target = {{ $ringCirc }} - ({{ $ringCirc }} * {{ $pct }} / 100);
  requestAnimationFrame(function () {
    setTimeout(function () {
      fill.style.transition = 'stroke-dashoffset 1.2s cubic-bezier(.4,0,.2,1)';
      fill.style.strokeDashoffset = target;
    }, 120);
  });
})();

// Corriger l'URL (la vue est rendue depuis /submit, on affiche /result)
if (window.history && window.history.replaceState) {
  window.history.replaceState(null, '', '{{ route('quiz.result', $language) }}');
}
</script>
@endsection
