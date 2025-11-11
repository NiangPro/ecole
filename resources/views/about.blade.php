@extends('layouts.app')

@section('title', 'À Propos - NiangProgrammeur | Développeur Full-Stack & Formateur')
@section('meta_description', 'Découvrez l\'histoire de Bassirou Niang, développeur Full-Stack et formateur passionné. Plus de 5 ans d\'expérience chez Sunucode, spécialisé en Laravel, React et Vue.js.')
@section('meta_keywords', 'Bassirou Niang, NiangProgrammeur, développeur full-stack, formateur développement web, Sunucode, Laravel expert, React Vue.js')

@section('styles')
<style>
    body {
        overflow-x: hidden;
    }
    
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .skill-bar {
        height: 8px;
        background: rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .skill-progress {
        height: 100%;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        border-radius: 10px;
        transition: width 2s ease-out;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen bg-black pt-32 pb-20 overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-10 w-72 h-72 bg-cyan-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <!-- Hero Content -->
        <div class="max-w-5xl mx-auto text-center mb-20">
            <h1 class="text-6xl md:text-7xl font-black mb-6">
                Bonjour, C'est <span class="gradient-text">Bassirou Niang</span>
            </h1>
            <p class="text-2xl md:text-3xl text-gray-300 mb-8">
                Je suis <span class="text-cyan-400 font-bold">NiangProgrammeur</span>
            </p>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                Développeur Full-Stack & Formateur passionné
            </p>
        </div>
        
        <!-- Profile Card -->
        <div class="max-w-6xl mx-auto">
            <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-xl rounded-3xl p-8 md:p-12 border border-cyan-500/30 shadow-2xl">
                <div class="grid md:grid-cols-3 gap-8 items-center">
                    <!-- Avatar -->
                    <div class="flex justify-center">
                        <div class="relative">
                            <div class="w-64 h-64 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-full flex items-center justify-center shadow-2xl">
                                <i class="fas fa-user-tie text-8xl text-white"></i>
                            </div>
                            <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-green-500 rounded-full border-4 border-black flex items-center justify-center">
                                <i class="fas fa-check text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="md:col-span-2">
                        <h2 class="text-4xl font-bold mb-4 gradient-text">À propos de moi</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Voici l'histoire d'un développeur passionné qui a suivi une voie conventionnelle pour arriver à son métier actuel. 
                            Issu d'une famille modeste, dès mon plus jeune âge, j'étais fasciné par la technologie et passais des heures sur mon 
                            ordinateur personnel à explorer différents programmes et langages de programmation.
                        </p>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Au fil du temps, ma passion est devenue évidente auprès de mes collègues qui ont commencé à me confier des projets liés 
                            au développement web. Petit-à-petit, je me suis constitué un portefeuille impressionnant avec diverses réalisations allant 
                            du simple site vitrine jusqu'à la création complète d'applications complexes.
                        </p>
                        
                        <!-- Contact Info -->
                        <div class="grid sm:grid-cols-2 gap-4 mt-8">
                            <div class="flex items-center gap-3 p-4 bg-black/50 rounded-xl border border-cyan-500/20">
                                <i class="fas fa-envelope text-cyan-400 text-2xl"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <a href="mailto:NiangProgrammeur@gmail.com" class="text-sm font-semibold hover:text-cyan-400 transition">
                                        NiangProgrammeur@gmail.com
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-4 bg-black/50 rounded-xl border border-cyan-500/20">
                                <i class="fas fa-phone text-teal-400 text-2xl"></i>
                                <div>
                                    <p class="text-xs text-gray-500">Téléphone</p>
                                    <a href="tel:+221783123657" class="text-sm font-semibold hover:text-cyan-400 transition">
                                        +221 78 312 36 57
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Compétences Section -->
<section class="py-20 bg-gradient-to-b from-black to-gray-900">
    <div class="container mx-auto px-6">
        <h2 class="text-5xl font-bold text-center mb-4">
            <span class="gradient-text">Compétences & Capacités</span>
        </h2>
        <p class="text-center text-gray-400 mb-16 max-w-2xl mx-auto">
            Maîtrise des technologies modernes du développement web
        </p>
        
        <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8">
            @php
                $skills = [
                    ['name' => 'HTML5 & CSS3', 'level' => 95, 'icon' => 'fab fa-html5', 'color' => 'from-orange-500 to-red-500'],
                    ['name' => 'JavaScript & TypeScript', 'level' => 90, 'icon' => 'fab fa-js', 'color' => 'from-yellow-500 to-orange-500'],
                    ['name' => 'PHP & Laravel', 'level' => 88, 'icon' => 'fab fa-php', 'color' => 'from-purple-500 to-pink-500'],
                    ['name' => 'React & Vue.js', 'level' => 85, 'icon' => 'fab fa-react', 'color' => 'from-blue-500 to-cyan-500'],
                    ['name' => 'Node.js & Express', 'level' => 82, 'icon' => 'fab fa-node', 'color' => 'from-green-500 to-teal-500'],
                    ['name' => 'Git & GitHub', 'level' => 92, 'icon' => 'fab fa-git-alt', 'color' => 'from-red-500 to-orange-500'],
                    ['name' => 'WordPress', 'level' => 87, 'icon' => 'fab fa-wordpress', 'color' => 'from-blue-600 to-cyan-600'],
                    ['name' => 'Intelligence Artificielle', 'level' => 75, 'icon' => 'fas fa-brain', 'color' => 'from-purple-600 to-pink-600'],
                ];
            @endphp
            
            @foreach($skills as $skill)
            <div class="bg-gray-900/50 backdrop-blur-xl rounded-2xl p-6 border border-cyan-500/20 hover:border-cyan-500/50 transition">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br {{ $skill['color'] }} rounded-xl flex items-center justify-center">
                        <i class="{{ $skill['icon'] }} text-white text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg">{{ $skill['name'] }}</h3>
                        <p class="text-sm text-gray-400">{{ $skill['level'] }}%</p>
                    </div>
                </div>
                <div class="skill-bar">
                    <div class="skill-progress" style="width: {{ $skill['level'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Formation Section -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <h2 class="text-5xl font-bold text-center mb-4">
            <span class="gradient-text">Mes Formations</span>
        </h2>
        <p class="text-center text-gray-400 mb-16 max-w-2xl mx-auto">
            L'éducation ne se limite pas seulement au domaine scolaire mais englobe également l'apprentissage tout au long de la vie
        </p>
        
        <div class="max-w-4xl mx-auto space-y-8">
            @php
                $formations = [
                    [
                        'school' => 'Access Code School',
                        'degree' => 'Spécialisation Développeur Web & Mobile',
                        'period' => '2019-2020',
                        'icon' => 'fas fa-code',
                        'color' => 'cyan'
                    ],
                    [
                        'school' => 'Institut Supérieur D\'Informatique',
                        'degree' => 'Licence Professionnelle Génie Logiciel',
                        'period' => '2016-2019',
                        'icon' => 'fas fa-graduation-cap',
                        'color' => 'teal'
                    ],
                    [
                        'school' => 'Lycée Seydina Limamou Laye',
                        'degree' => 'Baccalauréat Scientifique S2',
                        'period' => '2013-2016',
                        'icon' => 'fas fa-school',
                        'color' => 'purple'
                    ],
                ];
            @endphp
            
            @foreach($formations as $formation)
            <div class="bg-gradient-to-br from-gray-800/50 to-black/50 backdrop-blur-xl rounded-2xl p-8 border border-{{ $formation['color'] }}-500/30 hover:border-{{ $formation['color'] }}-500/60 transition">
                <div class="flex items-start gap-6">
                    <div class="w-16 h-16 bg-{{ $formation['color'] }}-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="{{ $formation['icon'] }} text-{{ $formation['color'] }}-400 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold mb-2">{{ $formation['school'] }}</h3>
                        <p class="text-lg text-{{ $formation['color'] }}-400 mb-2">{{ $formation['degree'] }}</p>
                        <p class="text-gray-500 flex items-center gap-2">
                            <i class="fas fa-calendar"></i>
                            {{ $formation['period'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Expériences Section -->
<section class="py-20 bg-gradient-to-b from-gray-900 to-black">
    <div class="container mx-auto px-6">
        <h2 class="text-5xl font-bold text-center mb-4">
            <span class="gradient-text">Expériences Professionnelles</span>
        </h2>
        <p class="text-center text-gray-400 mb-16 max-w-2xl mx-auto">
            Mon parcours professionnel dans le développement web
        </p>
        
        <div class="max-w-4xl mx-auto">
            <div class="bg-gray-900/50 backdrop-blur-xl rounded-2xl p-8 border border-cyan-500/20 hover:border-cyan-500/50 transition">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-building text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold mb-1">Sunucode</h3>
                                <a href="https://sunucode.com/" target="_blank" class="text-cyan-400 hover:text-cyan-300 transition text-sm flex items-center gap-2">
                                    <i class="fas fa-external-link-alt"></i>
                                    sunucode.com
                                </a>
                            </div>
                        </div>
                        
                        <p class="text-xl text-cyan-400 mb-3 font-semibold">
                            <i class="fas fa-code mr-2"></i>Développeur Full-Stack & Formateur
                        </p>
                        
                        <p class="text-gray-400 flex items-center gap-2 mb-6">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="font-semibold">2020 - Présent</span>
                            <span class="text-gray-600">•</span>
                            <span class="text-green-400">{{ \Carbon\Carbon::parse('2020-01-01')->diffForHumans(null, true) }}</span>
                        </p>
                        
                        <div class="space-y-3">
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Développement d'applications web complexes avec Laravel, React et Vue.js
                            </p>
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Formation et accompagnement de développeurs juniors
                            </p>
                            <p class="text-gray-300 leading-relaxed">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Gestion de projets et architecture logicielle
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                        <span class="px-6 py-3 bg-green-500/20 text-green-400 rounded-xl text-sm font-bold text-center border border-green-500/30">
                            <i class="fas fa-briefcase mr-2"></i>En cours
                        </span>
                        <span class="px-6 py-3 bg-cyan-500/20 text-cyan-400 rounded-xl text-sm font-bold text-center border border-cyan-500/30">
                            <i class="fas fa-clock mr-2"></i>Temps plein
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Plus de {{ \Carbon\Carbon::parse('2020-01-01')->diffInYears() }} ans d'expérience professionnelle
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto bg-gradient-to-br from-cyan-600/20 to-teal-600/20 rounded-3xl p-12 border border-cyan-500/30 text-center">
            <h2 class="text-4xl font-bold mb-6">
                <span class="gradient-text">Prêt à apprendre ?</span>
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Rejoignez des milliers d'apprenants et commencez votre parcours dans le développement web
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('home') }}" class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-xl font-bold text-lg hover:shadow-2xl hover:scale-105 transition">
                    <i class="fas fa-home mr-2"></i>Retour à l'accueil
                </a>
                <a href="{{ route('home') }}#contact" class="px-8 py-4 bg-gray-800 hover:bg-gray-700 rounded-xl font-bold text-lg border border-cyan-500/30 hover:border-cyan-500/60 transition">
                    <i class="fas fa-envelope mr-2"></i>Me contacter
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
