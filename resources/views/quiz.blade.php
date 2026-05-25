@extends('layouts.app')

@section('title', trans('app.quiz.title') . ' | NiangProgrammeur')
@section('meta_description', trans('app.quiz.subtitle'))
@section('meta_keywords', 'quiz programmation, QCM code, quiz HTML, quiz CSS, quiz JavaScript, quiz PHP')

@section('content')
<div class="qz-page">

  {{-- ── HERO ──────────────────────────────────────────────── --}}
  <section class="qz-hero">
    <div class="qz-hero__eyebrow">
      <span class="qz-hero__dot"></span>
      Teste tes connaissances
    </div>
    <h1 class="qz-hero__title">
      {{ trans('app.quiz.title') }}<br>
      <span>de programmation</span>
    </h1>
    <p class="qz-hero__subtitle">{{ trans('app.quiz.subtitle') }}</p>
  </section>

  {{-- ── STATS ─────────────────────────────────────────────── --}}
  <div class="qz-stats">
    <div class="qz-stats__grid">
      <div class="qz-stat-card">
        <div class="qz-stat-card__icon qz-stat-card__icon--purple">
          <i class="fas fa-brain"></i>
        </div>
        <div>
          <div class="qz-stat-card__value">{{ count($languages) }}</div>
          <div class="qz-stat-card__label">{{ trans('app.quiz.stats.languages') }}</div>
        </div>
      </div>
      <div class="qz-stat-card">
        <div class="qz-stat-card__icon qz-stat-card__icon--pink">
          <i class="fas fa-question-circle"></i>
        </div>
        <div>
          <div class="qz-stat-card__value">{{ array_sum(array_column($languages, 'questions')) }}+</div>
          <div class="qz-stat-card__label">{{ trans('app.quiz.stats.questions') }}</div>
        </div>
      </div>
      <div class="qz-stat-card">
        <div class="qz-stat-card__icon qz-stat-card__icon--blue">
          <i class="fas fa-list-ol"></i>
        </div>
        <div>
          <div class="qz-stat-card__value">20</div>
          <div class="qz-stat-card__label">{{ trans('app.quiz.stats.questions_per_quiz') }}</div>
        </div>
      </div>
      <div class="qz-stat-card">
        <div class="qz-stat-card__icon qz-stat-card__icon--green">
          <i class="fas fa-gift"></i>
        </div>
        <div>
          <div class="qz-stat-card__value">100%</div>
          <div class="qz-stat-card__label">{{ trans('app.quiz.stats.free') }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── GRILLE DES LANGAGES ───────────────────────────────── --}}
  <section class="qz-main">
    <div class="qz-container">
      <p class="qz-section-label">{{ count($languages) }} langages disponibles</p>

      @php
        $colorMap = [
          'orange' => 'orange',
          'blue'   => 'blue',
          'yellow' => 'yellow',
          'purple' => 'purple',
          'red'    => 'red',
          'green'  => 'green',
          'gray'   => 'gray',
          'black'  => 'black',
          'cyan'   => 'cyan',
        ];
      @endphp

      <div class="qz-grid">
        @foreach($languages as $lang)
        <a href="{{ route('quiz.language', $lang['slug']) }}" class="qz-card">
          <div class="qz-card__icon-wrap qz-card__icon-wrap--{{ $colorMap[$lang['color']] ?? 'purple' }}">
            <i class="{{ $lang['icon'] }}"></i>
          </div>

          <div class="qz-card__name">{{ $lang['name'] }}</div>

          <div class="qz-card__count">
            <i class="fas fa-question-circle"></i>
            {{ $lang['questions'] }} {{ trans('app.quiz.questions_count') }}
          </div>

          <div class="qz-card__meta">
            <span class="qz-badge qz-badge--purple">QCM</span>
            <span class="qz-badge qz-badge--pink">20 min</span>
          </div>

          <div class="qz-card__cta">
            {{ trans('app.quiz.start_quiz') }}
            <i class="fas fa-arrow-right"></i>
          </div>
        </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── CTA ──────────────────────────────────────────────── --}}
  <section class="qz-cta">
    <div class="qz-cta__box">
      <div class="qz-cta__icon"><i class="fas fa-trophy"></i></div>
      <h2 class="qz-cta__title">{{ trans('app.quiz.cta.title') }}</h2>
      <p class="qz-cta__subtitle">{{ trans('app.quiz.cta.description') }}</p>
      <div class="qz-cta__actions">
        <a href="{{ route('exercices') }}" class="qz-btn qz-btn--primary">
          <i class="fas fa-code"></i>
          {{ trans('app.quiz.cta.see_exercices') }}
        </a>
        <a href="{{ route('about') }}" class="qz-btn qz-btn--ghost">
          <i class="fas fa-info-circle"></i>
          {{ trans('app.quiz.cta.learn_more') }}
        </a>
      </div>
    </div>
  </section>

</div>
@endsection
