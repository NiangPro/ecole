<?php

namespace Database\Seeders;

use App\Models\EpreuveMatiere;
use Illuminate\Database\Seeder;

class EpreuveMatieresSeeder extends Seeder
{
    /**
     * Matières des programmes scolaires sénégalais (primaire → lycée / BTS).
     */
    public function run(): void
    {
        $matieres = [
            ['name' => 'Mathématiques',        'icon' => 'fa-square-root-variable'],
            ['name' => 'Physique-Chimie',      'icon' => 'fa-flask'],
            ['name' => 'SVT',                  'icon' => 'fa-dna'],
            ['name' => 'Français',             'icon' => 'fa-book-open'],
            ['name' => 'Philosophie',          'icon' => 'fa-brain'],
            ['name' => 'Anglais',              'icon' => 'fa-language'],
            ['name' => 'Histoire-Géographie',  'icon' => 'fa-earth-africa'],
            ['name' => 'Espagnol',             'icon' => 'fa-language'],
            ['name' => 'Allemand',             'icon' => 'fa-language'],
            ['name' => 'Arabe',                'icon' => 'fa-language'],
            ['name' => 'Portugais',            'icon' => 'fa-language'],
            ['name' => 'Économie',             'icon' => 'fa-chart-line'],
            ['name' => 'Comptabilité',         'icon' => 'fa-calculator'],
            ['name' => 'Informatique',         'icon' => 'fa-laptop-code'],
            ['name' => 'Éducation civique',    'icon' => 'fa-scale-balanced'],
            ['name' => 'Sciences',             'icon' => 'fa-microscope'],
        ];

        foreach ($matieres as $order => $matiere) {
            EpreuveMatiere::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($matiere['name'])],
                [
                    'name' => $matiere['name'],
                    'icon' => $matiere['icon'],
                    'order' => $order,
                ]
            );
        }
    }
}
