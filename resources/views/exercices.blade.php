@extends('layouts.app')

@section('title', trans('app.exercices.title') . ' | NiangProgrammeur')
@section('meta_description', trans('app.exercices.subtitle'))
@section('meta_keywords', 'exercices programmation, pratique code, exercices HTML, exercices CSS, exercices JavaScript, exercices PHP')

@section('content')
<div class="ex-page">

  {{-- ── HERO ──────────────────────────────────────────────── --}}
  <section class="ex-hero">
    <div class="ex-hero__eyebrow">
      <span class="ex-hero__dot"></span>
      Pratique &amp; progression
    </div>
    <h1 class="ex-hero__title">
      {{ trans('app.exercices.title') }}<br>
      <span>de programmation</span>
    </h1>
    <p class="ex-hero__subtitle">{{ trans('app.exercices.subtitle') }}</p>
  </section>

  {{-- ── STATS ─────────────────────────────────────────────── --}}
  <div class="ex-stats">
    <div class="ex-stats__grid">
      <div class="ex-stat-card">
        <div class="ex-stat-card__icon ex-stat-card__icon--cyan">
          <i class="fas fa-code"></i>
        </div>
        <div>
          <div class="ex-stat-card__value">{{ count($languages) }}</div>
          <div class="ex-stat-card__label">{{ trans('app.exercices.choose_language') }}</div>
        </div>
      </div>
      <div class="ex-stat-card">
        <div class="ex-stat-card__icon ex-stat-card__icon--green">
          <i class="fas fa-dumbbell"></i>
        </div>
        <div>
          <div class="ex-stat-card__value">{{ array_sum(array_column($languages, 'exercises')) }}+</div>
          <div class="ex-stat-card__label">{{ trans('app.exercices.stats.exercises') }}</div>
        </div>
      </div>
      <div class="ex-stat-card">
        <div class="ex-stat-card__icon ex-stat-card__icon--purple">
          <i class="fas fa-layer-group"></i>
        </div>
        <div>
          <div class="ex-stat-card__value">3</div>
          <div class="ex-stat-card__label">{{ trans('app.exercices.stats.levels') }}</div>
        </div>
      </div>
      <div class="ex-stat-card">
        <div class="ex-stat-card__icon ex-stat-card__icon--orange">
          <i class="fas fa-gift"></i>
        </div>
        <div>
          <div class="ex-stat-card__value">100%</div>
          <div class="ex-stat-card__label">{{ trans('app.exercices.stats.free') }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── GRILLE DES LANGAGES ───────────────────────────────── --}}
  <section class="ex-main">
    <div class="ex-container">
      <p class="ex-section-label">{{ count($languages) }} langages disponibles</p>

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
        ];
      @endphp

      <div class="ex-grid">
        @foreach($languages as $lang)
        <a href="{{ route('exercices.language', $lang['slug']) }}" class="ex-card">
          <div class="ex-card__icon-wrap ex-card__icon-wrap--{{ $colorMap[$lang['color']] ?? 'cyan' }}">
            <i class="{{ $lang['icon'] }}"></i>
          </div>

          <div class="ex-card__name">{{ $lang['name'] }}</div>

          <div class="ex-card__count">
            <i class="fas fa-dumbbell"></i>
            {{ $lang['exercises'] }} {{ trans('app.exercices.exercises_count') }}
          </div>

          <div class="ex-card__levels">
            <span class="ex-badge ex-badge--easy">{{ trans('app.exercices.difficulty.easy') }}</span>
            <span class="ex-badge ex-badge--medium">{{ trans('app.exercices.difficulty.medium') }}</span>
            <span class="ex-badge ex-badge--hard">{{ trans('app.exercices.difficulty.hard') }}</span>
          </div>

          <div class="ex-card__cta">
            {{ trans('app.exercices.start_exercise') }}
            <i class="fas fa-arrow-right"></i>
          </div>
        </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── CTA ──────────────────────────────────────────────── --}}
  <section class="ex-cta">
    <div class="ex-cta__box">
      <div class="ex-cta__icon"><i class="fas fa-trophy"></i></div>
      <h2 class="ex-cta__title">{{ trans('app.exercices.cta.title') }}</h2>
      <p class="ex-cta__subtitle">{{ trans('app.exercices.cta.description') }}</p>
      <div class="ex-cta__actions">
        <a href="{{ route('about') }}" class="ex-btn ex-btn--primary">
          <i class="fas fa-info-circle"></i>
          {{ trans('app.exercices.cta.learn_more') }}
        </a>
        <a href="{{ route('contact') }}" class="ex-btn ex-btn--ghost">
          <i class="fas fa-envelope"></i>
          {{ trans('app.exercices.cta.contact') }}
        </a>
      </div>
    </div>
  </section>

</div>
@endsection
