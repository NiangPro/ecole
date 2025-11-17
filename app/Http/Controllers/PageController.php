<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class PageController extends Controller
{
    public function index()
    {
        // Cache les 3 derniers articles publiés (15 minutes) - Optimisé avec select()
        $latestJobs = \Illuminate\Support\Facades\Cache::remember('latest_jobs', 900, function () {
            return \App\Models\JobArticle::where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        });
        
        // Cache les publicités pour la position "content" (30 minutes)
        $sidebarAds = \Illuminate\Support\Facades\Cache::remember('sidebar_ads_content', 1800, function () {
            return \App\Models\Ad::active()
                ->forPosition('content')
                ->whereNull('location')
                ->orderBy('order')
                ->get();
        });
        
        // Cache les publicités pour l'emplacement "homepage_after_exercises" (30 minutes)
        $homepageAds = \Illuminate\Support\Facades\Cache::remember('homepage_ads_after_exercises', 1800, function () {
            return \App\Models\Ad::active()
                ->forLocation('homepage_after_exercises')
                ->orderBy('order')
                ->get();
        });
        
        return view('index', compact('latestJobs', 'sidebarAds', 'homepageAds'));
    }

    public function about()
    {
        return view('about');
    }
    
    public function contact()
    {
        return view('contact');
    }
    
    public function sendContact(Request $request)
    {
        // Vérifier le Honeypot
        if ($request->has('website') && !empty($request->input('website'))) {
            // Bot détecté - rejeter silencieusement
            abort(403, 'Spam détecté');
        }

        // Vérifier le temps de remplissage du formulaire
        if ($request->has('_form_time')) {
            $formTime = (float) $request->input('_form_time');
            $submitTime = microtime(true);
            $timeDiff = $submitTime - $formTime;

            // Si le formulaire a été soumis en moins de 2 secondes, c'est probablement un bot
            if ($timeDiff < 2) {
                abort(403, 'Spam détecté');
            }
        }

        // Vérifier reCAPTCHA si configuré
        $recaptchaService = new \App\Services\RecaptchaService();
        if (!empty(config('services.recaptcha.secret_key'))) {
            $recaptchaToken = $request->input('g-recaptcha-response');
            if (!$recaptchaService->verify($recaptchaToken, $request->ip())) {
                return back()->with('error', 'Vérification anti-spam échouée. Veuillez réessayer.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        ContactMessage::create($request->all());
        
        return back()->with('success', 'Votre message a été envoyé avec succès! Nous vous répondrons dans les plus brefs délais.');
    }

    public function faq()
    {
        return view('faq');
    }

    public function exercices()
    {
        $languages = [
            ['name' => 'HTML5', 'slug' => 'html5', 'icon' => 'fab fa-html5', 'color' => 'orange', 'exercises' => 25],
            ['name' => 'CSS3', 'slug' => 'css3', 'icon' => 'fab fa-css3-alt', 'color' => 'blue', 'exercises' => 30],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'icon' => 'fab fa-js', 'color' => 'yellow', 'exercises' => 35],
            ['name' => 'PHP', 'slug' => 'php', 'icon' => 'fab fa-php', 'color' => 'purple', 'exercises' => 28],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap', 'icon' => 'fab fa-bootstrap', 'color' => 'purple', 'exercises' => 20],
            ['name' => 'Git', 'slug' => 'git', 'icon' => 'fab fa-git-alt', 'color' => 'red', 'exercises' => 15],
            ['name' => 'WordPress', 'slug' => 'wordpress', 'icon' => 'fab fa-wordpress', 'color' => 'blue', 'exercises' => 18],
            ['name' => 'IA', 'slug' => 'ia', 'icon' => 'fas fa-robot', 'color' => 'green', 'exercises' => 12],
        ];
        
        return view('exercices', compact('languages'));
    }

    public function exercicesLanguage($language)
    {
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Varier les exercices par utilisateur (basé sur IP + session)
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $exercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        return view('exercices-language', compact('language', 'exercises'));
    }
    
    /**
     * Obtenir des exercices variés par utilisateur pour tous les niveaux
     */
    private function getVariedExercises($allExercises, $userIdentifier)
    {
        // Organiser les exercices par niveau
        $byDifficulty = [
            'Facile' => [],
            'Moyen' => [],
            'Difficile' => []
        ];
        
        foreach ($allExercises as $index => $exercise) {
            $difficulty = $exercise['difficulty'] ?? 'Facile';
            if (isset($byDifficulty[$difficulty])) {
                $byDifficulty[$difficulty][] = array_merge($exercise, ['original_index' => $index + 1]);
            }
        }
        
        // Utiliser l'identifiant utilisateur pour créer une variation
        $seed = hexdec(substr($userIdentifier, 0, 8));
        mt_srand($seed);
        
        $selectedExercises = [];
        
        // Sélectionner des exercices de chaque niveau
        // Facile: 2-3 exercices
        $facileCount = min(3, count($byDifficulty['Facile']));
        if ($facileCount > 0) {
            $facileSelected = $this->selectRandomExercises($byDifficulty['Facile'], $facileCount);
            $selectedExercises = array_merge($selectedExercises, $facileSelected);
        }
        
        // Moyen: 2-3 exercices
        $moyenCount = min(3, count($byDifficulty['Moyen']));
        if ($moyenCount > 0) {
            $moyenSelected = $this->selectRandomExercises($byDifficulty['Moyen'], $moyenCount);
            $selectedExercises = array_merge($selectedExercises, $moyenSelected);
        }
        
        // Difficile: 1-2 exercices
        $difficileCount = min(2, count($byDifficulty['Difficile']));
        if ($difficileCount > 0) {
            $difficileSelected = $this->selectRandomExercises($byDifficulty['Difficile'], $difficileCount);
            $selectedExercises = array_merge($selectedExercises, $difficileSelected);
        }
        
        // Mélanger l'ordre pour plus de variété
        shuffle($selectedExercises);
        
        // Réindexer pour les URLs
        foreach ($selectedExercises as $index => &$exercise) {
            $exercise['display_index'] = $index + 1;
        }
        
        return $selectedExercises;
    }
    
    /**
     * Sélectionner des exercices aléatoires de manière déterministe
     */
    private function selectRandomExercises($exercises, $count)
    {
        if (count($exercises) <= $count) {
            return $exercises;
        }
        
        $selected = [];
        $indices = range(0, count($exercises) - 1);
        shuffle($indices);
        
        for ($i = 0; $i < $count; $i++) {
            $selected[] = $exercises[$indices[$i]];
        }
        
        return $selected;
    }

    public function exerciceDetail($language, $id)
    {
        // Récupérer tous les exercices pour trouver celui correspondant à l'index d'affichage
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Varier les exercices par utilisateur (même logique que dans exercicesLanguage)
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $variedExercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        // Trouver l'exercice par son index d'affichage
        $exercise = null;
        foreach ($variedExercises as $ex) {
            if ($ex['display_index'] == $id) {
                // Récupérer les détails complets de l'exercice
                $originalIndex = $ex['original_index'] ?? $id;
                $exercise = $this->getExerciseDetail($language, $originalIndex);
                if ($exercise) {
                    $exercise['display_index'] = $id;
                }
                break;
            }
        }
        
        if (!$exercise) {
            abort(404);
        }
        
        return view('exercice-detail', compact('language', 'id', 'exercise'));
    }

    public function exerciceSubmit(Request $request, $language, $id)
    {
        // Utiliser la même logique que exerciceDetail pour trouver l'exercice
        $allExercises = $this->getExercisesByLanguage($language);
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $variedExercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        $exercise = null;
        foreach ($variedExercises as $ex) {
            if ($ex['display_index'] == $id) {
                $originalIndex = $ex['original_index'] ?? $id;
                $exercise = $this->getExerciseDetail($language, $originalIndex);
                break;
            }
        }
        
        if (!$exercise) {
            return response()->json(['correct' => false, 'message' => 'Exercice non trouvé']);
        }
        
        $userCode = $request->input('code');
        
        // Vérification simple (à améliorer)
        $isCorrect = $this->checkAnswer($exercise, $userCode);
        
        return response()->json([
            'correct' => $isCorrect,
            'message' => $isCorrect ? 'Bravo ! Votre réponse est correcte !' : 'Pas tout à fait. Réessayez !',
            'solution' => $isCorrect ? null : $exercise['solution']
        ]);
    }

    private function checkAnswer($exercise, $userCode)
    {
        // Normaliser le code (enlever espaces, sauts de ligne, etc.)
        $userCodeNormalized = preg_replace('/\s+/', '', strtolower($userCode));
        $solutionNormalized = preg_replace('/\s+/', '', strtolower($exercise['solution']));
        
        return $userCodeNormalized === $solutionNormalized;
    }

    private function getExerciseDetail($language, $id)
    {
        $allExercises = [
            'html5' => [
                1 => [
                    'title' => 'Les balises de base',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Ajoutez un titre "Bienvenue" à la page HTML ci-dessous.',
                    'description' => 'Utilisez la balise HTML appropriée pour ajouter un titre de niveau 1.',
                    'startCode' => '<html>
<body>

"Bienvenue"

</body>
</html>',
                    'solution' => '<html>
<body>

<h1>Bienvenue</h1>

</body>
</html>',
                    'hint' => 'Utilisez la balise <h1> pour créer un titre de niveau 1.'
                ],
                2 => [
                    'title' => 'Les paragraphes',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Ajoutez un paragraphe avec le texte "Ceci est un paragraphe."',
                    'description' => 'Utilisez la balise HTML appropriée pour créer un paragraphe.',
                    'startCode' => '<html>
<body>

<h1>Mon premier titre</h1>

"Ceci est un paragraphe."

</body>
</html>',
                    'solution' => '<html>
<body>

<h1>Mon premier titre</h1>

<p>Ceci est un paragraphe.</p>

</body>
</html>',
                    'hint' => 'Utilisez la balise <p> pour créer un paragraphe.'
                ],
                3 => [
                    'title' => 'Les liens',
                    'difficulty' => 'Facile',
                    'points' => 15,
                    'instruction' => 'Créez un lien vers "https://www.google.com" avec le texte "Aller sur Google".',
                    'description' => 'Utilisez la balise <a> avec l\'attribut href.',
                    'startCode' => '<html>
<body>

<h1>Liens HTML</h1>

"Aller sur Google"

</body>
</html>',
                    'solution' => '<html>
<body>

<h1>Liens HTML</h1>

<a href="https://www.google.com">Aller sur Google</a>

</body>
</html>',
                    'hint' => 'Utilisez <a href="URL">Texte</a> pour créer un lien.'
                ],
                4 => [
                    'title' => 'Les images',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Ajoutez une image avec la source "logo.png" et le texte alternatif "Logo".',
                    'description' => 'Utilisez la balise <img> avec les attributs src et alt.',
                    'startCode' => '<html>
<body>

<h1>Images HTML</h1>



</body>
</html>',
                    'solution' => '<html>
<body>

<h1>Images HTML</h1>

<img src="logo.png" alt="Logo">

</body>
</html>',
                    'hint' => 'Utilisez <img src="..." alt="..."> pour ajouter une image.'
                ],
                5 => [
                    'title' => 'Les listes',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez une liste non ordonnée avec trois éléments : "HTML", "CSS", "JavaScript".',
                    'description' => 'Utilisez les balises <ul> et <li>.',
                    'startCode' => '<html>
<body>

<h1>Mes langages préférés</h1>



</body>
</html>',
                    'solution' => '<html>
<body>

<h1>Mes langages préférés</h1>

<ul>
<li>HTML</li>
<li>CSS</li>
<li>JavaScript</li>
</ul>

</body>
</html>',
                    'hint' => 'Utilisez <ul> pour la liste et <li> pour chaque élément.'
                ],
            ],
            'css3' => [
                1 => [
                    'title' => 'Les sélecteurs CSS',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Changez la couleur du texte du paragraphe en bleu.',
                    'description' => 'Utilisez la propriété CSS color pour changer la couleur du texte.',
                    'startCode' => '<html>
<head>
<style>
p {
  
}
</style>
</head>
<body>

<p>Ce texte doit être bleu.</p>

</body>
</html>',
                    'solution' => '<html>
<head>
<style>
p {
  color: blue;
}
</style>
</head>
<body>

<p>Ce texte doit être bleu.</p>

</body>
</html>',
                    'hint' => 'Utilisez color: blue; dans le sélecteur p.'
                ],
                2 => [
                    'title' => 'Flexbox - Centrage',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Centrez la div avec flexbox.',
                    'description' => 'Utilisez display: flex et les propriétés de centrage.',
                    'startCode' => '<html>
<head>
<style>
.container {
  height: 300px;
  border: 2px solid black;
  
  
  
}
.box {
  width: 100px;
  height: 100px;
  background: cyan;
}
</style>
</head>
<body>

<div class="container">
  <div class="box"></div>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<style>
.container {
  height: 300px;
  border: 2px solid black;
  display: flex;
  justify-content: center;
  align-items: center;
}
.box {
  width: 100px;
  height: 100px;
  background: cyan;
}
</style>
</head>
<body>

<div class="container">
  <div class="box"></div>
</div>

</body>
</html>',
                    'hint' => 'Utilisez display: flex, justify-content: center et align-items: center.'
                ],
                3 => [
                    'title' => 'Grid Layout',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez une grille de 3 colonnes égales.',
                    'description' => 'Utilisez CSS Grid pour créer une disposition en colonnes.',
                    'startCode' => '<html>
<head>
<style>
.grid {
  
  
}
.item {
  background: lightblue;
  padding: 20px;
  border: 1px solid blue;
}
</style>
</head>
<body>

<div class="grid">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<style>
.grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
}
.item {
  background: lightblue;
  padding: 20px;
  border: 1px solid blue;
}
</style>
</head>
<body>

<div class="grid">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>

</body>
</html>',
                    'hint' => 'Utilisez display: grid et grid-template-columns: 1fr 1fr 1fr.'
                ],
                4 => [
                    'title' => 'Animations CSS',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez une animation qui fait tourner la div en continu.',
                    'description' => 'Utilisez @keyframes et animation pour créer une rotation.',
                    'startCode' => '<html>
<head>
<style>
@keyframes rotate {
  from { transform: rotate(0deg); }
  to { }
}

.box {
  width: 100px;
  height: 100px;
  background: purple;
  
}
</style>
</head>
<body>

<div class="box"></div>

</body>
</html>',
                    'solution' => '<html>
<head>
<style>
@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.box {
  width: 100px;
  height: 100px;
  background: purple;
  animation: rotate 2s linear infinite;
}
</style>
</head>
<body>

<div class="box"></div>

</body>
</html>',
                    'hint' => 'Complétez le @keyframes avec rotate(360deg) et ajoutez animation: rotate 2s linear infinite.'
                ],
                5 => [
                    'title' => 'Responsive Design',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Rendez le texte rouge sur les petits écrans (max 600px).',
                    'description' => 'Utilisez une media query pour le responsive.',
                    'startCode' => '<html>
<head>
<style>
p {
  color: blue;
}


</style>
</head>
<body>

<p>Ce texte doit être rouge sur mobile.</p>

</body>
</html>',
                    'solution' => '<html>
<head>
<style>
p {
  color: blue;
}

@media (max-width: 600px) {
  p { color: red; }
}
</style>
</head>
<body>

<p>Ce texte doit être rouge sur mobile.</p>

</body>
</html>',
                    'hint' => 'Utilisez @media (max-width: 600px) { p { color: red; } }'
                ],
            ],
            'javascript' => [
                1 => [
                    'title' => 'Variables et types',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une variable "nom" avec votre prénom et affichez-la.',
                    'description' => 'Utilisez let ou const pour déclarer une variable.',
                    'startCode' => '<html>
<body>

<p id="demo"></p>

<script>


document.getElementById("demo").innerHTML = nom;
</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<p id="demo"></p>

<script>
let nom = "Jean";

document.getElementById("demo").innerHTML = nom;
</script>

</body>
</html>',
                    'hint' => 'Utilisez let nom = "VotreNom"; avant le document.getElementById.'
                ],
                2 => [
                    'title' => 'Fonctions',
                    'difficulty' => 'Facile',
                    'points' => 15,
                    'instruction' => 'Créez une fonction qui additionne deux nombres.',
                    'description' => 'Utilisez function pour créer une fonction.',
                    'startCode' => '<html>
<body>

<p id="demo"></p>

<script>


let resultat = additionner(5, 3);
document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<p id="demo"></p>

<script>
function additionner(a, b) {
  return a + b;
}

let resultat = additionner(5, 3);
document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'hint' => 'Créez function additionner(a, b) { return a + b; }'
                ],
                3 => [
                    'title' => 'DOM Manipulation',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Changez le texte du bouton en "Cliqué !" quand on clique dessus.',
                    'description' => 'Utilisez addEventListener et innerHTML.',
                    'startCode' => '<html>
<body>

<button id="monBouton">Cliquez-moi</button>

<script>
let bouton = document.getElementById("monBouton");


</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<button id="monBouton">Cliquez-moi</button>

<script>
let bouton = document.getElementById("monBouton");
bouton.addEventListener("click", function() {
  bouton.innerHTML = "Cliqué !";
});
</script>

</body>
</html>',
                    'hint' => 'Utilisez bouton.addEventListener("click", function() { bouton.innerHTML = "Cliqué !"; });'
                ],
                4 => [
                    'title' => 'Tableaux et boucles',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Affichez tous les fruits du tableau avec une boucle.',
                    'description' => 'Utilisez une boucle for ou forEach.',
                    'startCode' => '<html>
<body>

<p id="demo"></p>

<script>
let fruits = ["Pomme", "Banane", "Orange"];
let texte = "";



document.getElementById("demo").innerHTML = texte;
</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<p id="demo"></p>

<script>
let fruits = ["Pomme", "Banane", "Orange"];
let texte = "";

for (let i = 0; i < fruits.length; i++) {
  texte += fruits[i] + " ";
}

document.getElementById("demo").innerHTML = texte;
</script>

</body>
</html>',
                    'hint' => 'Utilisez for (let i = 0; i < fruits.length; i++) { texte += fruits[i] + " "; }'
                ],
                5 => [
                    'title' => 'Objets JavaScript',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez un objet "personne" avec nom et age, puis affichez le nom.',
                    'description' => 'Utilisez la syntaxe des objets JavaScript.',
                    'startCode' => '<html>
<body>

<p id="demo"></p>

<script>


document.getElementById("demo").innerHTML = personne.nom;
</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<p id="demo"></p>

<script>
let personne = {
  nom: "Marie",
  age: 25
};

document.getElementById("demo").innerHTML = personne.nom;
</script>

</body>
</html>',
                    'hint' => 'Créez let personne = { nom: "Marie", age: 25 };'
                ],
            ],
            'php' => [
                1 => [
                    'title' => 'Syntaxe de base PHP',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Affichez "Bonjour PHP" avec echo.',
                    'description' => 'Utilisez la balise PHP et echo pour afficher du texte.',
                    'startCode' => '<html>
<body>

<?php

?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
echo "Bonjour PHP";
?>

</body>
</html>',
                    'hint' => 'Utilisez echo "Bonjour PHP"; entre les balises PHP.'
                ],
                2 => [
                    'title' => 'Variables PHP',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une variable $nom avec votre prénom et affichez-la.',
                    'description' => 'Les variables PHP commencent par $.',
                    'startCode' => '<html>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$nom = "Pierre";
echo $nom;
?>

</body>
</html>',
                    'hint' => 'Créez $nom = "Pierre"; puis echo $nom;'
                ],
                3 => [
                    'title' => 'Conditions PHP',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Affichez "Majeur" si $age >= 18, sinon "Mineur".',
                    'description' => 'Utilisez if/else en PHP.',
                    'startCode' => '<html>
<body>

<?php
$age = 20;


?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$age = 20;
if ($age >= 18) {
  echo "Majeur";
} else {
  echo "Mineur";
}
?>

</body>
</html>',
                    'hint' => 'Utilisez if ($age >= 18) { echo "Majeur"; } else { echo "Mineur"; }'
                ],
                4 => [
                    'title' => 'Boucles PHP',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Affichez les nombres de 1 à 5 avec une boucle for.',
                    'description' => 'Utilisez une boucle for en PHP.',
                    'startCode' => '<html>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
for ($i = 1; $i <= 5; $i++) {
  echo $i . " ";
}
?>

</body>
</html>',
                    'hint' => 'Utilisez for ($i = 1; $i <= 5; $i++) { echo $i . " "; }'
                ],
                5 => [
                    'title' => 'Tableaux PHP',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez un tableau de fruits et affichez-les avec foreach.',
                    'description' => 'Utilisez array() et foreach.',
                    'startCode' => '<html>
<body>

<?php




?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$fruits = array("Pomme", "Banane", "Orange");
foreach ($fruits as $fruit) {
  echo $fruit . " ";
}
?>

</body>
</html>',
                    'hint' => 'Créez $fruits = array("Pomme", "Banane", "Orange"); puis foreach ($fruits as $fruit) { echo $fruit . " "; }'
                ],
            ],
            'bootstrap' => [
                1 => [
                    'title' => 'Grille Bootstrap',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une grille Bootstrap avec 3 colonnes égales.',
                    'description' => 'Utilisez les classes Bootstrap col pour créer des colonnes.',
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="">Colonne 1</div>
    <div class="">Colonne 2</div>
    <div class="">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col">Colonne 1</div>
    <div class="col">Colonne 2</div>
    <div class="col">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'hint' => 'Ajoutez la classe "col" à chaque div pour créer des colonnes égales.'
                ],
                2 => [
                    'title' => 'Bouton Bootstrap',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez un bouton bleu primaire avec Bootstrap.',
                    'description' => 'Utilisez les classes btn et btn-primary.',
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <button class="">Cliquez-moi</button>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <button class="btn btn-primary">Cliquez-moi</button>
</div>

</body>
</html>',
                    'hint' => 'Ajoutez les classes "btn btn-primary" au bouton.'
                ],
                3 => [
                    'title' => 'Card Bootstrap',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Complétez la structure d\'une card Bootstrap.',
                    'description' => 'Ajoutez les classes card-body et card-title.',
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="card" style="width: 18rem;">
    <div class="">
      <h5 class="">Titre de la carte</h5>
      <p class="card-text">Contenu de la carte.</p>
    </div>
  </div>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="card" style="width: 18rem;">
    <div class="card-body">
      <h5 class="card-title">Titre de la carte</h5>
      <p class="card-text">Contenu de la carte.</p>
    </div>
  </div>
</div>

</body>
</html>',
                    'hint' => 'Ajoutez "card-body" à la div et "card-title" au h5.'
                ],
                4 => [
                    'title' => 'Navbar Bootstrap',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez une navbar Bootstrap avec un logo et des liens.',
                    'description' => 'Utilisez les classes navbar, navbar-brand et nav-link.',
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="" href="#">MonSite</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="" href="#">Accueil</a>
      </li>
    </ul>
  </div>
</nav>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">MonSite</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">Accueil</a>
      </li>
    </ul>
  </div>
</nav>

</body>
</html>',
                    'hint' => 'Ajoutez "navbar-brand" au logo et "nav-link" au lien.'
                ],
                5 => [
                    'title' => 'Responsive Bootstrap',
                    'difficulty' => 'Difficile',
                    'points' => 20,
                    'instruction' => 'Créez une grille responsive : 12 colonnes sur mobile, 6 sur tablette, 4 sur desktop.',
                    'description' => 'Utilisez col-12, col-md-6 et col-lg-4.',
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="">Colonne 1</div>
    <div class="">Colonne 2</div>
    <div class="">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-12 col-md-6 col-lg-4">Colonne 1</div>
    <div class="col-12 col-md-6 col-lg-4">Colonne 2</div>
    <div class="col-12 col-md-6 col-lg-4">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'hint' => 'Utilisez "col-12 col-md-6 col-lg-4" pour chaque colonne.'
                ],
            ],
            'git' => [
                1 => [
                    'title' => 'Initialiser un dépôt Git',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Quelle commande initialise un nouveau dépôt Git ?',
                    'description' => 'Tapez la commande Git pour créer un nouveau dépôt.',
                    'startCode' => 'git ',
                    'solution' => 'git init',
                    'hint' => 'La commande commence par "git" et utilise "init".'
                ],
                2 => [
                    'title' => 'Ajouter des fichiers',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Quelle commande ajoute tous les fichiers au staging ?',
                    'description' => 'Utilisez la commande pour ajouter tous les fichiers modifiés.',
                    'startCode' => 'git ',
                    'solution' => 'git add .',
                    'hint' => 'Utilisez "git add" suivi d\'un point pour tout ajouter.'
                ],
                3 => [
                    'title' => 'Créer un commit',
                    'difficulty' => 'Facile',
                    'points' => 15,
                    'instruction' => 'Créez un commit avec le message "Premier commit".',
                    'description' => 'Utilisez la commande commit avec l\'option -m.',
                    'startCode' => 'git ',
                    'solution' => 'git commit -m "Premier commit"',
                    'hint' => 'Utilisez git commit -m suivi du message entre guillemets.'
                ],
                4 => [
                    'title' => 'Créer une branche',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Créez une nouvelle branche appelée "develop".',
                    'description' => 'Utilisez la commande branch ou checkout -b.',
                    'startCode' => 'git ',
                    'solution' => 'git branch develop',
                    'hint' => 'Utilisez "git branch" suivi du nom de la branche.'
                ],
                5 => [
                    'title' => 'Fusionner des branches',
                    'difficulty' => 'Difficile',
                    'points' => 20,
                    'instruction' => 'Fusionnez la branche "feature" dans la branche actuelle.',
                    'description' => 'Utilisez la commande merge.',
                    'startCode' => 'git ',
                    'solution' => 'git merge feature',
                    'hint' => 'Utilisez "git merge" suivi du nom de la branche à fusionner.'
                ],
            ],
            'wordpress' => [
                1 => [
                    'title' => 'The Loop WordPress',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Complétez la boucle WordPress pour afficher les articles.',
                    'description' => 'Utilisez have_posts() et the_post().',
                    'startCode' => '<?php
if ( ) {
  while ( ) {
    
    the_title();
    the_content();
  }
}
?>',
                    'solution' => '<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_title();
    the_content();
  }
}
?>',
                    'hint' => 'Utilisez have_posts() dans le if et le while, puis the_post() dans la boucle.'
                ],
                2 => [
                    'title' => 'Afficher le titre',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Affichez le titre de l\'article dans un h1.',
                    'description' => 'Utilisez la fonction WordPress appropriée.',
                    'startCode' => '<h1><?php  ?></h1>',
                    'solution' => '<h1><?php the_title(); ?></h1>',
                    'hint' => 'Utilisez the_title() pour afficher le titre.'
                ],
                3 => [
                    'title' => 'Image à la une',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Affichez l\'image à la une de l\'article.',
                    'description' => 'Utilisez la fonction WordPress pour l\'image à la une.',
                    'startCode' => '<?php
if (has_post_thumbnail()) {
  
}
?>',
                    'solution' => '<?php
if (has_post_thumbnail()) {
  the_post_thumbnail();
}
?>',
                    'hint' => 'Utilisez the_post_thumbnail() pour afficher l\'image.'
                ],
                4 => [
                    'title' => 'Menu WordPress',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Affichez le menu principal de WordPress.',
                    'description' => 'Utilisez wp_nav_menu avec le bon paramètre.',
                    'startCode' => '<?php
wp_nav_menu(array(
  \'theme_location\' => \'\'
));
?>',
                    'solution' => '<?php
wp_nav_menu(array(
  \'theme_location\' => \'primary\'
));
?>',
                    'hint' => 'Utilisez "primary" comme theme_location.'
                ],
                5 => [
                    'title' => 'Custom Post Type',
                    'difficulty' => 'Difficile',
                    'points' => 20,
                    'instruction' => 'Enregistrez un custom post type "portfolio".',
                    'description' => 'Utilisez register_post_type avec les bons paramètres.',
                    'startCode' => '<?php
function create_portfolio_post_type() {
  register_post_type(\'\', array(
    \'labels\' => array(\'name\' => \'Portfolio\'),
    \'public\' => true
  ));
}
add_action(\'init\', \'create_portfolio_post_type\');
?>',
                    'solution' => '<?php
function create_portfolio_post_type() {
  register_post_type(\'portfolio\', array(
    \'labels\' => array(\'name\' => \'Portfolio\'),
    \'public\' => true
  ));
}
add_action(\'init\', \'create_portfolio_post_type\');
?>',
                    'hint' => 'Utilisez "portfolio" comme premier paramètre de register_post_type.'
                ],
            ],
            'ia' => [
                1 => [
                    'title' => 'Concepts de base IA',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Complétez : L\'IA est la simulation de l\'intelligence ___ par des machines.',
                    'description' => 'Quel type d\'intelligence l\'IA simule-t-elle ?',
                    'startCode' => 'L\'IA est la simulation de l\'intelligence ___ par des machines.',
                    'solution' => 'L\'IA est la simulation de l\'intelligence humaine par des machines.',
                    'hint' => 'L\'IA simule l\'intelligence humaine.'
                ],
                2 => [
                    'title' => 'Machine Learning',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Citez les 3 types principaux de Machine Learning.',
                    'description' => 'Supervisé, Non supervisé et...',
                    'startCode' => '1. Apprentissage supervisé
2. Apprentissage non supervisé
3. Apprentissage ___',
                    'solution' => '1. Apprentissage supervisé
2. Apprentissage non supervisé
3. Apprentissage par renforcement',
                    'hint' => 'Le troisième type est l\'apprentissage par renforcement.'
                ],
                3 => [
                    'title' => 'Réseaux de neurones',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Complétez la structure d\'un réseau de neurones simple.',
                    'description' => 'Un réseau a une couche d\'entrée, des couches ___ et une couche de sortie.',
                    'startCode' => 'Couche d\'entrée → Couches ___ → Couche de sortie',
                    'solution' => 'Couche d\'entrée → Couches cachées → Couche de sortie',
                    'hint' => 'Les couches intermédiaires sont appelées couches cachées.'
                ],
                4 => [
                    'title' => 'Deep Learning',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Quelle bibliothèque Python est populaire pour le Deep Learning ?',
                    'description' => 'Développée par Google, commence par "Tensor".',
                    'startCode' => 'import ___',
                    'solution' => 'import tensorflow',
                    'hint' => 'La bibliothèque s\'appelle TensorFlow.'
                ],
                5 => [
                    'title' => 'Applications IA',
                    'difficulty' => 'Difficile',
                    'points' => 18,
                    'instruction' => 'Citez 3 applications concrètes de l\'IA.',
                    'description' => 'Exemples : reconnaissance vocale, voitures autonomes...',
                    'startCode' => '1. Reconnaissance ___
2. Voitures ___
3. Assistants ___',
                    'solution' => '1. Reconnaissance vocale
2. Voitures autonomes
3. Assistants virtuels',
                    'hint' => 'Pensez à Siri, Tesla, et la reconnaissance d\'image.'
                ],
            ],
        ];

        return $allExercises[$language][$id] ?? null;
    }

    private function getExercisesByLanguage($language)
    {
        // Exemples d'exercices par langage
        $allExercises = [
            'html5' => [
                ['title' => 'Les balises de base', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les paragraphes', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les liens', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'Les images', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Les listes', 'difficulty' => 'Moyen', 'points' => 20],
            ],
            'css3' => [
                // Niveau Facile
                ['title' => 'Les sélecteurs CSS', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les couleurs et backgrounds', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les polices et textes', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Les marges et paddings', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les bordures', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen
                ['title' => 'Flexbox - Centrage', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Grid Layout', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Responsive Design', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Les pseudo-classes', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Les transformations CSS', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile
                ['title' => 'Animations CSS', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'CSS Variables', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Advanced Grid', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'javascript' => [
                // Niveau Facile
                ['title' => 'Variables et types', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Fonctions', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'Les conditions if/else', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Les opérateurs', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les chaînes de caractères', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen
                ['title' => 'DOM Manipulation', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Tableaux et boucles', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les événements', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Les fonctions fléchées', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Async/Await', 'difficulty' => 'Moyen', 'points' => 22],
                // Niveau Difficile
                ['title' => 'Objets JavaScript', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Promises et Callbacks', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Closures et Scope', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'php' => [
                // Niveau Facile
                ['title' => 'Syntaxe de base PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Variables PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les types de données', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Les opérateurs PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les chaînes PHP', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen
                ['title' => 'Conditions PHP', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Boucles PHP', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les fonctions PHP', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Les tableaux associatifs', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les formulaires PHP', 'difficulty' => 'Moyen', 'points' => 22],
                // Niveau Difficile
                ['title' => 'Tableaux PHP avancés', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'POO en PHP', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Les sessions PHP', 'difficulty' => 'Difficile', 'points' => 28],
            ],
            'bootstrap' => [
                // Niveau Facile
                ['title' => 'Grille Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Bouton Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les couleurs Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les typographies', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Les badges et alertes', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen
                ['title' => 'Card Bootstrap', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Navbar Bootstrap', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les modales', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Les formulaires Bootstrap', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les carrousels', 'difficulty' => 'Moyen', 'points' => 22],
                // Niveau Difficile
                ['title' => 'Responsive Bootstrap', 'difficulty' => 'Difficile', 'points' => 20],
                ['title' => 'Customisation Bootstrap', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Bootstrap avec JavaScript', 'difficulty' => 'Difficile', 'points' => 28],
            ],
            'git' => [
                // Niveau Facile
                ['title' => 'Initialiser un dépôt Git', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Ajouter des fichiers', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Créer un commit', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'Voir l\'historique', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Les statuts Git', 'difficulty' => 'Facile', 'points' => 10],
                // Niveau Moyen
                ['title' => 'Créer une branche', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Changer de branche', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Les remotes', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Push et Pull', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les tags Git', 'difficulty' => 'Moyen', 'points' => 18],
                // Niveau Difficile
                ['title' => 'Fusionner des branches', 'difficulty' => 'Difficile', 'points' => 20],
                ['title' => 'Résoudre les conflits', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Git rebase', 'difficulty' => 'Difficile', 'points' => 28],
            ],
            'wordpress' => [
                // Niveau Facile
                ['title' => 'The Loop WordPress', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Afficher le titre', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Afficher le contenu', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Les fonctions WordPress', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les hooks de base', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen
                ['title' => 'Image à la une', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Menu WordPress', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Les widgets', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Les taxonomies', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les métadonnées', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile
                ['title' => 'Custom Post Type', 'difficulty' => 'Difficile', 'points' => 20],
                ['title' => 'Les actions et filtres', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Créer un thème complet', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'ia' => [
                // Niveau Facile
                ['title' => 'Concepts de base IA', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Machine Learning', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Qu\'est-ce que l\'IA ?', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les types d\'IA', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Histoire de l\'IA', 'difficulty' => 'Facile', 'points' => 10],
                // Niveau Moyen
                ['title' => 'Réseaux de neurones', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Deep Learning', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Supervised Learning', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Unsupervised Learning', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Reinforcement Learning', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile
                ['title' => 'Applications IA', 'difficulty' => 'Difficile', 'points' => 18],
                ['title' => 'Natural Language Processing', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Computer Vision', 'difficulty' => 'Difficile', 'points' => 28],
            ],
        ];

        return $allExercises[$language] ?? [];
    }

    public function quiz()
    {
        $languages = [
            ['name' => 'HTML5', 'slug' => 'html5', 'icon' => 'fab fa-html5', 'color' => 'orange', 'questions' => 20],
            ['name' => 'CSS3', 'slug' => 'css3', 'icon' => 'fab fa-css3-alt', 'color' => 'blue', 'questions' => 20],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'icon' => 'fab fa-js', 'color' => 'yellow', 'questions' => 20],
            ['name' => 'PHP', 'slug' => 'php', 'icon' => 'fab fa-php', 'color' => 'purple', 'questions' => 20],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap', 'icon' => 'fab fa-bootstrap', 'color' => 'purple', 'questions' => 15],
            ['name' => 'Git', 'slug' => 'git', 'icon' => 'fab fa-git-alt', 'color' => 'red', 'questions' => 15],
            ['name' => 'WordPress', 'slug' => 'wordpress', 'icon' => 'fab fa-wordpress', 'color' => 'blue', 'questions' => 15],
            ['name' => 'IA', 'slug' => 'ia', 'icon' => 'fas fa-robot', 'color' => 'green', 'questions' => 15],
        ];
        
        return view('quiz', compact('languages'));
    }

    public function quizLanguage($language)
    {
        $questions = $this->getQuizQuestions($language);
        
        if (empty($questions)) {
            abort(404);
        }
        
        return view('quiz-language', compact('language', 'questions'));
    }

    public function quizSubmit(Request $request, $language)
    {
        $questions = $this->getQuizQuestions($language);
        $answers = $request->input('answers', []);
        
        $score = 0;
        $total = count($questions);
        $results = [];
        
        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            $isCorrect = $userAnswer == $question['correct'];
            
            if ($isCorrect) {
                $score++;
            }
            
            $results[] = [
                'question' => $question['question'],
                'userAnswer' => $userAnswer,
                'correctAnswer' => $question['correct'],
                'isCorrect' => $isCorrect,
                'options' => $question['options']
            ];
        }
        
        $percentage = ($score / $total) * 100;
        
        // Stocker les résultats en session pour éviter la re-soumission
        session([
            'quiz_results_' . $language => [
                'score' => $score,
                'total' => $total,
                'percentage' => $percentage,
                'results' => $results
            ]
        ]);
        
        // Rediriger vers la route GET pour afficher les résultats (pattern Post-Redirect-Get)
        return redirect()->route('quiz.result', $language);
    }
    
    public function quizResult($language)
    {
        // Récupérer les résultats depuis la session
        $sessionKey = 'quiz_results_' . $language;
        $quizData = session($sessionKey);
        
        if (!$quizData) {
            // Si pas de résultats en session, rediriger vers le quiz
            return redirect()->route('quiz.language', $language)
                ->with('error', 'Aucun résultat de quiz trouvé. Veuillez compléter le quiz d\'abord.');
        }
        
        // Supprimer les résultats de la session après récupération (une seule fois)
        session()->forget($sessionKey);
        
        return view('quiz-result', [
            'language' => $language,
            'score' => $quizData['score'],
            'total' => $quizData['total'],
            'percentage' => $quizData['percentage'],
            'results' => $quizData['results']
        ]);
    }

    private function getQuizQuestions($language)
    {
        $allQuestions = [
            'html5' => [
                ['question' => 'Que signifie HTML ?', 'options' => ['Hyper Text Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language'], 'correct' => 0],
                ['question' => 'Quelle balise est utilisée pour créer un lien hypertexte ?', 'options' => ['<link>', '<a>', '<href>', '<url>'], 'correct' => 1],
                ['question' => 'Quelle balise définit le titre d\'une page HTML ?', 'options' => ['<head>', '<title>', '<meta>', '<header>'], 'correct' => 1],
                ['question' => 'Quelle balise est utilisée pour insérer une image ?', 'options' => ['<image>', '<img>', '<picture>', '<src>'], 'correct' => 1],
                ['question' => 'Quelle balise crée un paragraphe ?', 'options' => ['<para>', '<p>', '<paragraph>', '<text>'], 'correct' => 1],
                ['question' => 'Quelle balise crée une liste non ordonnée ?', 'options' => ['<ol>', '<ul>', '<list>', '<li>'], 'correct' => 1],
                ['question' => 'Quel attribut spécifie une URL alternative pour une image ?', 'options' => ['title', 'alt', 'src', 'href'], 'correct' => 1],
                ['question' => 'Quelle balise définit un tableau ?', 'options' => ['<table>', '<tab>', '<tr>', '<td>'], 'correct' => 0],
                ['question' => 'Quelle balise crée un titre de niveau 1 ?', 'options' => ['<heading>', '<h1>', '<title>', '<head>'], 'correct' => 1],
                ['question' => 'Quelle balise définit une ligne dans un tableau ?', 'options' => ['<td>', '<tr>', '<th>', '<table>'], 'correct' => 1],
                ['question' => 'Quel attribut ouvre un lien dans un nouvel onglet ?', 'options' => ['new', 'target="_blank"', 'open', 'window'], 'correct' => 1],
                ['question' => 'Quelle balise crée un formulaire ?', 'options' => ['<input>', '<form>', '<field>', '<submit>'], 'correct' => 1],
                ['question' => 'Quelle balise HTML5 définit une section ?', 'options' => ['<div>', '<section>', '<article>', '<part>'], 'correct' => 1],
                ['question' => 'Quelle balise crée un champ de saisie de texte ?', 'options' => ['<textbox>', '<input>', '<field>', '<text>'], 'correct' => 1],
                ['question' => 'Quelle balise définit un en-tête de page ?', 'options' => ['<head>', '<header>', '<top>', '<nav>'], 'correct' => 1],
                ['question' => 'Quelle balise crée une liste ordonnée ?', 'options' => ['<ul>', '<ol>', '<list>', '<order>'], 'correct' => 1],
                ['question' => 'Quel attribut définit l\'URL d\'un lien ?', 'options' => ['src', 'link', 'href', 'url'], 'correct' => 2],
                ['question' => 'Quelle balise définit un pied de page ?', 'options' => ['<bottom>', '<footer>', '<foot>', '<end>'], 'correct' => 1],
                ['question' => 'Quelle balise crée un bouton ?', 'options' => ['<btn>', '<button>', '<input type="button">', 'Les deux B et C'], 'correct' => 3],
                ['question' => 'Quelle balise HTML5 définit une navigation ?', 'options' => ['<navigate>', '<nav>', '<menu>', '<links>'], 'correct' => 1],
            ],
            'css3' => [
                ['question' => 'Que signifie CSS ?', 'options' => ['Cascading Style Sheets', 'Computer Style Sheets', 'Creative Style Sheets', 'Colorful Style Sheets'], 'correct' => 0],
                ['question' => 'Quelle propriété change la couleur du texte ?', 'options' => ['text-color', 'color', 'font-color', 'text-style'], 'correct' => 1],
                ['question' => 'Comment ajouter une couleur de fond ?', 'options' => ['color', 'background-color', 'bg-color', 'bgcolor'], 'correct' => 1],
                ['question' => 'Quelle propriété change la taille de la police ?', 'options' => ['text-size', 'font-size', 'text-style', 'font-weight'], 'correct' => 1],
                ['question' => 'Comment centrer un texte ?', 'options' => ['text-align: center', 'align: center', 'text: center', 'center: text'], 'correct' => 0],
                ['question' => 'Quelle propriété ajoute une bordure ?', 'options' => ['outline', 'border', 'edge', 'frame'], 'correct' => 1],
                ['question' => 'Comment rendre un élément invisible ?', 'options' => ['visibility: hidden', 'display: none', 'opacity: 0', 'Toutes les réponses'], 'correct' => 3],
                ['question' => 'Quelle propriété définit l\'espacement interne ?', 'options' => ['margin', 'padding', 'spacing', 'border'], 'correct' => 1],
                ['question' => 'Quelle propriété définit l\'espacement externe ?', 'options' => ['margin', 'padding', 'spacing', 'border'], 'correct' => 0],
                ['question' => 'Comment sélectionner une classe en CSS ?', 'options' => ['#classe', '.classe', '*classe', 'classe'], 'correct' => 1],
                ['question' => 'Comment sélectionner un ID en CSS ?', 'options' => ['#id', '.id', '*id', 'id'], 'correct' => 0],
                ['question' => 'Quelle propriété change la police ?', 'options' => ['font-family', 'font-type', 'font-style', 'text-font'], 'correct' => 0],
                ['question' => 'Comment mettre un texte en gras ?', 'options' => ['font-weight: bold', 'text-style: bold', 'font-bold', 'text-weight: bold'], 'correct' => 0],
                ['question' => 'Quelle propriété contrôle la largeur ?', 'options' => ['width', 'size', 'length', 'dimension'], 'correct' => 0],
                ['question' => 'Quelle propriété contrôle la hauteur ?', 'options' => ['height', 'size', 'length', 'dimension'], 'correct' => 0],
                ['question' => 'Comment créer une animation CSS ?', 'options' => ['@animation', '@keyframes', '@animate', '@motion'], 'correct' => 1],
                ['question' => 'Quelle propriété définit la position ?', 'options' => ['position', 'location', 'place', 'pos'], 'correct' => 0],
                ['question' => 'Quelle valeur de display crée un flexbox ?', 'options' => ['flex', 'flexbox', 'flexible', 'box'], 'correct' => 0],
                ['question' => 'Quelle valeur de display crée une grille ?', 'options' => ['grid', 'table', 'gridbox', 'layout'], 'correct' => 0],
                ['question' => 'Comment arrondir les coins ?', 'options' => ['corner-radius', 'border-radius', 'round-corner', 'border-round'], 'correct' => 1],
            ],
            'javascript' => [
                ['question' => 'Comment déclarer une variable en JavaScript ?', 'options' => ['var x', 'variable x', 'v x', 'dim x'], 'correct' => 0],
                ['question' => 'Quelle méthode affiche un message dans la console ?', 'options' => ['console.log()', 'print()', 'echo()', 'display()'], 'correct' => 0],
                ['question' => 'Comment créer une fonction ?', 'options' => ['function myFunc()', 'def myFunc()', 'func myFunc()', 'create myFunc()'], 'correct' => 0],
                ['question' => 'Quel opérateur teste l\'égalité stricte ?', 'options' => ['==', '===', '=', '!='], 'correct' => 1],
                ['question' => 'Comment ajouter un commentaire sur une ligne ?', 'options' => ['// commentaire', '/* commentaire */', '# commentaire', '-- commentaire'], 'correct' => 0],
                ['question' => 'Quelle méthode sélectionne un élément par ID ?', 'options' => ['getElementById()', 'getElement()', 'selectById()', 'findById()'], 'correct' => 0],
                ['question' => 'Comment créer un tableau ?', 'options' => ['var arr = []', 'var arr = ()', 'var arr = {}', 'var arr = <>'], 'correct' => 0],
                ['question' => 'Quelle méthode ajoute un élément à la fin d\'un tableau ?', 'options' => ['add()', 'push()', 'append()', 'insert()'], 'correct' => 1],
                ['question' => 'Comment créer une boucle for ?', 'options' => ['for (i = 0; i < 5; i++)', 'for i = 0 to 5', 'for (i in 5)', 'loop (i < 5)'], 'correct' => 0],
                ['question' => 'Quelle méthode convertit une chaîne en nombre ?', 'options' => ['parseInt()', 'toNumber()', 'convert()', 'number()'], 'correct' => 0],
                ['question' => 'Comment créer un objet ?', 'options' => ['var obj = []', 'var obj = ()', 'var obj = {}', 'var obj = <>'], 'correct' => 2],
                ['question' => 'Quelle méthode retourne la longueur d\'une chaîne ?', 'options' => ['length', 'size()', 'count()', 'len()'], 'correct' => 0],
                ['question' => 'Comment ajouter un événement click ?', 'options' => ['element.click()', 'element.addEventListener("click")', 'element.onClick()', 'element.addClick()'], 'correct' => 1],
                ['question' => 'Quel mot-clé définit une constante ?', 'options' => ['var', 'let', 'const', 'constant'], 'correct' => 2],
                ['question' => 'Comment arrondir un nombre ?', 'options' => ['Math.round()', 'round()', 'Math.ceil()', 'number.round()'], 'correct' => 0],
                ['question' => 'Quelle méthode génère un nombre aléatoire ?', 'options' => ['Math.random()', 'random()', 'Math.rand()', 'getRandom()'], 'correct' => 0],
                ['question' => 'Comment vérifier le type d\'une variable ?', 'options' => ['typeof', 'type()', 'getType()', 'varType()'], 'correct' => 0],
                ['question' => 'Quelle méthode supprime le dernier élément d\'un tableau ?', 'options' => ['remove()', 'pop()', 'delete()', 'removeLast()'], 'correct' => 1],
                ['question' => 'Comment créer une condition if ?', 'options' => ['if (x > 5)', 'if x > 5', 'if (x > 5) then', 'if x > 5 then'], 'correct' => 0],
                ['question' => 'Quelle méthode transforme un tableau en chaîne ?', 'options' => ['toString()', 'join()', 'concat()', 'Les deux A et B'], 'correct' => 3],
            ],
            'php' => [
                ['question' => 'Que signifie PHP ?', 'options' => ['Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Home Page', 'Public HTML Page'], 'correct' => 1],
                ['question' => 'Comment commence une variable en PHP ?', 'options' => ['@', '#', '$', '&'], 'correct' => 2],
                ['question' => 'Comment afficher du texte en PHP ?', 'options' => ['echo', 'print', 'display', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment créer un commentaire sur une ligne ?', 'options' => ['// commentaire', '# commentaire', '/* commentaire */', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Quelle fonction retourne la longueur d\'une chaîne ?', 'options' => ['strlen()', 'length()', 'size()', 'count()'], 'correct' => 0],
                ['question' => 'Comment créer un tableau en PHP ?', 'options' => ['array()', '[]', '$arr = []', 'Toutes les réponses'], 'correct' => 3],
                ['question' => 'Quelle superglobale contient les données POST ?', 'options' => ['$POST', '$_POST', '$FORM', '$_FORM'], 'correct' => 1],
                ['question' => 'Comment inclure un fichier PHP ?', 'options' => ['include', 'require', 'import', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Quelle fonction vérifie si une variable existe ?', 'options' => ['isset()', 'exists()', 'defined()', 'check()'], 'correct' => 0],
                ['question' => 'Comment créer une fonction ?', 'options' => ['function myFunc()', 'def myFunc()', 'func myFunc()', 'create myFunc()'], 'correct' => 0],
                ['question' => 'Quelle fonction connecte à MySQL ?', 'options' => ['mysql_connect()', 'mysqli_connect()', 'connect_mysql()', 'db_connect()'], 'correct' => 1],
                ['question' => 'Comment démarrer une session ?', 'options' => ['session_start()', 'start_session()', 'session_begin()', 'begin_session()'], 'correct' => 0],
                ['question' => 'Quelle superglobale contient les cookies ?', 'options' => ['$COOKIE', '$_COOKIE', '$COOKIES', '$_COOKIES'], 'correct' => 1],
                ['question' => 'Comment rediriger vers une autre page ?', 'options' => ['header("Location: page.php")', 'redirect("page.php")', 'goto("page.php")', 'location("page.php")'], 'correct' => 0],
                ['question' => 'Quelle fonction retourne le type d\'une variable ?', 'options' => ['gettype()', 'typeof()', 'type()', 'vartype()'], 'correct' => 0],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MyClass {}', 'create class MyClass', 'new class MyClass', 'define class MyClass'], 'correct' => 0],
                ['question' => 'Quelle fonction compte les éléments d\'un tableau ?', 'options' => ['count()', 'length()', 'size()', 'sizeof()'], 'correct' => 0],
                ['question' => 'Comment vérifier si un fichier existe ?', 'options' => ['file_exists()', 'exists()', 'is_file()', 'check_file()'], 'correct' => 0],
                ['question' => 'Quelle fonction convertit en entier ?', 'options' => ['intval()', 'int()', 'toInt()', 'parseInt()'], 'correct' => 0],
                ['question' => 'Comment créer une constante ?', 'options' => ['define("NAME", "value")', 'const NAME = "value"', 'constant NAME = "value"', 'Les deux A et B'], 'correct' => 3],
            ],
            'bootstrap' => [
                ['question' => 'Que signifie Bootstrap ?', 'options' => ['Un framework CSS', 'Un langage de programmation', 'Une base de données', 'Un serveur web'], 'correct' => 0],
                ['question' => 'Quelle classe crée un conteneur responsive ?', 'options' => ['.container', '.responsive', '.wrapper', '.box'], 'correct' => 0],
                ['question' => 'Quelle classe crée une ligne dans la grille ?', 'options' => ['.line', '.row', '.grid', '.flex'], 'correct' => 1],
                ['question' => 'Quelle classe crée une colonne ?', 'options' => ['.column', '.col', '.grid-col', '.cell'], 'correct' => 1],
                ['question' => 'Quelle classe crée un bouton primaire ?', 'options' => ['.btn-primary', '.button-primary', '.primary-btn', '.btn-blue'], 'correct' => 0],
                ['question' => 'Quelle classe centre le texte ?', 'options' => ['.center', '.text-center', '.align-center', '.middle'], 'correct' => 1],
                ['question' => 'Quelle classe crée une alerte ?', 'options' => ['.alert', '.message', '.notification', '.warning'], 'correct' => 0],
                ['question' => 'Quelle classe crée une navbar ?', 'options' => ['.navigation', '.navbar', '.nav', '.menu'], 'correct' => 1],
                ['question' => 'Quelle classe crée une card ?', 'options' => ['.card', '.box', '.panel', '.container'], 'correct' => 0],
                ['question' => 'Quelle classe ajoute une marge en haut ?', 'options' => ['.margin-top', '.mt-3', '.top-margin', '.m-top'], 'correct' => 1],
                ['question' => 'Quelle classe crée un badge ?', 'options' => ['.badge', '.label', '.tag', '.chip'], 'correct' => 0],
                ['question' => 'Quelle classe crée une modal ?', 'options' => ['.modal', '.popup', '.dialog', '.window'], 'correct' => 0],
                ['question' => 'Quelle classe masque un élément sur mobile ?', 'options' => ['.hide-mobile', '.d-none d-md-block', '.mobile-hide', '.hidden-xs'], 'correct' => 1],
                ['question' => 'Quelle classe crée un tableau rayé ?', 'options' => ['.table-striped', '.striped-table', '.table-zebra', '.zebra'], 'correct' => 0],
                ['question' => 'Quelle classe crée un formulaire inline ?', 'options' => ['.form-inline', '.inline-form', '.form-horizontal', '.horizontal'], 'correct' => 0],
            ],
            'git' => [
                ['question' => 'Que signifie Git ?', 'options' => ['Global Information Tracker', 'Un système de contrôle de version', 'Un langage de programmation', 'Un éditeur de code'], 'correct' => 1],
                ['question' => 'Quelle commande initialise un dépôt Git ?', 'options' => ['git start', 'git init', 'git create', 'git new'], 'correct' => 1],
                ['question' => 'Quelle commande ajoute tous les fichiers ?', 'options' => ['git add *', 'git add .', 'git add all', 'git add --all'], 'correct' => 1],
                ['question' => 'Quelle commande crée un commit ?', 'options' => ['git commit', 'git save', 'git push', 'git create'], 'correct' => 0],
                ['question' => 'Quelle commande envoie vers le dépôt distant ?', 'options' => ['git send', 'git upload', 'git push', 'git commit'], 'correct' => 2],
                ['question' => 'Quelle commande récupère les changements ?', 'options' => ['git get', 'git fetch', 'git pull', 'git download'], 'correct' => 2],
                ['question' => 'Quelle commande crée une branche ?', 'options' => ['git branch nom', 'git create nom', 'git new nom', 'git add nom'], 'correct' => 0],
                ['question' => 'Quelle commande change de branche ?', 'options' => ['git change', 'git switch', 'git checkout', 'Les deux B et C'], 'correct' => 3],
                ['question' => 'Quelle commande fusionne des branches ?', 'options' => ['git merge', 'git combine', 'git join', 'git fusion'], 'correct' => 0],
                ['question' => 'Quelle commande affiche l\'historique ?', 'options' => ['git history', 'git log', 'git show', 'git list'], 'correct' => 1],
                ['question' => 'Quelle commande affiche le statut ?', 'options' => ['git status', 'git state', 'git info', 'git check'], 'correct' => 0],
                ['question' => 'Quelle commande clone un dépôt ?', 'options' => ['git copy', 'git clone', 'git download', 'git get'], 'correct' => 1],
                ['question' => 'Quelle commande annule les modifications ?', 'options' => ['git undo', 'git reset', 'git revert', 'git cancel'], 'correct' => 1],
                ['question' => 'Quelle commande affiche les différences ?', 'options' => ['git diff', 'git compare', 'git changes', 'git show'], 'correct' => 0],
                ['question' => 'Quelle commande configure le nom d\'utilisateur ?', 'options' => ['git config user.name', 'git set name', 'git user name', 'git name'], 'correct' => 0],
            ],
            'wordpress' => [
                ['question' => 'Que signifie WordPress ?', 'options' => ['Un CMS', 'Un langage de programmation', 'Une base de données', 'Un serveur'], 'correct' => 0],
                ['question' => 'Quelle fonction affiche le titre ?', 'options' => ['get_title()', 'the_title()', 'show_title()', 'title()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche le contenu ?', 'options' => ['get_content()', 'the_content()', 'show_content()', 'content()'], 'correct' => 1],
                ['question' => 'Quelle boucle affiche les articles ?', 'options' => ['The Loop', 'Post Loop', 'Article Loop', 'Content Loop'], 'correct' => 0],
                ['question' => 'Quelle fonction vérifie s\'il y a des articles ?', 'options' => ['has_posts()', 'have_posts()', 'check_posts()', 'posts_exist()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche l\'image à la une ?', 'options' => ['the_thumbnail()', 'the_post_thumbnail()', 'featured_image()', 'post_image()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche le menu ?', 'options' => ['wp_menu()', 'wp_nav_menu()', 'the_menu()', 'show_menu()'], 'correct' => 1],
                ['question' => 'Quel fichier contient les fonctions du thème ?', 'options' => ['functions.php', 'theme.php', 'config.php', 'setup.php'], 'correct' => 0],
                ['question' => 'Quel fichier est le template principal ?', 'options' => ['main.php', 'index.php', 'home.php', 'template.php'], 'correct' => 1],
                ['question' => 'Quelle fonction enregistre un menu ?', 'options' => ['register_menu()', 'register_nav_menu()', 'add_menu()', 'create_menu()'], 'correct' => 1],
                ['question' => 'Quelle fonction enregistre un widget ?', 'options' => ['register_widget()', 'register_sidebar()', 'add_widget()', 'create_widget()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche le footer ?', 'options' => ['wp_footer()', 'get_footer()', 'the_footer()', 'show_footer()'], 'correct' => 0],
                ['question' => 'Quelle fonction affiche le header ?', 'options' => ['wp_header()', 'get_header()', 'the_header()', 'show_header()'], 'correct' => 1],
                ['question' => 'Quelle fonction récupère une option ?', 'options' => ['get_option()', 'option()', 'wp_option()', 'the_option()'], 'correct' => 0],
                ['question' => 'Quelle fonction crée un custom post type ?', 'options' => ['create_post_type()', 'register_post_type()', 'add_post_type()', 'new_post_type()'], 'correct' => 1],
            ],
            'ia' => [
                ['question' => 'Que signifie IA ?', 'options' => ['Intelligence Artificielle', 'Information Automatique', 'Internet Avancé', 'Interface Automatisée'], 'correct' => 0],
                ['question' => 'Que signifie ML ?', 'options' => ['Machine Learning', 'Modern Language', 'Multiple Layers', 'Memory Learning'], 'correct' => 0],
                ['question' => 'Quel type d\'apprentissage utilise des données étiquetées ?', 'options' => ['Non supervisé', 'Supervisé', 'Par renforcement', 'Semi-supervisé'], 'correct' => 1],
                ['question' => 'Quelle bibliothèque Python est populaire pour l\'IA ?', 'options' => ['TensorFlow', 'Django', 'Flask', 'Pandas'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un neurone artificiel ?', 'options' => ['Une unité de calcul', 'Un algorithme', 'Un langage', 'Un framework'], 'correct' => 0],
                ['question' => 'Que signifie CNN ?', 'options' => ['Convolutional Neural Network', 'Computer Neural Network', 'Complex Neural Network', 'Connected Neural Network'], 'correct' => 0],
                ['question' => 'Que signifie RNN ?', 'options' => ['Recurrent Neural Network', 'Random Neural Network', 'Recursive Neural Network', 'Regular Neural Network'], 'correct' => 0],
                ['question' => 'Quel algorithme est utilisé pour la classification ?', 'options' => ['K-means', 'Decision Tree', 'Linear Regression', 'PCA'], 'correct' => 1],
                ['question' => 'Que signifie NLP ?', 'options' => ['Natural Language Processing', 'Neural Learning Process', 'Network Layer Protocol', 'New Learning Pattern'], 'correct' => 0],
                ['question' => 'Quel est l\'objectif du Deep Learning ?', 'options' => ['Apprendre des représentations', 'Stocker des données', 'Créer des sites web', 'Gérer des bases de données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un dataset ?', 'options' => ['Un ensemble de données', 'Un algorithme', 'Un réseau', 'Un langage'], 'correct' => 0],
                ['question' => 'Que signifie GPU ?', 'options' => ['Graphics Processing Unit', 'General Processing Unit', 'Global Processing Unit', 'Graphical Program Unit'], 'correct' => 0],
                ['question' => 'Quel est le rôle d\'une fonction d\'activation ?', 'options' => ['Introduire de la non-linéarité', 'Stocker des données', 'Compiler du code', 'Créer des graphiques'], 'correct' => 0],
                ['question' => 'Que signifie overfitting ?', 'options' => ['Surapprentissage', 'Sous-apprentissage', 'Apprentissage optimal', 'Apprentissage rapide'], 'correct' => 0],
                ['question' => 'Quelle technique réduit l\'overfitting ?', 'options' => ['Dropout', 'Augmentation', 'Compilation', 'Optimisation'], 'correct' => 0],
            ],
        ];

        return $allQuestions[$language] ?? [];
    }

    public function legal()
    {
        return view('legal');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà inscrite à notre newsletter.'
        ]);

        $token = \Str::random(64);

        \App\Models\Newsletter::create([
            'email' => $request->email,
            'token' => $token,
            'is_active' => true,
            'subscribed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Merci pour votre inscription ! Vous recevrez bientôt nos actualités.'
        ]);
    }

    public function newsletterUnsubscribe($token)
    {
        $subscriber = \App\Models\Newsletter::where('token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Lien de désinscription invalide.');
        }

        $subscriber->update(['is_active' => false]);

        return redirect()->route('home')->with('success', 'Vous avez été désinscrit de notre newsletter.');
    }

    // Formations
    public function html5()
    {
        return view('formations.html5');
    }

    public function css3()
    {
        return view('formations.css3');
    }

    public function javascript()
    {
        return view('formations.javascript');
    }

    public function php()
    {
        return view('formations.php');
    }

    public function bootstrap()
    {
        return view('formations.bootstrap');
    }

    public function git()
    {
        return view('formations.git');
    }

    public function wordpress()
    {
        return view('formations.wordpress');
    }

    public function ia()
    {
        return view('formations.ia');
    }

    // Pages Emplois
    public function emplois()
    {
        // Cache les catégories actives avec sélection optimisée (15 minutes - réduit pour avoir des données plus fraîches)
        $categories = \Illuminate\Support\Facades\Cache::remember('active_categories', 900, function () {
            $categories = \App\Models\Category::where('is_active', true)
                ->withCount([
                    'articles' => function($query) {
                        $query->where('status', 'published');
                    }
                ])
                ->select('id', 'name', 'slug', 'description', 'icon', 'image', 'image_type', 'order')
                ->orderBy('order')
                ->get();
            
            // S'assurer que le count est accessible et calculé correctement
            foreach ($categories as $category) {
                // Utiliser articles_count qui est créé par withCount
                // Si articles_count n'existe pas, calculer directement
                if (!isset($category->articles_count)) {
                    $category->articles_count = \App\Models\JobArticle::where('category_id', $category->id)
                        ->where('status', 'published')
                        ->count();
                }
                $category->published_articles_count = $category->articles_count ?? 0;
            }
            
            return $categories;
        });
        
        // Cache les 6 derniers articles avec sélection optimisée (15 minutes)
        $recentArticles = \Illuminate\Support\Facades\Cache::remember('recent_job_articles', 900, function () {
            return \App\Models\JobArticle::where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(6)
                ->get();
        });
        
        return view('emplois.index', compact('categories', 'recentArticles'));
    }

    public function offresEmploi(Request $request)
    {
        $categorySlug = $request->get('category');
        $page = $request->get('page', 1);
        
        // Cache la catégorie (1 heure)
        if ($categorySlug) {
            $category = \Illuminate\Support\Facades\Cache::remember("category_{$categorySlug}", 3600, function () use ($categorySlug) {
                return \App\Models\Category::where('slug', $categorySlug)->first();
            });
        } else {
            $category = \Illuminate\Support\Facades\Cache::remember('category_offres-emploi', 3600, function () {
                return \App\Models\Category::where('slug', 'offres-emploi')->first();
            });
        }
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = $category ? "job_articles_category_{$category->id}_page_{$page}" : "job_articles_all_page_{$page}";
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            $query = \App\Models\JobArticle::where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'created_at', 'updated_at');
            
            if ($category) {
                $query->where('category_id', $category->id);
            }
            
            return $query->orderBy('published_at', 'desc')->paginate(10);
        });
        
        return view('emplois.offres', compact('articles', 'category'));
    }

    public function bourses()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_bourses-etudes', 3600, function () {
            return \App\Models\Category::where('slug', 'bourses-etudes')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = $category ? "job_articles_bourses_page_" . request()->get('page', 1) : "job_articles_bourses_all_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.bourses', compact('articles', 'category'));
    }

    public function candidatureSpontanee()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_candidature-spontanee', 3600, function () {
            return \App\Models\Category::where('slug', 'candidature-spontanee')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = "job_articles_candidature_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.candidature', compact('articles', 'category'));
    }

    public function opportunites()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_opportunites-professionnelles', 3600, function () {
            return \App\Models\Category::where('slug', 'opportunites-professionnelles')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = "job_articles_opportunites_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.opportunites', compact('articles', 'category'));
    }

    public function concours()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_concours', 3600, function () {
            return \App\Models\Category::where('slug', 'concours')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = "job_articles_concours_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.concours', compact('articles', 'category'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', '');
        $dateFilter = $request->get('date', '');
        $sortBy = $request->get('sort', 'relevance');
        
        $results = [
            'formations' => collect(),
            'articles' => collect(),
            'total' => 0
        ];
        
        // Récupérer toutes les catégories pour les filtres
        $categories = \Illuminate\Support\Facades\Cache::remember('all_categories_search', 3600, function () {
            return \App\Models\Category::where('is_active', true)
                ->orderBy('name')
                ->get();
        });
        
        if (strlen($query) >= 2) {
            // Recherche dans les formations (titres et descriptions)
            $formations = collect([
                ['name' => 'HTML5', 'url' => route('formations.html5'), 'description' => 'Formation complète HTML5'],
                ['name' => 'CSS3', 'url' => route('formations.css3'), 'description' => 'Formation complète CSS3'],
                ['name' => 'JavaScript', 'url' => route('formations.javascript'), 'description' => 'Formation complète JavaScript'],
                ['name' => 'PHP', 'url' => route('formations.php'), 'description' => 'Formation complète PHP'],
                // Laravel route n'existe pas encore
                // ['name' => 'Laravel', 'url' => route('formations.laravel'), 'description' => 'Formation complète Laravel'],
                ['name' => 'Bootstrap', 'url' => route('formations.bootstrap'), 'description' => 'Formation complète Bootstrap'],
                ['name' => 'Git', 'url' => route('formations.git'), 'description' => 'Formation complète Git'],
                ['name' => 'WordPress', 'url' => route('formations.wordpress'), 'description' => 'Formation complète WordPress'],
                ['name' => 'Intelligence Artificielle', 'url' => route('formations.ia'), 'description' => 'Formation Intelligence Artificielle'],
            ])->filter(function($formation) use ($query) {
                return stripos($formation['name'], $query) !== false || 
                       stripos($formation['description'], $query) !== false;
            });
            
            // Recherche dans les articles d'emploi publiés avec filtres
            $articlesQuery = \App\Models\JobArticle::where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%");
                });
            
            // Filtre par catégorie
            if ($category) {
                $articlesQuery->where('category_id', $category);
            }
            
            // Filtre par date
            if ($dateFilter) {
                switch($dateFilter) {
                    case 'today':
                        $articlesQuery->whereDate('published_at', today());
                        break;
                    case 'week':
                        $articlesQuery->where('published_at', '>=', now()->subWeek());
                        break;
                    case 'month':
                        $articlesQuery->where('published_at', '>=', now()->subMonth());
                        break;
                    case 'year':
                        $articlesQuery->where('published_at', '>=', now()->subYear());
                        break;
                }
            }
            
            // Tri
            switch($sortBy) {
                case 'date':
                    $articlesQuery->orderBy('published_at', 'desc');
                    break;
                case 'views':
                    $articlesQuery->orderBy('views', 'desc');
                    break;
                case 'title':
                    $articlesQuery->orderBy('title', 'asc');
                    break;
                case 'relevance':
                default:
                    $articlesQuery->orderBy('published_at', 'desc');
                    break;
            }
            
            $cacheKey = "search_articles_{$query}_{$category}_{$dateFilter}_{$sortBy}";
            $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($articlesQuery) {
                return $articlesQuery->with('category')->limit(50)->get();
            });
            
            $results['formations'] = $formations;
            $results['articles'] = $articles;
            $results['total'] = $formations->count() + $articles->count();
        }
        
        return view('search', compact('query', 'results', 'categories', 'category', 'dateFilter', 'sortBy'));
    }

    public function showArticle($slug)
    {
        // Cache l'article avec sélection optimisée (30 minutes)
        $article = \Illuminate\Support\Facades\Cache::remember("job_article_{$slug}", 1800, function () use ($slug) {
            return \App\Models\JobArticle::where('slug', $slug)
                ->where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'content', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'meta_title', 'meta_description', 'meta_keywords', 'created_at', 'updated_at')
                ->firstOrFail();
        });
        
        // Incrémenter les vues de manière optimisée (sans recharger l'article)
        \App\Models\JobArticle::where('id', $article->id)->increment('views');
        
        // Cache les articles similaires avec sélection optimisée (15 minutes)
        $relatedArticles = \Illuminate\Support\Facades\Cache::remember("related_articles_{$article->id}", 900, function () use ($article) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
        });
        
        // Cache les publicités pour la sidebar des articles (30 minutes)
        $sidebarAds = \Illuminate\Support\Facades\Cache::remember('sidebar_ads_articles', 1800, function () {
            return \App\Models\Ad::active()
                ->forPosition('content')
                ->where(function($q) {
                    $q->whereNull('location')
                      ->orWhere('location', 'article_sidebar');
                })
                ->select('id', 'name', 'description', 'image', 'image_type', 'link_url')
                ->orderBy('order')
                ->get();
        });

        // Cache les 3 derniers commentaires approuvés avec sélection optimisée (15 minutes)
        $latestComments = \Illuminate\Support\Facades\Cache::remember("article_latest_comments_{$article->id}", 900, function () use ($article) {
            return \App\Models\Comment::where('commentable_type', 'App\\Models\\JobArticle')
                ->where('commentable_id', $article->id)
                ->where('status', 'approved')
                ->whereNull('parent_id')
                ->select('id', 'name', 'email', 'content', 'created_at', 'user_id')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        });

        // Cache les commentaires avec sélection optimisée (15 minutes) - Exclure les 3 derniers pour éviter les doublons
        $latestCommentIds = $latestComments->pluck('id')->toArray();
        $comments = \Illuminate\Support\Facades\Cache::remember("article_comments_{$article->id}", 900, function () use ($article, $latestCommentIds) {
            $query = \App\Models\Comment::where('commentable_type', 'App\\Models\\JobArticle')
                ->where('commentable_id', $article->id)
                ->where('status', 'approved')
                ->select('id', 'name', 'email', 'content', 'parent_id', 'created_at', 'user_id');
            if (!empty($latestCommentIds)) {
                $query->whereNotIn('id', $latestCommentIds);
            }
            $comments = $query->orderBy('created_at', 'desc')->get();
            
            // Charger les réponses de manière optimisée
            $commentIds = $comments->pluck('id')->toArray();
            if (!empty($commentIds)) {
                $replies = \App\Models\Comment::whereIn('parent_id', $commentIds)
                    ->where('status', 'approved')
                    ->select('id', 'name', 'email', 'content', 'parent_id', 'created_at', 'user_id')
                    ->get()
                    ->groupBy('parent_id');
                
                foreach ($comments as $comment) {
                    $comment->setRelation('replies', $replies->get($comment->id, collect()));
                }
            }
            
            return $comments;
        });
        
        return view('emplois.article', compact('article', 'relatedArticles', 'sidebarAds', 'comments', 'latestComments'));
    }
}
