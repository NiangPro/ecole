@extends('layouts.app')

@php
  $langMap = [
    'html5'        => ['icon' => 'fab fa-html5',       'color' => 'orange', 'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'css3'         => ['icon' => 'fab fa-css3-alt',    'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'javascript'   => ['icon' => 'fab fa-js',           'color' => 'yellow', 'bg' => '#fefce8', 'fg' => '#ca8a04'],
    'php'          => ['icon' => 'fab fa-php',          'color' => 'cyan',   'bg' => '#ecfeff', 'fg' => '#0891b2'],
    'bootstrap'    => ['icon' => 'fab fa-bootstrap',   'color' => 'cyan',   'bg' => '#ecfeff', 'fg' => '#0891b2'],
    'git'          => ['icon' => 'fab fa-git-alt',     'color' => 'red',    'bg' => '#fff1f2', 'fg' => '#dc2626'],
    'wordpress'    => ['icon' => 'fab fa-wordpress',   'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'ia'           => ['icon' => 'fas fa-robot',        'color' => 'green',  'bg' => '#f0fdf4', 'fg' => '#16a34a'],
    'python'       => ['icon' => 'fab fa-python',       'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'java'         => ['icon' => 'fab fa-java',         'color' => 'orange', 'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'sql'          => ['icon' => 'fas fa-database',    'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'c'            => ['icon' => 'fab fa-c',            'color' => 'gray',   'bg' => '#f8fafc', 'fg' => '#475569'],
    'cpp'          => ['icon' => 'fab fa-cuttlefish',  'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'csharp'       => ['icon' => 'fab fa-microsoft',   'color' => 'green',  'bg' => '#f0fdf4', 'fg' => '#16a34a'],
    'dart'         => ['icon' => 'fas fa-feather-alt', 'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'go'           => ['icon' => 'fab fa-golang',      'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'swift'        => ['icon' => 'fab fa-swift',       'color' => 'orange', 'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'perl'         => ['icon' => 'fas fa-code',        'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'typescript'   => ['icon' => 'fab fa-js-square',   'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'rust'         => ['icon' => 'fab fa-rust',        'color' => 'gray',   'bg' => '#f8fafc', 'fg' => '#475569'],
    'ruby'         => ['icon' => 'fas fa-gem',         'color' => 'red',    'bg' => '#fff1f2', 'fg' => '#dc2626'],
    'cybersecurite'=> ['icon' => 'fas fa-shield-alt',  'color' => 'orange', 'bg' => '#fff7ed', 'fg' => '#ea580c'],
    'data-science' => ['icon' => 'fas fa-chart-line',  'color' => 'blue',   'bg' => '#eff6ff', 'fg' => '#2563eb'],
    'big-data'     => ['icon' => 'fas fa-database',    'color' => 'cyan',   'bg' => '#ecfeff', 'fg' => '#0891b2'],
  ];
  $info = $langMap[$language] ?? ['icon' => 'fas fa-code', 'color' => 'cyan', 'bg' => '#ecfeff', 'fg' => '#0891b2'];

  $easyKey   = trans('app.exercices.difficulty.easy');
  $mediumKey = trans('app.exercices.difficulty.medium');
  $hardKey   = trans('app.exercices.difficulty.hard');

  $easyCount   = collect($exercises)->filter(fn($e) => $e['difficulty'] === $easyKey)->count();
  $mediumCount = collect($exercises)->filter(fn($e) => $e['difficulty'] === $mediumKey)->count();
  $hardCount   = collect($exercises)->filter(fn($e) => $e['difficulty'] === $hardKey)->count();
  $totalPoints = array_sum(array_column($exercises, 'points'));

  $langLabel = trans('app.formations.languages.' . $language, [], null, ucfirst($language));
@endphp

@section('title', trans('app.exercices.exercises') . ' ' . $langLabel . ' | NiangProgrammeur')
@section('meta_description', trans('app.exercices.practice_skills'))

@section('content')
<div class="el-page">

  {{-- ── HEADER ────────────────────────────────────────────── --}}
  <div class="el-header">
    <div class="el-header__inner">

      <a href="{{ route('exercices') }}" class="el-back">
        <i class="fas fa-arrow-left"></i>
        {{ trans('app.exercices.back_to_exercices') }}
      </a>

      <div class="el-header__top">
        <div class="el-lang-icon" style="background:{{ $info['bg'] }};color:{{ $info['fg'] }};border-color:{{ $info['bg'] }}">
          <i class="{{ $info['icon'] }}"></i>
        </div>
        <div class="el-header__info">
          <h1 class="el-title">
            {{ trans('app.exercices.exercises') }}
            <span>{{ $langLabel }}</span>
          </h1>
          <p class="el-subtitle">{{ trans('app.exercices.practice_skills') }}</p>
        </div>
      </div>

      <div class="el-chips">
        <div class="el-chip el-chip--total">
          <div class="el-chip__icon"><i class="fas fa-dumbbell"></i></div>
          <div>
            <div class="el-chip__val">{{ count($exercises) }}</div>
            <div class="el-chip__lbl">{{ trans('app.exercices.exercises') }}</div>
          </div>
        </div>
        <div class="el-chip el-chip--easy">
          <div class="el-chip__icon"><i class="fas fa-seedling"></i></div>
          <div>
            <div class="el-chip__val">{{ $easyCount }}</div>
            <div class="el-chip__lbl">{{ $easyKey }}</div>
          </div>
        </div>
        <div class="el-chip el-chip--medium">
          <div class="el-chip__icon"><i class="fas fa-bolt"></i></div>
          <div>
            <div class="el-chip__val">{{ $mediumCount }}</div>
            <div class="el-chip__lbl">{{ $mediumKey }}</div>
          </div>
        </div>
        <div class="el-chip el-chip--hard">
          <div class="el-chip__icon"><i class="fas fa-fire"></i></div>
          <div>
            <div class="el-chip__val">{{ $hardCount }}</div>
            <div class="el-chip__lbl">{{ $hardKey }}</div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @if(count($exercises) > 0)

  {{-- ── TOOLBAR ───────────────────────────────────────────── --}}
  <div class="el-toolbar">
    <div class="el-toolbar__inner">
      <span class="el-toolbar__label">{{ trans('app.exercices.filter_by_level') }}</span>

      <button class="el-filter is-active" data-filter="all" onclick="elFilter(this,'all')">
        <i class="fas fa-border-all"></i>
        {{ trans('app.exercices.difficulty.all') }} ({{ count($exercises) }})
      </button>
      <button class="el-filter el-filter--easy" data-filter="{{ $easyKey }}" onclick="elFilter(this,'{{ $easyKey }}')">
        <i class="fas fa-seedling"></i>
        {{ $easyKey }} ({{ $easyCount }})
      </button>
      <button class="el-filter el-filter--medium" data-filter="{{ $mediumKey }}" onclick="elFilter(this,'{{ $mediumKey }}')">
        <i class="fas fa-bolt"></i>
        {{ $mediumKey }} ({{ $mediumCount }})
      </button>
      <button class="el-filter el-filter--hard" data-filter="{{ $hardKey }}" onclick="elFilter(this,'{{ $hardKey }}')">
        <i class="fas fa-fire"></i>
        {{ $hardKey }} ({{ $hardCount }})
      </button>
    </div>
  </div>

  {{-- ── EXERCISES LIST ────────────────────────────────────── --}}
  <div class="el-main">
    <div class="el-container">

      <div id="elList">
        @foreach($exercises as $index => $exercise)
        @php
          $exId       = $exercise['original_index'] ?? ($exercise['display_index'] ?? ($index + 1));
          $progress   = $exerciseProgress->get($exId) ?? null;
          $isDone     = $progress && $progress->completed;
          $diffLower  = strtolower($exercise['difficulty'] ?? '');
          if (in_array($diffLower, ['facile','easy']))           $diffClass = 'easy';
          elseif (in_array($diffLower, ['moyen','medium','intermédiaire'])) $diffClass = 'medium';
          else                                                    $diffClass = 'hard';
          $exDisplay  = $exercise['display_index'] ?? ($index + 1);
          $exRoute    = route('exercices.detail', [$language, $exDisplay]);
        @endphp

        <a href="{{ $exRoute }}"
           class="el-exercise{{ $isDone ? ' is-done' : '' }}"
           data-difficulty="{{ $exercise['difficulty'] }}">

          {{-- Number --}}
          <div class="el-exercise__num">{{ $exDisplay }}</div>

          {{-- Body --}}
          <div class="el-exercise__body">
            <div class="el-exercise__title">
              {{ $exercise['title'] }}
              @if($isDone)
                &nbsp;<small style="font-size:.72rem;font-weight:700;color:oklch(50% 0.18 145)">— {{ trans('app.profile.dashboard.exercices.completed') }}</small>
              @endif
            </div>
            <div class="el-exercise__meta">
              <span class="el-diff-badge el-diff-badge--{{ $diffClass }}">
                <i class="fas fa-signal"></i>
                {{ $exercise['difficulty'] }}
              </span>
              <span class="el-exercise__points">
                <i class="fas fa-star"></i>
                {{ $exercise['points'] }} {{ trans('app.exercices.points') }}
              </span>
              @if($isDone && $progress->score)
              <span class="el-exercise__score">
                <i class="fas fa-trophy"></i>
                {{ $progress->score }}%
              </span>
              @endif
            </div>
          </div>

          {{-- Check icon if done --}}
          @if($isDone)
          <div class="el-exercise__check"><i class="fas fa-check"></i></div>
          @endif

          {{-- CTA --}}
          <span class="el-exercise__cta {{ $isDone ? 'el-exercise__cta--review' : 'el-exercise__cta--start' }}">
            <i class="fas {{ $isDone ? 'fa-eye' : 'fa-play' }}"></i>
            {{ $isDone ? trans('app.exercices.review_exercise') : trans('app.exercices.start_exercise') }}
          </span>

        </a>
        @endforeach
      </div>

      {{-- Tips --}}
      <div class="el-tips">
        <h3 class="el-tips__title">
          <i class="fas fa-lightbulb"></i>
          {{ trans('app.exercices.tips.title') }}
        </h3>
        <ul class="el-tips__list">
          <li class="el-tips__item"><i class="fas fa-check-circle"></i><span>{{ trans('app.exercices.tips.read_carefully') }}</span></li>
          <li class="el-tips__item"><i class="fas fa-check-circle"></i><span>{{ trans('app.exercices.tips.test_regularly') }}</span></li>
          <li class="el-tips__item"><i class="fas fa-check-circle"></i><span>{{ trans('app.exercices.tips.check_docs') }}</span></li>
          <li class="el-tips__item"><i class="fas fa-check-circle"></i><span>{{ trans('app.exercices.tips.take_time') }}</span></li>
        </ul>
      </div>

    </div>
  </div>

  @else

  {{-- ── EMPTY STATE ───────────────────────────────────────── --}}
  <div class="el-main">
    <div class="el-container">
      <div class="el-empty">
        <div class="el-empty__icon"><i class="fas fa-dumbbell"></i></div>
        <h3 class="el-empty__title">{{ trans('app.exercices.coming_soon') }}</h3>
        <p class="el-empty__sub">{{ str_replace(':language', ucfirst($language), trans('app.exercices.coming_soon_desc')) }}</p>
        <a href="{{ route('exercices') }}" class="el-exercise__cta el-exercise__cta--start" style="display:inline-flex;margin-inline:auto">
          <i class="fas fa-arrow-left"></i>
          {{ trans('app.exercices.back_to_exercices') }}
        </a>
      </div>
    </div>
  </div>

  @endif

</div>

<script>
(function () {
  function elFilter(btn, difficulty) {
    document.querySelectorAll('.el-filter').forEach(b => b.classList.remove('is-active'));
    btn.classList.add('is-active');

    document.querySelectorAll('#elList .el-exercise').forEach(function (card) {
      if (difficulty === 'all' || card.dataset.difficulty === difficulty) {
        card.classList.remove('is-hidden');
      } else {
        card.classList.add('is-hidden');
      }
    });
  }

  window.elFilter = elFilter;
})();
</script>
@endsection
