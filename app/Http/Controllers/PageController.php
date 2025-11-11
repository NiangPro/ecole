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
            'bootstrap' => [
                ['title' => 'Grille Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Bouton Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Card Bootstrap', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Navbar Bootstrap', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Responsive Bootstrap', 'difficulty' => 'Difficile', 'points' => 20],
            ],
            'git' => [
                ['title' => 'Initialiser un dépôt Git', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Ajouter des fichiers', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Créer un commit', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'Créer une branche', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Fusionner des branches', 'difficulty' => 'Difficile', 'points' => 20],
            ],
            'wordpress' => [
                ['title' => 'The Loop WordPress', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Afficher le titre', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Image à la une', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Menu WordPress', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Custom Post Type', 'difficulty' => 'Difficile', 'points' => 20],
            ],
            'ia' => [
                ['title' => 'Concepts de base IA', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Machine Learning', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Réseaux de neurones', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Deep Learning', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Applications IA', 'difficulty' => 'Difficile', 'points' => 18],
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
