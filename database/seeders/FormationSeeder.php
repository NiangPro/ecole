<?php

namespace Database\Seeders;

use App\Models\Formation;
use App\Models\FormationChapter;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Formation 1: HTML5
        $html5 = Formation::create([
            'title' => 'HTML5 Complet',
            'slug' => 'html5-complet',
            'description' => 'Apprenez HTML5 de zéro à maître. Tous les éléments, sémantique, formulaires, multimedia.',
            'content' => 'HTML5 est le langage de balisage standard du web. Ce cours vous enseigne tout sur HTML5.',
            'cover_image' => 'formations/html5.jpg',
            'chapters_count' => 30,
            'duration_hours' => 25,
            'rating' => 4.8,
            'reviews_count' => 342,
            'views_count' => 15000,
            'enrollments_count' => 8500,
            'level' => 'Beginner',
            'category' => 'Web Development',
            'what_you_learn' => ['HTML5 basics', 'Semantic HTML', 'Forms', 'Audio/Video', 'Canvas', 'Storage API'],
            'requirements' => ['Basic computer knowledge', 'Text editor (VS Code)'],
            'status' => 'published',
        ]);

        // Formation 2: CSS3
        $css3 = Formation::create([
            'title' => 'CSS3 Avancé',
            'slug' => 'css3-avance',
            'description' => 'Maîtrisez CSS3. Flexbox, Grid, Animations, Transitions, Responsive Design.',
            'content' => 'CSS3 permet de créer des designs modernes et responsifs.',
            'cover_image' => 'formations/css3.jpg',
            'chapters_count' => 28,
            'duration_hours' => 22,
            'rating' => 4.9,
            'reviews_count' => 298,
            'views_count' => 14000,
            'enrollments_count' => 7800,
            'level' => 'Intermediate',
            'category' => 'Web Development',
            'what_you_learn' => ['Selectors', 'Flexbox', 'Grid', 'Animations', 'Responsive Design', 'Preprocessing'],
            'requirements' => ['HTML basics', 'Text editor'],
            'status' => 'published',
        ]);

        // Formation 3: JavaScript
        $javascript = Formation::create([
            'title' => 'JavaScript Moderne (ES6+)',
            'slug' => 'javascript-moderne-es6',
            'description' => 'JavaScript complet avec ES6+, Async/Await, Promises, DOM manipulation.',
            'content' => 'JavaScript est le langage de programmation du web.',
            'cover_image' => 'formations/javascript.jpg',
            'chapters_count' => 45,
            'duration_hours' => 40,
            'rating' => 4.7,
            'reviews_count' => 512,
            'views_count' => 25000,
            'enrollments_count' => 12000,
            'level' => 'Intermediate',
            'category' => 'Web Development',
            'what_you_learn' => ['ES6 syntax', 'Async/Await', 'Promises', 'DOM', 'Events', 'APIs'],
            'requirements' => ['HTML/CSS basics', 'Browser knowledge'],
            'status' => 'published',
        ]);

        // Formation 4: PHP
        $php = Formation::create([
            'title' => 'PHP Backend Complet',
            'slug' => 'php-backend-complet',
            'description' => 'PHP pour le backend. Sessions, Databases, Security, REST APIs.',
            'content' => 'PHP est le langage serveur le plus populaire.',
            'cover_image' => 'formations/php.jpg',
            'chapters_count' => 50,
            'duration_hours' => 45,
            'rating' => 4.6,
            'reviews_count' => 438,
            'views_count' => 18000,
            'enrollments_count' => 9500,
            'level' => 'Intermediate',
            'category' => 'Backend Development',
            'what_you_learn' => ['PHP Basics', 'OOP', 'Databases', 'Security', 'APIs', 'Frameworks'],
            'requirements' => ['Programming basics', 'Local development setup'],
            'status' => 'published',
        ]);

        // Formation 5: Python
        $python = Formation::create([
            'title' => 'Python pour Tous',
            'slug' => 'python-pour-tous',
            'description' => 'Python du zéro. Variables, Fonctions, Classes, Data Science basics.',
            'content' => 'Python est un langage polyvalent et facile à apprendre.',
            'cover_image' => 'formations/python.jpg',
            'chapters_count' => 35,
            'duration_hours' => 30,
            'rating' => 4.8,
            'reviews_count' => 367,
            'views_count' => 16500,
            'enrollments_count' => 8900,
            'level' => 'Beginner',
            'category' => 'Programming',
            'what_you_learn' => ['Syntax', 'Data Structures', 'Functions', 'OOP', 'Libraries', 'Data Science'],
            'requirements' => ['No programming experience needed'],
            'status' => 'published',
        ]);

        // Formation 6: Java
        $java = Formation::create([
            'title' => 'Java Enterprise',
            'slug' => 'java-enterprise',
            'description' => 'Java complet. OOP, Collections, Threads, Spring Framework.',
            'content' => 'Java est utilisé par millions d\'applications enterprise.',
            'cover_image' => 'formations/java.jpg',
            'chapters_count' => 40,
            'duration_hours' => 38,
            'rating' => 4.5,
            'reviews_count' => 289,
            'views_count' => 13000,
            'enrollments_count' => 6500,
            'level' => 'Advanced',
            'category' => 'Programming',
            'what_you_learn' => ['OOP', 'Collections', 'Threads', 'Spring', 'Databases', 'Testing'],
            'requirements' => ['Programming experience', 'JDK installed'],
            'status' => 'published',
        ]);

        // Formation 7: SQL
        $sql = Formation::create([
            'title' => 'SQL Maître',
            'slug' => 'sql-maitre',
            'description' => 'SQL complet. Requêtes, Joins, Aggregations, Optimization, Transactions.',
            'content' => 'SQL est essentiel pour tout développeur.',
            'cover_image' => 'formations/sql.jpg',
            'chapters_count' => 30,
            'duration_hours' => 25,
            'rating' => 4.7,
            'reviews_count' => 245,
            'views_count' => 11000,
            'enrollments_count' => 5800,
            'level' => 'Intermediate',
            'category' => 'Databases',
            'what_you_learn' => ['SELECT', 'JOINs', 'Aggregations', 'Subqueries', 'Optimization', 'Transactions'],
            'requirements' => ['Database basics', 'MySQL/PostgreSQL'],
            'status' => 'published',
        ]);

        // Formation 8: React
        $react = Formation::create([
            'title' => 'React Modern',
            'slug' => 'react-modern',
            'description' => 'React.js. Components, Hooks, State Management, Testing.',
            'content' => 'React est la librairie JavaScript la plus populaire.',
            'cover_image' => 'formations/react.jpg',
            'chapters_count' => 42,
            'duration_hours' => 35,
            'rating' => 4.8,
            'reviews_count' => 401,
            'views_count' => 19000,
            'enrollments_count' => 10200,
            'level' => 'Advanced',
            'category' => 'Frontend Frameworks',
            'what_you_learn' => ['Components', 'Hooks', 'State', 'Effects', 'Context', 'Testing'],
            'requirements' => ['JavaScript ES6+', 'Node.js', 'npm'],
            'status' => 'published',
        ]);

        // Formation 9: Vue.js
        $vue = Formation::create([
            'title' => 'Vue.js 3 Complet',
            'slug' => 'vuejs3-complet',
            'description' => 'Vue.js 3 avec Composition API, Pinia, Vue Router.',
            'content' => 'Vue.js est un framework progressive et éprouvé.',
            'cover_image' => 'formations/vue.jpg',
            'chapters_count' => 38,
            'duration_hours' => 32,
            'rating' => 4.7,
            'reviews_count' => 312,
            'views_count' => 14500,
            'enrollments_count' => 7200,
            'level' => 'Intermediate',
            'category' => 'Frontend Frameworks',
            'what_you_learn' => ['Composition API', 'Templates', 'Reactivity', 'Router', 'State', 'Testing'],
            'requirements' => ['JavaScript basics', 'Node.js', 'npm'],
            'status' => 'published',
        ]);

        // Formation 10: Bootstrap
        $bootstrap = Formation::create([
            'title' => 'Bootstrap 5 Framework',
            'slug' => 'bootstrap5-framework',
            'description' => 'Bootstrap 5. Responsive design, Components, Customization.',
            'content' => 'Bootstrap est le framework CSS le plus utilisé.',
            'cover_image' => 'formations/bootstrap.jpg',
            'chapters_count' => 25,
            'duration_hours' => 18,
            'rating' => 4.6,
            'reviews_count' => 223,
            'views_count' => 10000,
            'enrollments_count' => 5200,
            'level' => 'Beginner',
            'category' => 'Web Development',
            'what_you_learn' => ['Grid System', 'Components', 'Utilities', 'Forms', 'Customization', 'Responsive'],
            'requirements' => ['HTML/CSS basics'],
            'status' => 'published',
        ]);

        // Formation 11: Git & GitHub
        $git = Formation::create([
            'title' => 'Git Maîtrise Complète',
            'slug' => 'git-maitrise-complete',
            'description' => 'Git & GitHub. Version control, Branching, Collaboration, Workflows.',
            'content' => 'Git est essentiel pour tout développeur moderne.',
            'cover_image' => 'formations/git.jpg',
            'chapters_count' => 20,
            'duration_hours' => 15,
            'rating' => 4.9,
            'reviews_count' => 189,
            'views_count' => 9500,
            'enrollments_count' => 5000,
            'level' => 'Beginner',
            'category' => 'DevOps',
            'what_you_learn' => ['Basics', 'Branching', 'Merging', 'GitHub', 'Collaboration', 'Workflows'],
            'requirements' => ['Command line basics'],
            'status' => 'published',
        ]);

        // Formation 12: WordPress
        $wordpress = Formation::create([
            'title' => 'WordPress Professionnel',
            'slug' => 'wordpress-professionnel',
            'description' => 'WordPress. Plugins, Themes, Customization, Performance.',
            'content' => 'WordPress alimente 43% du web.',
            'cover_image' => 'formations/wordpress.jpg',
            'chapters_count' => 30,
            'duration_hours' => 26,
            'rating' => 4.5,
            'reviews_count' => 178,
            'views_count' => 8500,
            'enrollments_count' => 4200,
            'level' => 'Intermediate',
            'category' => 'CMS',
            'what_you_learn' => ['Installation', 'Plugins', 'Themes', 'Customization', 'Performance', 'SEO'],
            'requirements' => ['Web hosting', 'Basic admin knowledge'],
            'status' => 'published',
        ]);

        // Formation 13: Machine Learning
        $ml = Formation::create([
            'title' => 'Machine Learning Basics',
            'slug' => 'machine-learning-basics',
            'description' => 'Intro à ML. Supervised Learning, Unsupervised, Scikit-learn.',
            'content' => 'Machine Learning transforme le monde.',
            'cover_image' => 'formations/ml.jpg',
            'chapters_count' => 32,
            'duration_hours' => 30,
            'rating' => 4.6,
            'reviews_count' => 267,
            'views_count' => 12500,
            'enrollments_count' => 6300,
            'level' => 'Advanced',
            'category' => 'Data Science',
            'what_you_learn' => ['Supervised Learning', 'Unsupervised', 'Evaluation', 'Scikit-learn', 'Deep Learning'],
            'requirements' => ['Python knowledge', 'Math basics', 'Numpy/Pandas'],
            'status' => 'published',
        ]);

        // Formation 14: C++
        $cpp = Formation::create([
            'title' => 'C++ Complet',
            'slug' => 'cpp-complet',
            'description' => 'C++ moderne. OOP, STL, Memory Management, Performance.',
            'content' => 'C++ est le langage pour la performance.',
            'cover_image' => 'formations/cpp.jpg',
            'chapters_count' => 40,
            'duration_hours' => 38,
            'rating' => 4.4,
            'reviews_count' => 156,
            'views_count' => 7500,
            'enrollments_count' => 3800,
            'level' => 'Advanced',
            'category' => 'Programming',
            'what_you_learn' => ['Basics', 'OOP', 'STL', 'Memory', 'Templates', 'Optimization'],
            'requirements' => ['C basics', 'Compiler setup'],
            'status' => 'published',
        ]);

        // Formation 15: C#
        $csharp = Formation::create([
            'title' => 'C# & .NET',
            'slug' => 'csharp-dotnet',
            'description' => 'C# et .NET Framework. ASP.NET Core, Entity Framework, LINQ.',
            'content' => 'C# est un langage moderne et productif.',
            'cover_image' => 'formations/csharp.jpg',
            'chapters_count' => 35,
            'duration_hours' => 32,
            'rating' => 4.5,
            'reviews_count' => 198,
            'views_count' => 9200,
            'enrollments_count' => 4600,
            'level' => 'Intermediate',
            'category' => 'Programming',
            'what_you_learn' => ['Syntax', 'OOP', 'LINQ', 'ASP.NET', 'Entity Framework', 'Async'],
            'requirements' => ['Visual Studio', 'Programming basics'],
            'status' => 'published',
        ]);

        // Créer des chapitres pour chaque formation
        $formations = [
            'html5' => [$html5, 'HTML5'],
            'css3' => [$css3, 'CSS3'],
            'javascript' => [$javascript, 'JavaScript'],
            'php' => [$php, 'PHP'],
            'python' => [$python, 'Python'],
        ];

        foreach ($formations as $key => [$formation, $name]) {
            for ($i = 1; $i <= min(5, $formation->chapters_count); $i++) {
                FormationChapter::create([
                    'formation_id' => $formation->id,
                    'title' => "$name - Chapitre $i",
                    'description' => "Apprenez le chapitre $i de $name avec des exemples pratiques.",
                    'content' => "Contenu détaillé du chapitre $i...",
                    'order' => $i,
                    'duration_minutes' => rand(30, 120),
                    'status' => 'published',
                ]);
            }
        }

        // Créer un quiz pour HTML5
        $quiz = Quiz::create([
            'formation_id' => $html5->id,
            'title' => 'Quiz HTML5 - Niveau Débutant',
            'description' => 'Testez vos connaissances en HTML5',
            'total_questions' => 10,
            'passing_score' => 70,
            'time_limit_minutes' => 15,
            'shuffle_questions' => true,
            'show_correct_answers' => true,
            'status' => 'published',
        ]);

        // Questions du quiz
        $questions = [
            [
                'question' => 'Quel est le bon doctype HTML5?',
                'type' => 'multiple_choice',
                'explanation' => '<!DOCTYPE html> est le doctype correct pour HTML5',
                'points' => 1,
                'options' => [
                    ['text' => '<!DOCTYPE html>', 'correct' => true],
                    ['text' => '<!DOCTYPE HTML PUBLIC>', 'correct' => false],
                    ['text' => '<?xml version="1.0"?>', 'correct' => false],
                    ['text' => 'Pas besoin de doctype', 'correct' => false],
                ]
            ],
            [
                'question' => 'Quel élément HTML5 est utilisé pour la navigation?',
                'type' => 'multiple_choice',
                'explanation' => '<nav> est l\'élément sémantique pour la navigation',
                'points' => 1,
                'options' => [
                    ['text' => '<navigation>', 'correct' => false],
                    ['text' => '<nav>', 'correct' => true],
                    ['text' => '<menu>', 'correct' => false],
                    ['text' => '<div class="nav">', 'correct' => false],
                ]
            ],
        ];

        foreach ($questions as $index => $questionData) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'explanation' => $questionData['explanation'],
                'order' => $index + 1,
                'points' => $questionData['points'],
            ]);

            foreach ($questionData['options'] as $optionIndex => $option) {
                QuizOption::create([
                    'question_id' => $question->id,
                    'option_text' => $option['text'],
                    'is_correct' => $option['correct'],
                    'order' => $optionIndex + 1,
                ]);
            }
        }

        $this->command->info('✅ Formations créées avec succès!');
    }
}
