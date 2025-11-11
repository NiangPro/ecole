<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class PageController extends Controller
{
    public function index()
    {
        return view('index');
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
        $exercises = $this->getExercisesByLanguage($language);
        
        return view('exercices-language', compact('language', 'exercises'));
    }

    public function exerciceDetail($language, $id)
    {
        $exercise = $this->getExerciseDetail($language, $id);
        
        if (!$exercise) {
            abort(404);
        }
        
        return view('exercice-detail', compact('language', 'id', 'exercise'));
    }

    public function exerciceSubmit(Request $request, $language, $id)
    {
        $exercise = $this->getExerciseDetail($language, $id);
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
                ['title' => 'Les sélecteurs CSS', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Flexbox - Centrage', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Grid Layout', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Animations CSS', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Responsive Design', 'difficulty' => 'Moyen', 'points' => 15],
            ],
            'javascript' => [
                ['title' => 'Variables et types', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Fonctions', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'DOM Manipulation', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Tableaux et boucles', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Objets JavaScript', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'php' => [
                ['title' => 'Syntaxe de base PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Variables PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Conditions PHP', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Boucles PHP', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Tableaux PHP', 'difficulty' => 'Difficile', 'points' => 25],
            ],
        ];

        return $allExercises[$language] ?? [];
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
}
