<?php

namespace Database\Seeders;

use App\Models\PaidCourse;
use App\Models\CourseChapter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaidCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Formation Complète Laravel',
                'title_fr' => 'Formation Complète Laravel - De Zéro à Expert',
                'title_en' => 'Complete Laravel Course - From Zero to Expert',
                'slug' => 'formation-complete-laravel',
                'description' => 'Maîtrisez Laravel, le framework PHP le plus populaire, de A à Z',
                'description_fr' => 'Maîtrisez Laravel, le framework PHP le plus populaire, de A à Z. Apprenez à créer des applications web modernes et robustes avec toutes les fonctionnalités avancées.',
                'description_en' => 'Master Laravel, the most popular PHP framework, from A to Z. Learn to create modern and robust web applications with all advanced features.',
                'content' => 'Cette formation complète vous permettra de maîtriser Laravel en profondeur...',
                'content_fr' => 'Cette formation complète vous permettra de maîtriser Laravel en profondeur. Vous apprendrez les concepts fondamentaux, les fonctionnalités avancées, la sécurité, les performances, et bien plus encore. À la fin de cette formation, vous serez capable de créer des applications web professionnelles avec Laravel.',
                'content_en' => 'This complete course will allow you to master Laravel in depth. You will learn fundamental concepts, advanced features, security, performance, and much more. At the end of this course, you will be able to create professional web applications with Laravel.',
                'price' => 50000,
                'currency' => 'XOF',
                'discount_price' => 35000,
                'discount_start' => now(),
                'discount_end' => now()->addMonths(1),
                'status' => 'published',
                'duration_hours' => 40,
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'what_you_learn' => [
                    'Les bases de Laravel',
                    'Les migrations et les modèles',
                    'Les contrôleurs et les routes',
                    'L\'authentification',
                    'Les relations Eloquent',
                    'Les API REST',
                    'Les tests unitaires',
                    'Le déploiement'
                ],
                'requirements' => [
                    'Connaissances de base en PHP',
                    'Compréhension du HTML/CSS',
                    'Connaissances de base en JavaScript'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Laravel',
                        'description' => 'Découvrez Laravel et son écosystème',
                        'content' => 'Dans ce premier chapitre, nous allons découvrir Laravel, comprendre son architecture et installer notre premier projet. Laravel est un framework PHP moderne qui facilite le développement d\'applications web.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'Routes et Contrôleurs',
                        'description' => 'Maîtrisez le système de routage et la création de contrôleurs',
                        'content' => 'Les routes sont le point d\'entrée de votre application Laravel. Nous verrons comment définir des routes, créer des contrôleurs et organiser votre code de manière professionnelle.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Base de données et Eloquent ORM',
                        'description' => 'Apprenez à gérer les bases de données avec les migrations et Eloquent',
                        'content' => 'Laravel facilite grandement la gestion des bases de données grâce aux migrations et à Eloquent ORM. Nous créerons des migrations pour définir la structure de nos tables et utiliserons Eloquent pour interagir avec les données.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Authentification et Autorisation',
                        'description' => 'Implémentez un système complet d\'authentification',
                        'content' => 'Laravel fournit un système d\'authentification complet prêt à l\'emploi. Nous verrons comment l\'utiliser et le personnaliser selon vos besoins.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'API REST avec Laravel',
                        'description' => 'Créez des API RESTful complètes et professionnelles',
                        'content' => 'Laravel excelle dans la création d\'API REST. Nous créerons une API complète avec validation, transformation et pagination.',
                        'duration_minutes' => 110
                    ],
                    [
                        'title' => 'Tests et Déploiement',
                        'description' => 'Apprenez à tester votre application et la déployer en production',
                        'content' => 'Les tests sont essentiels pour maintenir la qualité de votre code. Nous verrons comment écrire des tests avec PHPUnit et déployer votre application.',
                        'duration_minutes' => 90
                    ]
                ]
            ],
            [
                'title' => 'Formation React Avancée',
                'title_fr' => 'Formation React Avancée - Hooks, Context & Redux',
                'title_en' => 'Advanced React Course - Hooks, Context & Redux',
                'slug' => 'formation-react-avancee',
                'description' => 'Découvrez les fonctionnalités avancées de React pour créer des applications modernes',
                'description_fr' => 'Découvrez les fonctionnalités avancées de React pour créer des applications modernes et performantes. Maîtrisez les Hooks, Context API, Redux et bien plus encore.',
                'description_en' => 'Discover advanced React features to create modern and performant applications. Master Hooks, Context API, Redux and much more.',
                'content' => 'Cette formation vous permettra de maîtriser React à un niveau avancé...',
                'content_fr' => 'Cette formation vous permettra de maîtriser React à un niveau avancé. Vous apprendrez à utiliser les Hooks modernes, à gérer l\'état global avec Redux, à optimiser les performances, et à créer des applications complexes et scalables.',
                'content_en' => 'This course will allow you to master React at an advanced level. You will learn to use modern Hooks, manage global state with Redux, optimize performance, and create complex and scalable applications.',
                'price' => 45000,
                'currency' => 'XOF',
                'discount_price' => null,
                'discount_start' => null,
                'discount_end' => null,
                'status' => 'published',
                'duration_hours' => 35,
                'cover_image' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'what_you_learn' => [
                    'Les Hooks React (useState, useEffect, etc.)',
                    'Context API et la gestion d\'état',
                    'Redux et Redux Toolkit',
                    'Les performances et l\'optimisation',
                    'Les tests avec Jest et React Testing Library',
                    'Le routing avec React Router',
                    'Les patterns avancés',
                    'Le déploiement'
                ],
                'requirements' => [
                    'Connaissances de base en JavaScript',
                    'Compréhension du HTML/CSS',
                    'Expérience avec React (niveau débutant)'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à React Avancé',
                        'description' => 'Découvrez les concepts avancés de React',
                        'content' => 'Nous commencerons par revoir les bases de React et introduire les concepts avancés que nous allons explorer dans cette formation.',
                        'duration_minutes' => 45
                    ],
                    [
                        'title' => 'Les Hooks React',
                        'description' => 'Maîtrisez useState, useEffect, useContext et les hooks personnalisés',
                        'content' => 'Les hooks sont la façon moderne de gérer l\'état et les effets de bord dans React. Nous explorerons tous les hooks essentiels et créerons nos propres hooks personnalisés.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Context API',
                        'description' => 'Utilisez Context API pour la gestion d\'état globale',
                        'content' => 'Context API permet de partager des données entre composants sans prop drilling. Nous verrons comment l\'utiliser efficacement.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Redux et Redux Toolkit',
                        'description' => 'Implémentez Redux pour gérer l\'état global de votre application',
                        'content' => 'Redux permet de gérer l\'état de manière prévisible dans vos applications React. Nous utiliserons Redux Toolkit pour simplifier notre code.',
                        'duration_minutes' => 150
                    ],
                    [
                        'title' => 'Optimisation des Performances',
                        'description' => 'Apprenez à optimiser les performances de vos applications React',
                        'content' => 'Nous verrons comment utiliser React.memo, useMemo, useCallback et d\'autres techniques pour optimiser les performances.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Tests avec Jest et React Testing Library',
                        'description' => 'Écrivez des tests complets pour vos composants React',
                        'content' => 'Les tests garantissent la qualité et la maintenabilité de votre code. Nous apprendrons à écrire des tests unitaires et d\'intégration.',
                        'duration_minutes' => 110
                    ]
                ]
            ],
            [
                'title' => 'Formation Node.js & Express',
                'title_fr' => 'Formation Node.js & Express - Backend Complet',
                'title_en' => 'Node.js & Express Course - Complete Backend',
                'slug' => 'formation-nodejs-express',
                'description' => 'Apprenez à créer des APIs robustes avec Node.js et Express',
                'description_fr' => 'Apprenez à créer des APIs robustes avec Node.js et Express. Maîtrisez le développement backend, les bases de données, l\'authentification JWT, et bien plus encore.',
                'description_en' => 'Learn to create robust APIs with Node.js and Express. Master backend development, databases, JWT authentication, and much more.',
                'content' => 'Cette formation vous enseignera tout ce que vous devez savoir sur Node.js...',
                'content_fr' => 'Cette formation vous enseignera tout ce que vous devez savoir sur Node.js et Express. Vous apprendrez à créer des serveurs, à gérer les bases de données, à implémenter l\'authentification, à gérer les erreurs, et à déployer vos applications.',
                'content_en' => 'This course will teach you everything you need to know about Node.js and Express. You will learn to create servers, manage databases, implement authentication, handle errors, and deploy your applications.',
                'price' => 40000,
                'currency' => 'XOF',
                'discount_price' => 30000,
                'discount_start' => now(),
                'discount_end' => now()->addWeeks(2),
                'status' => 'published',
                'duration_hours' => 30,
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'what_you_learn' => [
                    'Les bases de Node.js',
                    'Express.js et le routing',
                    'Les middlewares',
                    'L\'intégration avec MongoDB',
                    'L\'authentification JWT',
                    'La gestion des erreurs',
                    'Les tests avec Jest',
                    'Le déploiement sur Heroku/Vercel'
                ],
                'requirements' => [
                    'Connaissances solides en JavaScript',
                    'Compréhension des concepts backend',
                    'Expérience avec les bases de données'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Node.js',
                        'description' => 'Découvrez Node.js et son écosystème',
                        'content' => 'Nous commencerons par comprendre ce qu\'est Node.js, comment il fonctionne, et pourquoi il est si populaire pour le développement backend.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'Express.js et le Routing',
                        'description' => 'Créez des serveurs avec Express.js',
                        'content' => 'Express.js est le framework web le plus populaire pour Node.js. Nous apprendrons à créer des routes, gérer les requêtes et les réponses.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Les Middlewares',
                        'description' => 'Comprenez et utilisez les middlewares Express',
                        'content' => 'Les middlewares sont au cœur d\'Express. Nous verrons comment créer et utiliser des middlewares personnalisés.',
                        'duration_minutes' => 80
                    ],
                    [
                        'title' => 'Intégration avec MongoDB',
                        'description' => 'Connectez votre application à MongoDB',
                        'content' => 'Nous apprendrons à utiliser Mongoose pour interagir avec MongoDB et créer des modèles de données.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Authentification JWT',
                        'description' => 'Implémentez l\'authentification avec JWT',
                        'content' => 'Nous créerons un système d\'authentification complet utilisant JSON Web Tokens pour sécuriser nos API.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Gestion des Erreurs et Déploiement',
                        'description' => 'Gérez les erreurs et déployez votre application',
                        'content' => 'Nous verrons comment gérer les erreurs de manière professionnelle et déployer votre application sur des plateformes comme Heroku ou Vercel.',
                        'duration_minutes' => 90
                    ]
                ]
            ],
            [
                'title' => 'Formation Python Django',
                'title_fr' => 'Formation Python Django - Framework Web Complet',
                'title_en' => 'Python Django Course - Complete Web Framework',
                'slug' => 'formation-python-django',
                'description' => 'Maîtrisez Django, le framework Python pour créer des applications web puissantes',
                'description_fr' => 'Maîtrisez Django, le framework Python pour créer des applications web puissantes. Apprenez les modèles, les vues, les templates, l\'ORM, et bien plus encore.',
                'description_en' => 'Master Django, the Python framework to create powerful web applications. Learn models, views, templates, ORM, and much more.',
                'content' => 'Cette formation complète vous permettra de maîtriser Django...',
                'content_fr' => 'Cette formation complète vous permettra de maîtriser Django. Vous apprendrez à créer des applications web complètes, à gérer les bases de données avec l\'ORM Django, à implémenter l\'authentification, à créer des APIs REST, et à déployer vos applications.',
                'content_en' => 'This complete course will allow you to master Django. You will learn to create complete web applications, manage databases with Django ORM, implement authentication, create REST APIs, and deploy your applications.',
                'price' => 48000,
                'currency' => 'XOF',
                'discount_price' => null,
                'discount_start' => null,
                'discount_end' => null,
                'status' => 'published',
                'duration_hours' => 38,
                'cover_image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'what_you_learn' => [
                    'Les bases de Django',
                    'Les modèles et l\'ORM',
                    'Les vues et les templates',
                    'Les formulaires',
                    'L\'authentification',
                    'Les APIs REST avec Django REST Framework',
                    'Les tests',
                    'Le déploiement'
                ],
                'requirements' => [
                    'Connaissances de base en Python',
                    'Compréhension du HTML/CSS',
                    'Connaissances de base en bases de données'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Django',
                        'description' => 'Découvrez Django et créez votre premier projet',
                        'content' => 'Nous commencerons par installer Django et créer notre premier projet. Nous explorerons la structure d\'un projet Django et comprendre l\'architecture MVC.',
                        'duration_minutes' => 70
                    ],
                    [
                        'title' => 'Modèles et ORM Django',
                        'description' => 'Créez des modèles de données avec l\'ORM Django',
                        'content' => 'L\'ORM Django est puissant et intuitif. Nous apprendrons à créer des modèles, définir des relations et effectuer des requêtes complexes.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Vues et Templates',
                        'description' => 'Créez des vues et des templates Django',
                        'content' => 'Nous verrons comment créer des vues, utiliser le système de templates Django et gérer le rendu des pages.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Formulaires Django',
                        'description' => 'Créez et validez des formulaires',
                        'content' => 'Django fournit un système de formulaires puissant. Nous apprendrons à créer des formulaires, les valider et gérer les erreurs.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Authentification Django',
                        'description' => 'Implémentez l\'authentification utilisateur',
                        'content' => 'Django inclut un système d\'authentification complet. Nous verrons comment l\'utiliser et le personnaliser.',
                        'duration_minutes' => 80
                    ],
                    [
                        'title' => 'Django REST Framework',
                        'description' => 'Créez des APIs REST avec DRF',
                        'content' => 'Django REST Framework facilite la création d\'API REST. Nous créerons une API complète avec serializers, viewsets et permissions.',
                        'duration_minutes' => 130
                    ],
                    [
                        'title' => 'Tests et Déploiement',
                        'description' => 'Testez et déployez votre application Django',
                        'content' => 'Nous apprendrons à écrire des tests pour Django et à déployer notre application en production.',
                        'duration_minutes' => 100
                    ]
                ]
            ],
            [
                'title' => 'Formation Vue.js 3',
                'title_fr' => 'Formation Vue.js 3 - Composition API & TypeScript',
                'title_en' => 'Vue.js 3 Course - Composition API & TypeScript',
                'slug' => 'formation-vuejs-3',
                'description' => 'Découvrez Vue.js 3 avec la Composition API et TypeScript',
                'description_fr' => 'Découvrez Vue.js 3 avec la Composition API et TypeScript. Apprenez à créer des applications modernes, réactives et performantes avec le framework Vue.js.',
                'description_en' => 'Discover Vue.js 3 with Composition API and TypeScript. Learn to create modern, reactive and performant applications with the Vue.js framework.',
                'content' => 'Cette formation vous permettra de maîtriser Vue.js 3...',
                'content_fr' => 'Cette formation vous permettra de maîtriser Vue.js 3. Vous apprendrez la Composition API, TypeScript, Pinia pour la gestion d\'état, Vue Router, et toutes les fonctionnalités avancées de Vue.js 3.',
                'content_en' => 'This course will allow you to master Vue.js 3. You will learn Composition API, TypeScript, Pinia for state management, Vue Router, and all advanced features of Vue.js 3.',
                'price' => 42000,
                'currency' => 'XOF',
                'discount_price' => 32000,
                'discount_start' => now(),
                'discount_end' => now()->addMonths(1),
                'status' => 'published',
                'duration_hours' => 32,
                'cover_image' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'what_you_learn' => [
                    'Vue.js 3 et la Composition API',
                    'TypeScript avec Vue.js',
                    'Pinia pour la gestion d\'état',
                    'Vue Router',
                    'Les composables',
                    'Les performances et l\'optimisation',
                    'Les tests',
                    'Le déploiement'
                ],
                'requirements' => [
                    'Connaissances de base en JavaScript',
                    'Compréhension du HTML/CSS',
                    'Expérience avec Vue.js (optionnel)'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Vue.js 3',
                        'description' => 'Découvrez Vue.js 3 et ses nouveautés',
                        'content' => 'Nous explorerons Vue.js 3, ses nouvelles fonctionnalités et la Composition API. Nous verrons les différences avec Vue.js 2.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'Composition API',
                        'description' => 'Maîtrisez la Composition API de Vue.js 3',
                        'content' => 'La Composition API est la nouvelle façon d\'écrire des composants Vue.js. Nous apprendrons setup(), ref(), reactive(), computed() et bien plus.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'TypeScript avec Vue.js',
                        'description' => 'Intégrez TypeScript dans vos projets Vue.js',
                        'content' => 'TypeScript apporte la sécurité de type à Vue.js. Nous verrons comment l\'intégrer et l\'utiliser efficacement.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Pinia : Gestion d\'état',
                        'description' => 'Utilisez Pinia pour gérer l\'état global',
                        'content' => 'Pinia est le nouveau système de gestion d\'état recommandé pour Vue.js 3. Nous apprendrons à créer des stores et les utiliser dans nos composants.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Vue Router',
                        'description' => 'Implémentez la navigation avec Vue Router',
                        'content' => 'Vue Router permet de créer des applications à page unique avec navigation. Nous verrons comment configurer les routes et gérer la navigation.',
                        'duration_minutes' => 80
                    ],
                    [
                        'title' => 'Composables et Optimisation',
                        'description' => 'Créez des composables réutilisables et optimisez vos performances',
                        'content' => 'Les composables permettent de réutiliser la logique entre composants. Nous verrons aussi comment optimiser les performances de nos applications Vue.js.',
                        'duration_minutes' => 100
                    ]
                ]
            ],
            [
                'title' => 'Formation Full Stack MERN',
                'title_fr' => 'Formation Full Stack MERN - MongoDB, Express, React, Node.js',
                'title_en' => 'Full Stack MERN Course - MongoDB, Express, React, Node.js',
                'slug' => 'formation-fullstack-mern',
                'description' => 'Devenez développeur Full Stack avec la stack MERN',
                'description_fr' => 'Devenez développeur Full Stack avec la stack MERN. Apprenez MongoDB, Express, React et Node.js pour créer des applications web complètes de A à Z.',
                'description_en' => 'Become a Full Stack developer with the MERN stack. Learn MongoDB, Express, React and Node.js to create complete web applications from A to Z.',
                'content' => 'Cette formation complète vous permettra de maîtriser la stack MERN...',
                'content_fr' => 'Cette formation complète vous permettra de maîtriser la stack MERN. Vous apprendrez à créer des applications full stack complètes, de la base de données à l\'interface utilisateur, en passant par l\'API backend.',
                'content_en' => 'This complete course will allow you to master the MERN stack. You will learn to create complete full stack applications, from the database to the user interface, through the backend API.',
                'price' => 60000,
                'currency' => 'XOF',
                'discount_price' => 45000,
                'discount_start' => now(),
                'discount_end' => now()->addMonths(2),
                'status' => 'published',
                'duration_hours' => 50,
                'cover_image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'what_you_learn' => [
                    'MongoDB et la gestion des données',
                    'Express.js pour le backend',
                    'React pour le frontend',
                    'Node.js et les APIs',
                    'L\'authentification JWT',
                    'La gestion d\'état avec Redux',
                    'Le déploiement full stack',
                    'Les bonnes pratiques'
                ],
                'requirements' => [
                    'Connaissances solides en JavaScript',
                    'Compréhension du HTML/CSS',
                    'Expérience avec les bases de données'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à la Stack MERN',
                        'description' => 'Découvrez MongoDB, Express, React et Node.js',
                        'content' => 'Nous commencerons par comprendre chaque technologie de la stack MERN et comment elles s\'intègrent ensemble pour créer des applications complètes.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'MongoDB : Base de Données NoSQL',
                        'description' => 'Maîtrisez MongoDB pour stocker vos données',
                        'content' => 'MongoDB est une base de données NoSQL flexible. Nous apprendrons à créer des collections, des documents et effectuer des requêtes complexes.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Node.js et Express : Backend',
                        'description' => 'Créez votre API backend avec Node.js et Express',
                        'content' => 'Nous créerons un serveur Express complet avec routes, middlewares et intégration MongoDB.',
                        'duration_minutes' => 150
                    ],
                    [
                        'title' => 'React : Frontend',
                        'description' => 'Développez l\'interface utilisateur avec React',
                        'content' => 'Nous créerons une interface React moderne qui communique avec notre API backend.',
                        'duration_minutes' => 140
                    ],
                    [
                        'title' => 'Authentification JWT',
                        'description' => 'Sécurisez votre application avec JWT',
                        'content' => 'Nous implémenterons un système d\'authentification complet utilisant JSON Web Tokens.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Redux pour la Gestion d\'État',
                        'description' => 'Gérez l\'état global avec Redux',
                        'content' => 'Nous utiliserons Redux pour gérer l\'état de notre application React de manière prévisible.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Projet Final : Application Complète',
                        'description' => 'Créez une application MERN complète de A à Z',
                        'content' => 'Nous réaliserons un projet complet qui combine toutes les technologies MERN pour créer une application fonctionnelle.',
                        'duration_minutes' => 180
                    ],
                    [
                        'title' => 'Déploiement Full Stack',
                        'description' => 'Déployez votre application MERN en production',
                        'content' => 'Nous verrons comment déployer notre application complète sur des plateformes comme Heroku, Vercel ou AWS.',
                        'duration_minutes' => 90
                    ]
                ]
            ],
            [
                'title' => 'Formation Docker & Kubernetes',
                'title_fr' => 'Formation Docker & Kubernetes - Containerisation & Orchestration',
                'title_en' => 'Docker & Kubernetes Course - Containerization & Orchestration',
                'slug' => 'formation-docker-kubernetes',
                'description' => 'Maîtrisez Docker et Kubernetes pour le déploiement moderne',
                'description_fr' => 'Maîtrisez Docker et Kubernetes pour le déploiement moderne. Apprenez à containeriser vos applications, à orchestrer avec Kubernetes, et à gérer vos infrastructures cloud.',
                'description_en' => 'Master Docker and Kubernetes for modern deployment. Learn to containerize your applications, orchestrate with Kubernetes, and manage your cloud infrastructures.',
                'content' => 'Cette formation vous permettra de maîtriser Docker et Kubernetes...',
                'content_fr' => 'Cette formation vous permettra de maîtriser Docker et Kubernetes. Vous apprendrez à créer des images Docker, à gérer les conteneurs, à déployer avec Kubernetes, à gérer les services, et à scaler vos applications.',
                'content_en' => 'This course will allow you to master Docker and Kubernetes. You will learn to create Docker images, manage containers, deploy with Kubernetes, manage services, and scale your applications.',
                'price' => 55000,
                'currency' => 'XOF',
                'discount_price' => 40000,
                'discount_start' => now(),
                'discount_end' => now()->addMonths(1),
                'status' => 'published',
                'duration_hours' => 42,
                'cover_image' => 'https://images.unsplash.com/photo-1605745341112-85968b19335b?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'what_you_learn' => [
                    'Docker et les conteneurs',
                    'Les Dockerfiles',
                    'Docker Compose',
                    'Kubernetes et les pods',
                    'Les services et les déploiements',
                    'La gestion des secrets',
                    'Le scaling et l\'auto-scaling',
                    'Le monitoring'
                ],
                'requirements' => [
                    'Connaissances de base en Linux',
                    'Expérience avec les serveurs',
                    'Compréhension des concepts de déploiement'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Docker',
                        'description' => 'Découvrez Docker et la containerisation',
                        'content' => 'Nous commencerons par comprendre ce qu\'est Docker, pourquoi l\'utiliser, et comment il révolutionne le déploiement d\'applications.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'Les Dockerfiles',
                        'description' => 'Créez des images Docker avec Dockerfiles',
                        'content' => 'Nous apprendrons à créer des Dockerfiles efficaces pour containeriser nos applications.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Docker Compose',
                        'description' => 'Orchestrez plusieurs conteneurs avec Docker Compose',
                        'content' => 'Docker Compose permet de gérer plusieurs conteneurs ensemble. Nous créerons des applications multi-conteneurs.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Introduction à Kubernetes',
                        'description' => 'Découvrez Kubernetes et son architecture',
                        'content' => 'Kubernetes est la plateforme d\'orchestration de conteneurs la plus populaire. Nous explorerons son architecture et ses concepts fondamentaux.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Pods et Services Kubernetes',
                        'description' => 'Créez et gérez des pods et services',
                        'content' => 'Nous apprendrons à créer des pods, des services et à gérer la communication entre les composants de notre application.',
                        'duration_minutes' => 110
                    ],
                    [
                        'title' => 'Déploiements et Scaling',
                        'description' => 'Gérez les déploiements et le scaling automatique',
                        'content' => 'Nous verrons comment créer des déploiements, gérer les mises à jour et configurer l\'auto-scaling.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Secrets et Configuration',
                        'description' => 'Gérez les secrets et la configuration dans Kubernetes',
                        'content' => 'Nous apprendrons à gérer les secrets, les ConfigMaps et à sécuriser nos applications.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Monitoring et Maintenance',
                        'description' => 'Surveillez et maintenez vos clusters Kubernetes',
                        'content' => 'Nous verrons comment monitorer nos applications, gérer les logs et maintenir nos clusters Kubernetes.',
                        'duration_minutes' => 80
                    ]
                ]
            ]
        ];

        foreach ($courses as $courseData) {
            // Extraire les chapitres
            $chapters = $courseData['chapters'] ?? [];
            unset($courseData['chapters']);

            // Générer un slug unique si nécessaire
            $originalSlug = $courseData['slug'];
            $counter = 1;
            while (PaidCourse::where('slug', $courseData['slug'])->exists()) {
                $courseData['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Générer des statistiques aléatoires pour les cours publiés
            if ($courseData['status'] === 'published') {
                $courseData['students_count'] = rand(10, 500);
                $courseData['rating'] = round(rand(35, 50) / 10, 1);
                $courseData['reviews_count'] = rand(5, 150);
            } else {
                $courseData['students_count'] = 0;
                $courseData['rating'] = 0;
                $courseData['reviews_count'] = 0;
            }

            // Créer le cours
            $course = PaidCourse::create($courseData);

            // Créer les chapitres
            foreach ($chapters as $index => $chapterData) {
                CourseChapter::create([
                    'paid_course_id' => $course->id,
                    'title' => $chapterData['title'],
                    'description' => $chapterData['description'] ?? null,
                    'content' => $chapterData['content'] ?? null,
                    'order' => $index + 1,
                    'duration_minutes' => $chapterData['duration_minutes'] ?? null,
                ]);
            }

            $this->command->info("✅ Cours créé : {$course->title} ({$course->id}) avec " . count($chapters) . " chapitres");
        }

        $totalChapters = CourseChapter::count();
        $this->command->info("🎉 " . count($courses) . " cours payants créés avec succès !");
        $this->command->info("📚 Total de {$totalChapters} chapitres créés !");
    }
}
