<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobArticle;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateJobArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::where('is_active', true)->get();
        
        if ($categories->isEmpty()) {
            $this->command->warn('Aucune catégorie active trouvée. Veuillez créer des catégories d\'abord.');
            return;
        }
        
        $images = [
            'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1556761175-b413da4baf72?w=1200&h=630&fit=crop',
        ];
        
        $articles = [
            [
                'title' => 'Opportunités de Développement Web au Sénégal : Recrutement Actif',
                'excerpt' => 'Le marché du développement web au Sénégal est en pleine expansion. Découvrez les meilleures opportunités pour développeurs PHP, JavaScript, Laravel et React.',
                'content' => '<h2>Le Marché du Développement Web au Sénégal</h2>
<p>Le secteur du développement web connaît une croissance exponentielle au Sénégal. Les entreprises locales et internationales recherchent activement des développeurs talentueux pour renforcer leurs équipes techniques.</p>

<h3>Postes Disponibles</h3>
<ul>
<li><strong>Développeur Full-Stack</strong> : Maîtrise de PHP, JavaScript, Laravel, React</li>
<li><strong>Développeur Frontend</strong> : Expertise en HTML5, CSS3, JavaScript, Vue.js</li>
<li><strong>Développeur Backend</strong> : Spécialisation en PHP, Laravel, Node.js</li>
<li><strong>Développeur Mobile</strong> : Compétences en Flutter, React Native</li>
</ul>

<h3>Compétences Recherchées</h3>
<p>Les entreprises recherchent des profils avec une solide expérience en développement web moderne, une bonne compréhension des frameworks populaires, et une capacité à travailler en équipe.</p>

<h3>Comment Postuler</h3>
<p>Envoyez votre CV et lettre de motivation aux entreprises concernées. Assurez-vous de mettre en avant vos projets et votre portfolio.</p>',
                'meta_keywords' => ['développement web', 'emploi Sénégal', 'développeur PHP', 'Laravel', 'React', 'JavaScript'],
            ],
            [
                'title' => 'Bourses d\'Études 2025 : Opportunités pour Étudiants Sénégalais',
                'excerpt' => 'Découvrez les meilleures bourses d\'études disponibles pour les étudiants sénégalais en 2025. Bourses nationales et internationales pour tous les niveaux.',
                'content' => '<h2>Bourses d\'Études Disponibles en 2025</h2>
<p>De nombreuses opportunités de bourses s\'offrent aux étudiants sénégalais souhaitant poursuivre leurs études supérieures, que ce soit au niveau national ou international.</p>

<h3>Types de Bourses</h3>
<ul>
<li><strong>Bourses Nationales</strong> : Programme de bourses du gouvernement sénégalais</li>
<li><strong>Bourses Internationales</strong> : Opportunités dans les universités européennes, américaines et asiatiques</li>
<li><strong>Bourses d\'Excellence</strong> : Pour les étudiants avec d\'excellents résultats académiques</li>
<li><strong>Bourses par Domaine</strong> : Spécialisées en technologie, médecine, ingénierie</li>
</ul>

<h3>Critères d\'Éligibilité</h3>
<p>Les critères varient selon le type de bourse, mais généralement incluent : excellents résultats académiques, motivation, et parfois des critères sociaux.</p>

<h3>Comment Candidater</h3>
<p>Renseignez-vous auprès des institutions concernées et préparez votre dossier de candidature avec tous les documents requis.</p>',
                'meta_keywords' => ['bourses', 'études', 'Sénégal', 'éducation', 'université'],
            ],
            [
                'title' => 'Stages Professionnels : Opportunités pour Jeunes Diplômés',
                'excerpt' => 'Trouvez le stage idéal pour débuter votre carrière. Opportunités de stages dans les entreprises technologiques, startups et grandes entreprises au Sénégal.',
                'content' => '<h2>Stages Professionnels au Sénégal</h2>
<p>Les stages professionnels sont une excellente opportunité pour les jeunes diplômés de mettre en pratique leurs connaissances et d\'acquérir une expérience professionnelle précieuse.</p>

<h3>Secteurs Recruteurs</h3>
<ul>
<li><strong>Technologie</strong> : Startups tech, entreprises de développement logiciel</li>
<li><strong>Finance</strong> : Banques, institutions financières</li>
<li><strong>Commerce</strong> : E-commerce, distribution</li>
<li><strong>Services</strong> : Consulting, services aux entreprises</li>
</ul>

<h3>Avantages des Stages</h3>
<p>Un stage vous permet d\'acquérir de l\'expérience pratique, de développer votre réseau professionnel, et souvent de décrocher un emploi permanent.</p>

<h3>Comment Trouver un Stage</h3>
<p>Consultez les plateformes d\'emploi, les sites des entreprises, et n\'hésitez pas à candidater directement auprès des entreprises qui vous intéressent.</p>',
                'meta_keywords' => ['stages', 'emploi', 'jeunes diplômés', 'carrière', 'expérience professionnelle'],
            ],
            [
                'title' => 'Formation Continue : Développez vos Compétences Professionnelles',
                'excerpt' => 'Investissez dans votre avenir professionnel avec des formations continues adaptées. Programmes de certification et formations courtes disponibles.',
                'content' => '<h2>Formation Continue Professionnelle</h2>
<p>La formation continue est essentielle pour rester compétitif sur le marché de l\'emploi. De nombreuses opportunités de formation sont disponibles au Sénégal.</p>

<h3>Types de Formations</h3>
<ul>
<li><strong>Certifications Professionnelles</strong> : Certifications reconnues internationalement</li>
<li><strong>Formations Techniques</strong> : Développement web, gestion de projet, marketing digital</li>
<li><strong>Formations en Ligne</strong> : Cours en ligne flexibles et accessibles</li>
<li><strong>Formations Présentielles</strong> : Sessions intensives avec formateurs experts</li>
</ul>

<h3>Bénéfices</h3>
<p>La formation continue vous permet de mettre à jour vos compétences, d\'apprendre de nouvelles technologies, et d\'améliorer vos perspectives de carrière.</p>

<h3>Financement</h3>
<p>Plusieurs options de financement existent : financement personnel, prise en charge par l\'employeur, ou financement public.</p>',
                'meta_keywords' => ['formation continue', 'certification', 'compétences', 'développement professionnel'],
            ],
            [
                'title' => 'Emploi Public : Concours et Recrutements dans la Fonction Publique',
                'excerpt' => 'Découvrez les concours et opportunités d\'emploi dans la fonction publique sénégalaise. Postes disponibles dans tous les secteurs d\'activité.',
                'content' => '<h2>Emploi dans la Fonction Publique</h2>
<p>La fonction publique offre de nombreuses opportunités d\'emploi stables et valorisantes pour les candidats qualifiés.</p>

<h3>Secteurs Recruteurs</h3>
<ul>
<li><strong>Administration</strong> : Postes administratifs dans les ministères</li>
<li><strong>Éducation</strong> : Enseignants, formateurs, personnel éducatif</li>
<li><strong>Santé</strong> : Personnel médical et paramédical</li>
<li><strong>Technique</strong> : Ingénieurs, techniciens spécialisés</li>
</ul>

<h3>Processus de Recrutement</h3>
<p>Le recrutement dans la fonction publique se fait généralement par concours. Les candidats doivent répondre aux critères d\'éligibilité et réussir les épreuves.</p>

<h3>Avantages</h3>
<p>L\'emploi public offre la stabilité, des avantages sociaux complets, et des opportunités d\'évolution de carrière.</p>',
                'meta_keywords' => ['fonction publique', 'concours', 'emploi public', 'administration'],
            ],
            [
                'title' => 'Startups Tech : Rejoignez l\'Écosystème Entrepreneurial Sénégalais',
                'excerpt' => 'Les startups technologiques au Sénégal recherchent des talents. Rejoignez une équipe dynamique et contribuez à l\'innovation technologique.',
                'content' => '<h2>Opportunités dans les Startups Tech</h2>
<p>L\'écosystème des startups technologiques au Sénégal est en pleine croissance et recherche activement des talents pour développer leurs projets innovants.</p>

<h3>Postes Disponibles</h3>
<ul>
<li><strong>Développeurs</strong> : Full-stack, frontend, backend, mobile</li>
<li><strong>Designers</strong> : UI/UX designers, graphistes</li>
<li><strong>Marketing</strong> : Growth hackers, community managers</li>
<li><strong>Business</strong> : Business developers, sales managers</li>
</ul>

<h3>Avantages de Travailler dans une Startup</h3>
<p>Travailler dans une startup vous permet d\'avoir un impact direct, d\'apprendre rapidement, et de participer à la croissance d\'une entreprise innovante.</p>

<h3>Comment Postuler</h3>
<p>Consultez les sites des startups, les plateformes d\'emploi spécialisées, et n\'hésitez pas à contacter directement les fondateurs.</p>',
                'meta_keywords' => ['startups', 'tech', 'innovation', 'entrepreneuriat', 'Sénégal'],
            ],
        ];
        
        $imageIndex = 0;
        
        foreach ($categories as $category) {
            // Trouver un article correspondant à la catégorie ou utiliser le premier disponible
            $articleData = $articles[$imageIndex % count($articles)] ?? $articles[0];
            
            // Adapter le titre et le contenu selon la catégorie
            $title = $articleData['title'];
            $excerpt = $articleData['excerpt'];
            $content = $articleData['content'];
            $keywords = $articleData['meta_keywords'];
            
            // Ajouter le nom de la catégorie dans les mots-clés
            $keywords[] = strtolower($category->name);
            
            $article = JobArticle::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . time() . '-' . $category->id,
                'excerpt' => $excerpt,
                'content' => $content,
                'category_id' => $category->id,
                'cover_image' => $images[$imageIndex % count($images)],
                'cover_type' => 'external',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(rand(0, 7)), // Articles très récents (0-7 jours)
                'meta_title' => $title,
                'meta_description' => $excerpt,
                'meta_keywords' => $keywords,
                'seo_score' => 100,
                'readability_score' => 85,
                'views' => rand(50, 500),
                'created_at' => Carbon::now()->subDays(rand(0, 7)),
                'updated_at' => Carbon::now()->subDays(rand(0, 7)),
            ]);
            
            $this->command->info("Article créé pour la catégorie : {$category->name} - {$article->title}");
            
            $imageIndex++;
        }
        
        $this->command->info('Tous les articles ont été créés avec succès !');
    }
}

