@extends('layouts.app')

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
  $info = $langMap[$language] ?? ['icon' => 'fas fa-code', 'color' => 'cyan'];
  $colorMap = [
    'orange' => ['bg' => '#fff7ed', 'color' => '#ea580c'],
    'blue'   => ['bg' => '#eff6ff', 'color' => '#2563eb'],
    'yellow' => ['bg' => '#fefce8', 'color' => '#ca8a04'],
    'cyan'   => ['bg' => '#ecfeff', 'color' => '#0891b2'],
    'red'    => ['bg' => '#fff1f2', 'color' => '#dc2626'],
    'green'  => ['bg' => '#f0fdf4', 'color' => '#16a34a'],
    'gray'   => ['bg' => '#f8fafc', 'color' => '#475569'],
  ];
  $c = $colorMap[$info['color']] ?? $colorMap['cyan'];
  $langLabel = trans('app.formations.languages.' . $language, [], null, ucfirst($language));
@endphp

@section('title', trans('app.quiz.title') . ' ' . $langLabel . ' | NiangProgrammeur')
@section('meta_description', trans('app.quiz.answer_questions', [':count' => count($questions)]))

@section('styles')
<style>
.ql-header {
  padding-block-start: calc(var(--spacing-navbar, 76px) + 2.5rem) !important;
}
</style>
@endsection

@section('content')
<div class="ql-page">

  {{-- ── HEADER ────────────────────────────────────────────── --}}
  <div class="ql-header">
    <div class="ql-header__inner">

      <a href="{{ route('quiz') }}" class="ql-back">
        <i class="fas fa-arrow-left"></i>
        {{ trans('app.quiz.back_to_quiz') }}
      </a>

      <div class="ql-header__top">
        <div class="ql-lang-icon" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};border-color:{{ $c['bg'] }}">
          <i class="{{ $info['icon'] }}"></i>
        </div>
        <div class="ql-header__info">
          <h1 class="ql-title">
            {{ trans('app.quiz.title') }}
            <span>{{ $langLabel }}</span>
          </h1>
          <div class="ql-meta">
            <span class="ql-meta-item">
              <i class="fas fa-question-circle"></i>
              {{ count($questions) }} {{ trans('app.quiz.questions_count') }}
            </span>
            <span class="ql-meta-item">
              <i class="fas fa-clock"></i>
              20 min
            </span>
            <span class="ql-meta-item">
              <i class="fas fa-list-check"></i>
              QCM
            </span>
          </div>
        </div>
      </div>

      {{-- Progress bar --}}
      <div class="ql-progress-wrap">
        <div class="ql-progress__top">
          <span class="ql-progress__label">{{ trans('app.exercices.progress') }}</span>
          <span class="ql-progress__count" id="progressText">0 / {{ count($questions) }}</span>
        </div>
        <div class="ql-progress__track">
          <div class="ql-progress__fill" id="progressFill"></div>
        </div>
      </div>

    </div>
  </div>

  {{-- ── FORM ──────────────────────────────────────────────── --}}
  <div class="ql-main">
    <div class="ql-container">

      <form id="quizForm" action="{{ route('quiz.submit', $language) }}" method="POST">
        @csrf

        @foreach($questions as $index => $question)
        <div class="ql-question" id="question-{{ $index }}">
          <div class="ql-question__header">
            <div class="ql-question__num">{{ $index + 1 }}</div>
            <p class="ql-question__text">{{ $question['question'] }}</p>
          </div>

          <div class="ql-options">
            @foreach($question['options'] as $optIndex => $option)
            <div>
              <input type="radio"
                     name="answers[{{ $index }}]"
                     value="{{ $optIndex }}"
                     id="q{{ $index }}_opt{{ $optIndex }}"
                     class="ql-option__input"
                     onchange="handleAnswer({{ $index }}, this)">
              <label for="q{{ $index }}_opt{{ $optIndex }}" class="ql-option__label">
                <span class="ql-option__letter">{{ chr(65 + $optIndex) }}</span>
                <span class="ql-option__text">{{ $option }}</span>
              </label>
            </div>
            @endforeach
          </div>
        </div>
        @endforeach

        {{-- Submit zone --}}
        <div class="ql-submit-zone">
          <p class="ql-submit-zone__hint">
            <strong id="answeredCount">0</strong> / {{ count($questions) }}
            {{ trans('app.quiz.questions_count') }} répondues
          </p>
          <button type="submit" class="ql-btn-submit">
            <i class="fas fa-paper-plane"></i>
            {{ trans('app.quiz.submit_quiz') }}
          </button>
        </div>

      </form>

    </div>
  </div>

</div>

<script>
(function () {
  const total = {{ count($questions) }};
  let answered = 0;

  function updateProgress() {
    const pct = (answered / total) * 100;
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressText').textContent = answered + ' / ' + total;
    document.getElementById('answeredCount').textContent = answered;
  }

  window.handleAnswer = function (questionIndex, radio) {
    const card = document.getElementById('question-' + questionIndex);
    if (!card.classList.contains('is-answered')) {
      card.classList.add('is-answered');
      answered++;
      updateProgress();
    }
  };

  document.getElementById('quizForm').addEventListener('submit', function (e) {
    const checked = document.querySelectorAll('input[type="radio"]:checked').length;
    if (checked < total) {
      e.preventDefault();
      alert(@json(trans('app.quiz.answer_all')));
    }
  });

  window.scrollTo(0, 0);
})();
</script>
@endsection
