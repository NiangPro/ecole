@extends('layouts.app')

@section('title', trans('app.about.meta.title'))
@section('meta_description', trans('app.about.meta.description'))
@section('meta_keywords', trans('app.about.meta.keywords'))

@section('content')
<div class="ab-page">

  {{-- ── HERO ─────────────────────────────────────────────── --}}
  <section class="ab-hero">
    <div class="ab-hero__container">

      {{-- Left: text --}}
      <div>
        <span class="ab-hero__eyebrow">
          <span class="ab-hero__eyebrow-dot"></span>
          {{ trans('app.about.badge') }}
        </span>

        <h1 class="ab-hero__name">
          {{ trans('app.about.hero.greeting') }}<br>
          <span class="ab-hero__name-gradient">{{ trans('app.about.hero.name') }}</span>
        </h1>

        <p class="ab-hero__role">
          {{ trans('app.about.hero.brand') }}<br>
          <span style="font-size:.9em">{{ trans('app.about.hero.subtitle') }}</span>
        </p>

        <div class="ab-hero__stats">
          <div class="ab-stat">
            <span class="ab-stat__value">{{ number_format(\Carbon\Carbon::parse('2020-01-01')->diffInYears(), 0) }}+</span>
            <span class="ab-stat__label">{{ trans('app.about.experience.stats.years') }}</span>
          </div>
          <div class="ab-stat">
            <span class="ab-stat__value">120+</span>
            <span class="ab-stat__label">{{ trans('app.about.experience.stats.projects') }}</span>
          </div>
          <div class="ab-stat">
            <span class="ab-stat__value">300+</span>
            <span class="ab-stat__label">{{ trans('app.about.experience.stats.trained') }}</span>
          </div>
        </div>

        <div class="ab-hero__actions">
          <a href="{{ route('home') }}" class="ab-btn ab-btn--primary">
            <i class="fas fa-home"></i>
            {{ trans('app.about.cta.back_home') }}
          </a>
          <a href="mailto:{{ optional($siteSettings)->contact_email ?? 'NiangProgrammeur@gmail.com' }}" class="ab-btn ab-btn--ghost">
            <i class="fas fa-envelope"></i>
            {{ trans('app.about.contact.email') }}
          </a>
        </div>
      </div>

      {{-- Right: photo --}}
      <div class="ab-hero__photo-wrap">
        <div class="ab-hero__photo-ring">
          <img src="{{ asset('images/about.jpg') }}" alt="Bassirou Niang" class="ab-hero__photo" width="400" height="400" loading="lazy" decoding="async">
        </div>
        <span class="ab-hero__badge">
          <span class="ab-hero__badge-dot"></span>
          Disponible
        </span>
        <div class="ab-hero__float ab-hero__float--1"><i class="fab fa-html5"></i></div>
        <div class="ab-hero__float ab-hero__float--2"><i class="fab fa-js"></i></div>
        <div class="ab-hero__float ab-hero__float--3"><i class="fab fa-laravel"></i></div>
        <div class="ab-hero__float ab-hero__float--4"><i class="fab fa-react"></i></div>
      </div>

    </div>
  </section>

  {{-- ── STORY TIMELINE ───────────────────────────────────── --}}
  <section class="ab-section ab-section--alt">
    <div class="ab-container">
      <div class="ab-section-header">
        <span class="ab-eyebrow">Mon parcours</span>
        <h2 class="ab-heading ab-heading--gradient">{{ trans('app.about.title') }}</h2>
        <p class="ab-subtext">{{ trans('app.about.hero.subtitle') }}</p>
      </div>

      <div class="ab-timeline">
        @php
          $events = [
            ['icon' => 'fas fa-seedling',      'key' => 'beginnings'],
            ['icon' => 'fas fa-graduation-cap','key' => 'education'],
            ['icon' => 'fas fa-code',          'key' => 'experience'],
            ['icon' => 'fas fa-briefcase',     'key' => 'sunucode'],
            ['icon' => 'fas fa-heart',         'key' => 'niangprogrammeur'],
          ];
        @endphp
        @foreach($events as $ev)
        <div class="ab-timeline__item">
          <div class="ab-timeline__card">
            <div class="ab-timeline__icon"><i class="{{ $ev['icon'] }}"></i></div>
            <h3 class="ab-timeline__title">{{ trans('app.about.timeline.'.$ev['key'].'.title') }}</h3>
            <p class="ab-timeline__text">{{ trans('app.about.timeline.'.$ev['key'].'.text') }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── SKILLS ───────────────────────────────────────────── --}}
  <section class="ab-section">
    <div class="ab-container">
      <div class="ab-section-header">
        <span class="ab-eyebrow">Compétences</span>
        <h2 class="ab-heading ab-heading--gradient">{{ trans('app.about.skills.title') }}</h2>
        <p class="ab-subtext">{{ trans('app.about.skills.subtitle') }}</p>
      </div>

      @php
        $skills = [
          ['name' => 'HTML5 & CSS3',             'level' => 95, 'icon' => 'fab fa-html5'],
          ['name' => 'JavaScript & TypeScript',   'level' => 90, 'icon' => 'fab fa-js'],
          ['name' => 'PHP & Laravel',             'level' => 88, 'icon' => 'fab fa-php'],
          ['name' => 'React & Vue.js',            'level' => 85, 'icon' => 'fab fa-react'],
          ['name' => 'React Native',              'level' => 83, 'icon' => 'fab fa-react'],
          ['name' => 'Node.js & Express',         'level' => 82, 'icon' => 'fab fa-node'],
          ['name' => 'Java',                      'level' => 80, 'icon' => 'fab fa-java'],
          ['name' => 'Git & GitHub',              'level' => 92, 'icon' => 'fab fa-git-alt'],
          ['name' => 'WordPress',                 'level' => 87, 'icon' => 'fab fa-wordpress'],
          ['name' => 'Intelligence Artificielle', 'level' => 75, 'icon' => 'fas fa-brain'],
          ['name' => 'Python',                    'level' => 80, 'icon' => 'fab fa-python'],
          ['name' => 'Flutter',                   'level' => 78, 'icon' => null, 'flutter' => true],
        ];
      @endphp

      <div class="ab-skills-grid">
        @foreach($skills as $sk)
        <div class="ab-skill">
          <div class="ab-skill__top">
            <div class="ab-skill__icon">
              @if(!empty($sk['flutter']))
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18.5 2L4 16l5 5L28.5 2H18.5z" fill="#54C5F8"/>
                  <path d="M18.5 2L9 11.5 14 16l14.5-14H18.5z" fill="#01579B"/>
                  <path d="M9 20.5L14 16l5 5-5 5-5-5z" fill="#01579B"/>
                  <path d="M14 21l5-5 5 5-5 5-5-5z" fill="#29B6F6"/>
                </svg>
              @else
                <i class="{{ $sk['icon'] }}"></i>
              @endif
            </div>
            <div>
              <div class="ab-skill__name">{{ $sk['name'] }}</div>
              <div class="ab-skill__pct">{{ $sk['level'] }}%</div>
            </div>
          </div>
          <div class="ab-skill__bar-track">
            <div class="ab-skill__bar-fill" style="width:{{ $sk['level'] }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── EDUCATION ────────────────────────────────────────── --}}
  <section class="ab-section ab-section--alt">
    <div class="ab-container">
      <div class="ab-section-header">
        <span class="ab-eyebrow">Formation</span>
        <h2 class="ab-heading ab-heading--gradient">{{ trans('app.about.formations.title') }}</h2>
        <p class="ab-subtext">{{ trans('app.about.formations.subtitle') }}</p>
      </div>

      @php
        $formations = [
          [
            'school'  => 'Access Code School',
            'degree'  => 'Spécialisation Développeur Web & Mobile',
            'period'  => '2019 – 2020',
            'icon'    => 'fas fa-code',
            'image'   => 'https://cdn-s-www.estrepublicain.fr/images/0285FB16-360C-4210-92C8-B4FA09471706/NW_raw/au-senegal-la-jeune-generation-est-rompue-aux-metiers-du-numerique-elle-apprend-tres-vite-1669215275.jpg',
          ],
          [
            'school'  => 'Institut Supérieur D\'Informatique',
            'degree'  => 'Licence Professionnelle Génie Logiciel',
            'period'  => '2016 – 2019',
            'icon'    => 'fas fa-graduation-cap',
            'image'   => 'https://www.groupeisi.com/wp-content/uploads/2018/12/ISI-KM-2-1024x683.jpg',
          ],
          [
            'school'  => 'Lycée Seydina Limamou Laye',
            'degree'  => 'Baccalauréat Scientifique S2',
            'period'  => '2013 – 2016',
            'icon'    => 'fas fa-school',
            'image'   => 'https://www.ecolesausenegal.org/uploads/articles/images/1599178281.jpg',
          ],
        ];
      @endphp

      <div class="ab-edu-grid">
        @foreach($formations as $f)
        <div class="ab-edu-card">
          <div class="ab-edu-card__img-wrap">
            <img src="{{ $f['image'] }}" alt="{{ $f['school'] }}" class="ab-edu-card__img" loading="lazy"
                 onerror="this.style.display='none'">
            <div class="ab-edu-card__img-overlay"></div>
            <div class="ab-edu-card__badge"><i class="{{ $f['icon'] }}"></i></div>
          </div>
          <div class="ab-edu-card__body">
            <div class="ab-edu-card__period"><i class="fas fa-calendar-alt"></i> {{ $f['period'] }}</div>
            <h3 class="ab-edu-card__school">{{ $f['school'] }}</h3>
            <p class="ab-edu-card__degree">{{ $f['degree'] }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── EXPERIENCE ───────────────────────────────────────── --}}
  <section class="ab-section">
    <div class="ab-container">
      <div class="ab-section-header">
        <span class="ab-eyebrow">Expérience</span>
        <h2 class="ab-heading ab-heading--gradient">{{ trans('app.about.experience.title') }}</h2>
        <p class="ab-subtext">{{ trans('app.about.experience.subtitle') }}</p>
      </div>

      <div class="ab-xp-card">
        <div class="ab-xp-card__header">
          <div class="ab-xp-card__logo"><i class="fas fa-building"></i></div>
          <div class="ab-xp-card__info">
            <div class="ab-xp-card__company">Sunucode</div>
            <a href="https://sunucode.com/" target="_blank" rel="noopener" class="ab-xp-card__link">
              <i class="fas fa-external-link-alt"></i> sunucode.com
            </a>
          </div>
          <div class="ab-xp-card__tags">
            <span class="ab-tag ab-tag--green">
              <i class="fas fa-circle" style="font-size:.5rem"></i>
              {{ trans('app.about.experience.active') }}
            </span>
            <span class="ab-tag ab-tag--cyan">
              <i class="fas fa-clock"></i>
              {{ trans('app.about.experience.fulltime') }}
            </span>
          </div>
        </div>

        <div class="ab-xp-card__body">
          <div class="ab-xp-card__role">
            <i class="fas fa-code"></i>
            {{ trans('app.about.experience.role') }}
          </div>
          <div class="ab-xp-card__period">
            <i class="fas fa-calendar-alt"></i>
            <span>{{ trans('app.about.experience.period') }}</span>
            <span>•</span>
            <span class="ab-xp-card__period-duration">
              {{ \Carbon\Carbon::parse('2020-01-01')->diffForHumans(null, true) }}
            </span>
          </div>

          <div class="ab-xp-achievements">
            @php
              $xpKeys = ['fullstack','training','management','ecommerce','seo'];
            @endphp
            @foreach($xpKeys as $k)
            <div class="ab-xp-achievement">
              <div class="ab-xp-achievement__icon"><i class="fas fa-check-circle"></i></div>
              <div>
                <div class="ab-xp-achievement__title">{{ trans('app.about.experience.achievements.'.$k.'.title') }}</div>
                <p class="ab-xp-achievement__desc">{{ trans('app.about.experience.achievements.'.$k.'.description') }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        <div class="ab-xp-card__footer">
          <div class="ab-xp-stat">
            <i class="fas fa-calendar-check"></i>
            <span class="ab-xp-stat__value">{{ number_format(\Carbon\Carbon::parse('2020-01-01')->diffInDays() / 365.25, 1) }}</span>
            <span class="ab-xp-stat__label">{{ trans('app.about.experience.stats.years') }}</span>
          </div>
          <div class="ab-xp-stat">
            <i class="fas fa-project-diagram"></i>
            <span class="ab-xp-stat__value">120+</span>
            <span class="ab-xp-stat__label">{{ trans('app.about.experience.stats.projects') }}</span>
          </div>
          <div class="ab-xp-stat">
            <i class="fas fa-users"></i>
            <span class="ab-xp-stat__value">300+</span>
            <span class="ab-xp-stat__label">{{ trans('app.about.experience.stats.trained') }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ── ACHIEVEMENTS ─────────────────────────────────────── --}}
  @if(isset($showAchievementsSection) && $showAchievementsSection && isset($achievements) && $achievements->count() > 0)
  <section class="ab-section ab-section--alt">
    <div class="ab-container">
      <div class="ab-section-header">
        <span class="ab-eyebrow">Réalisations</span>
        <h2 class="ab-heading ab-heading--gradient">{{ trans('app.about.achievements.title') }}</h2>
        <p class="ab-subtext">{{ trans('app.about.achievements.subtitle') }}</p>
      </div>

      <div class="ab-achievements-grid">
        @foreach($achievements as $achievement)
        <div class="ab-achiev-card">
          <div class="ab-achiev-card__bg">
            @if($achievement->image)
              <img src="{{ $achievement->image_url }}" alt="{{ $achievement->title }}" class="ab-achiev-card__img">
            @else
              <div class="ab-achiev-card__placeholder"></div>
            @endif
            <div class="ab-achiev-card__overlay"></div>
          </div>

          <div class="ab-achiev-card__content">
            @if($achievement->icon)
            <div class="ab-achiev-card__icon-badge"><i class="{{ $achievement->icon }}"></i></div>
            @endif
            <h3 class="ab-achiev-card__title">{{ $achievement->title }}</h3>
            @if($achievement->description)
            <p class="ab-achiev-card__desc">{{ Str::limit($achievement->description, 140) }}</p>
            @endif
            @if($achievement->link_url)
            <a href="{{ $achievement->link_url }}" target="_blank" rel="noopener" class="ab-achiev-card__link">
              {{ trans('app.about.achievements.view_project') }}
              <i class="fas fa-arrow-right"></i>
            </a>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ── CTA ──────────────────────────────────────────────── --}}
  <section class="ab-cta">
    <div class="ab-cta__box">
      <h2 class="ab-cta__title ab-heading--gradient">{{ trans('app.about.cta.title') }}</h2>
      <p class="ab-cta__subtitle">{{ trans('app.about.cta.subtitle') }}</p>
      <div class="ab-cta__actions">
        <a href="{{ route('home') }}" class="ab-btn ab-btn--primary">
          <i class="fas fa-home"></i>
          {{ trans('app.about.cta.back_home') }}
        </a>
        <a href="mailto:{{ optional($siteSettings)->contact_email ?? 'NiangProgrammeur@gmail.com' }}" class="ab-btn ab-btn--ghost">
          <i class="fas fa-envelope"></i>
          {{ trans('app.about.cta.contact_me') }}
        </a>
      </div>
    </div>
  </section>

</div>
@endsection
