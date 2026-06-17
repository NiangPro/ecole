<?php

namespace Database\Seeders;

use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EpreuveCFEESeeder extends Seeder
{
    /**
     * Épreuves CFEE (CM2) Sénégal.
     * Lance d'abord : bash scripts/download_cfee.sh
     */
    public function run(): void
    {
        $m = $this->matieres();
        $created = 0;

        foreach ($this->documents($m) as $doc) {
            $slug = $doc['slug'];
            $path = 'epreuves/cfee/' . $slug . '.pdf';
            $fullPath = storage_path('app/public/' . $path);

            $fileExists = file_exists($fullPath);

            Epreuve::firstOrCreate(
                ['slug' => $slug],
                array_merge($doc, [
                    'file_path'  => $fileExists ? $path : '',
                    'file_name'  => $fileExists ? basename($fullPath) : null,
                    'file_size'  => $fileExists ? filesize($fullPath) : null,
                    'status'     => $fileExists ? 'published' : 'draft',
                    'is_featured'=> false,
                    'exam'       => 'cfee',
                    'level'      => 'cm2',
                ])
            );
            $created++;
        }

        $this->command->info("✓ {$created} épreuves CFEE insérées/vérifiées.");
    }

    private function matieres(): array
    {
        $all = EpreuveMatiere::all()->keyBy(fn ($m) => $m->slug);

        // Créer "Éducation civique" si absente
        if (! $all->has('education-civique')) {
            $all['education-civique'] = EpreuveMatiere::firstOrCreate(
                ['slug' => 'education-civique'],
                ['name' => 'Éducation civique', 'icon' => 'fa-scale-balanced', 'order' => 20]
            );
        }

        return [
            'maths'    => $all->get('mathematiques')?->id,
            'francais' => $all->get('francais')?->id,
            'sciences' => $all->get('sciences')?->id,
            'edciviq'  => $all->get('education-civique')?->id,
        ];
    }

    private function documents(array $m): array
    {
        $maths    = $m['maths'];
        $francais = $m['francais'];
        $sciences = $m['sciences'];
        $edciviq  = $m['edciviq'];

        return [
            // ── ANCIEN PROGRAMME (2000-2012) ────────────────────────────────
            // banquedesepreuves.com
            ['slug' => 'cfee-2000-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2000 — Calcul',                          'year' => 2000, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => 'cfee-2000-senegal-epreuve-d-orthographe',             'title' => 'CFEE 2000 — Orthographe',                      'year' => 2000, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2001-senegal-epreuve-de-question-de-cours',      'title' => 'CFEE 2001 — Questions de cours',               'year' => 2001, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'cfee-2001-senegal-epreuve-de-redaction',              'title' => 'CFEE 2001 — Rédaction',                        'year' => 2001, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2002-senegal-epreuve-de-question-de-cours',      'title' => 'CFEE 2002 — Questions de cours',               'year' => 2002, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'cfee-2002-senegal-epreuve-de-redaction',              'title' => 'CFEE 2002 — Rédaction',                        'year' => 2002, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2003-senegal-epreuve-de-question-de-cours',      'title' => 'CFEE 2003 — Questions de cours',               'year' => 2003, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'cfee-2003-senegal-epreuve-de-redaction',              'title' => 'CFEE 2003 — Rédaction',                        'year' => 2003, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2004-senegal-epreuve-de-question-de-cours',      'title' => 'CFEE 2004 — Questions de cours',               'year' => 2004, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'cfee-2005-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2005 — Calcul',                          'year' => 2005, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => 'cfee-2005-senegal-epreuve-d-orthographe',             'title' => 'CFEE 2005 — Orthographe',                      'year' => 2005, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2006-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2006 — Calcul',                          'year' => 2006, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => 'cfee-2006-senegal-epreuve-d-orthographe',             'title' => 'CFEE 2006 — Orthographe',                      'year' => 2006, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2007-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2007 — Calcul',                          'year' => 2007, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => 'cfee-2007-senegal-epreuve-d-orthographe',             'title' => 'CFEE 2007 — Orthographe',                      'year' => 2007, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2008-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2008 — Calcul',                          'year' => 2008, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => 'cfee-2008-senegal-epreuve-d-orthographe',             'title' => 'CFEE 2008 — Orthographe',                      'year' => 2008, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2009-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2009 — Calcul',                          'year' => 2009, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => 'cfee-2009-orthographe',                               'title' => 'CFEE 2009 — Orthographe',                      'year' => 2009, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2010-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2010 — Calcul',                          'year' => 2010, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => 'cfee-2010-senegal-orthographe',                       'title' => 'CFEE 2010 — Orthographe',                      'year' => 2010, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2010-senegal-epreuve-de-redaction',              'title' => 'CFEE 2010 — Rédaction',                        'year' => 2010, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2010-senegal-epreuve-de-question-de-cours',      'title' => 'CFEE 2010 — Questions de cours',               'year' => 2010, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'cfee-2011-senegal-epreuve-de-redaction',              'title' => 'CFEE 2011 — Rédaction',                        'year' => 2011, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2011-senegal-epreuve-d-orthographe',             'title' => 'CFEE 2011 — Orthographe',                      'year' => 2011, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2012-senegal-epreuve-de-redaction',              'title' => 'CFEE 2012 — Rédaction',                        'year' => 2012, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'cfee-2012-senegal-epreuve-de-question-de-cours',      'title' => 'CFEE 2012 — Questions de cours',               'year' => 2012, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'cfee-2012-senegal-epreuve-de-calcul',                 'title' => 'CFEE 2012 — Calcul',                          'year' => 2012, 'matiere_id' => $maths,    'type' => 'epreuve'],

            // cm2.examen.sn — source complémentaire (slugs différents)
            ['slug' => '2006_redaction',   'title' => 'CFEE 2006 — Rédaction',         'year' => 2006, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2006_orthographe', 'title' => 'CFEE 2006 — Orthographe (b)',   'year' => 2006, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2006_calcul',      'title' => 'CFEE 2006 — Calcul (b)',        'year' => 2006, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => '2006_cours',       'title' => 'CFEE 2006 — Questions de cours (b)', 'year' => 2006, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => '2007_redaction',   'title' => 'CFEE 2007 — Rédaction',         'year' => 2007, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2007_orthographe', 'title' => 'CFEE 2007 — Orthographe (b)',   'year' => 2007, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2007_calcul',      'title' => 'CFEE 2007 — Calcul (b)',        'year' => 2007, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => '2007_cours',       'title' => 'CFEE 2007 — Questions de cours (b)', 'year' => 2007, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => '2008_redaction',   'title' => 'CFEE 2008 — Rédaction',         'year' => 2008, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2008_orthographe', 'title' => 'CFEE 2008 — Orthographe (b)',   'year' => 2008, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2008_calcul',      'title' => 'CFEE 2008 — Calcul (b)',        'year' => 2008, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => '2008_cours',       'title' => 'CFEE 2008 — Questions de cours (b)', 'year' => 2008, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => '2009_redaction',   'title' => 'CFEE 2009 — Rédaction',         'year' => 2009, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2009_orthographe', 'title' => 'CFEE 2009 — Orthographe (b)',   'year' => 2009, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2009_calcul',      'title' => 'CFEE 2009 — Calcul (b)',        'year' => 2009, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => '2009_cours',       'title' => 'CFEE 2009 — Questions de cours (b)', 'year' => 2009, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => '2010_redaction',   'title' => 'CFEE 2010 — Rédaction (b)',     'year' => 2010, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2010_orthographe', 'title' => 'CFEE 2010 — Orthographe (c)',   'year' => 2010, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2010_calcul',      'title' => 'CFEE 2010 — Calcul (b)',        'year' => 2010, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => '2010_cours',       'title' => 'CFEE 2010 — Questions de cours (b)', 'year' => 2010, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => '2011_redaction',   'title' => 'CFEE 2011 — Rédaction (b)',     'year' => 2011, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2011_orthographe', 'title' => 'CFEE 2011 — Orthographe (b)',   'year' => 2011, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2011_calcul',      'title' => 'CFEE 2011 — Calcul',            'year' => 2011, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => '2011_cours',       'title' => 'CFEE 2011 — Questions de cours', 'year' => 2011, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => '2012_redaction',   'title' => 'CFEE 2012 — Rédaction (b)',     'year' => 2012, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2012_orthographe', 'title' => 'CFEE 2012 — Orthographe',       'year' => 2012, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => '2012_calcul',      'title' => 'CFEE 2012 — Calcul (b)',        'year' => 2012, 'matiere_id' => $maths,    'type' => 'epreuve'],
            ['slug' => '2012_cours',       'title' => 'CFEE 2012 — Questions de cours', 'year' => 2012, 'matiere_id' => $sciences, 'type' => 'epreuve'],

            // ── NOUVEAU PROGRAMME 2016-2019 ─────────────────────────────────
            ['slug' => 'epreuve-cfee-2016-decouverte-du-monde-controle-de-la-competence',                'title' => 'CFEE 2016 — Découverte du Monde (CC)',               'year' => 2016, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2016-education-au-developpement-durable-controle-de-la-competence', 'title' => 'CFEE 2016 — Développement Durable (CC)',             'year' => 2016, 'matiere_id' => $edciviq,  'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2016-langue-et-communication-controle-de-la-competence',            'title' => 'CFEE 2016 — Langue et Communication (CC)',           'year' => 2016, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2016-mathematiques-controle-de-la-competence',                      'title' => 'CFEE 2016 — Mathématiques (CC)',                     'year' => 2016, 'matiere_id' => $maths,    'type' => 'epreuve'],

            ['slug' => 'epreuve-cfee-2017-developpement-durable-controle-de-la-competence',  'title' => 'CFEE 2017 — Développement Durable (CC)',   'year' => 2017, 'matiere_id' => $edciviq,  'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2017-developpement-durable-controle-des-ressources',    'title' => 'CFEE 2017 — Développement Durable (CR)',   'year' => 2017, 'matiere_id' => $edciviq,  'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2017-langue-et-communication-controle-de-la-competence','title' => 'CFEE 2017 — Langue et Communication (CC)', 'year' => 2017, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2017-mathematiques-controle-de-la-competence',          'title' => 'CFEE 2017 — Mathématiques (CC)',           'year' => 2017, 'matiere_id' => $maths,    'type' => 'epreuve'],

            ['slug' => 'epreuve-cfee-2018-developpement-durable-controle-de-la-competence',  'title' => 'CFEE 2018 — Développement Durable (CC)',   'year' => 2018, 'matiere_id' => $edciviq,  'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2018-education-artistique-controle-de-la-competence',   'title' => 'CFEE 2018 — Éducation Artistique (CC)',    'year' => 2018, 'matiere_id' => null,       'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2018-langue-et-communication-controle-des-ressources',  'title' => 'CFEE 2018 — Langue et Communication (CR)', 'year' => 2018, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2018-mathematiques-controle-des-ressources',            'title' => 'CFEE 2018 — Mathématiques (CR)',           'year' => 2018, 'matiere_id' => $maths,    'type' => 'epreuve'],

            ['slug' => 'epreuve-cfee-2019-decouverte-du-monde-controle-des-ressources',      'title' => 'CFEE 2019 — Découverte du Monde (CR)',     'year' => 2019, 'matiere_id' => $sciences, 'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2019-developpement-durable-controle-des-ressources',    'title' => 'CFEE 2019 — Développement Durable (CR)',   'year' => 2019, 'matiere_id' => $edciviq,  'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2019-langue-et-communication-controle-de-la-competence','title' => 'CFEE 2019 — Langue et Communication (CC)', 'year' => 2019, 'matiere_id' => $francais, 'type' => 'epreuve'],
            ['slug' => 'epreuve-cfee-2019-mathematiques-controle-de-la-competence',          'title' => 'CFEE 2019 — Mathématiques (CC)',           'year' => 2019, 'matiere_id' => $maths,    'type' => 'epreuve'],

            // ── CFEE BLANC 2014 ──────────────────────────────────────────────
            ['slug' => 'cfee-blanc-2014-decouverte-du-monde-controle-des-competences',                      'title' => 'CFEE Blanc 2014 — Découverte du Monde (CC)',        'year' => 2014, 'matiere_id' => $sciences, 'type' => 'examen_blanc'],
            ['slug' => 'cfee-blanc-2014-education-au-developpement-durable-controle-des-ressources',        'title' => 'CFEE Blanc 2014 — Développement Durable (CR)',      'year' => 2014, 'matiere_id' => $edciviq,  'type' => 'examen_blanc'],
            ['slug' => 'cfee-blanc-2014-langue-et-communication-controle-des-competences',                  'title' => 'CFEE Blanc 2014 — Langue et Communication (CC)',    'year' => 2014, 'matiere_id' => $francais, 'type' => 'examen_blanc'],
            ['slug' => 'cfee-blanc-2014-langue-et-communication-controle-des-ressources',                   'title' => 'CFEE Blanc 2014 — Langue et Communication (CR)',    'year' => 2014, 'matiere_id' => $francais, 'type' => 'examen_blanc'],
            ['slug' => 'cfee-blanc-2014-mathematiques-controle-des-competences',                            'title' => 'CFEE Blanc 2014 — Mathématiques (CC)',              'year' => 2014, 'matiere_id' => $maths,    'type' => 'examen_blanc'],

            // Corrigés blanc 2014
            ['slug' => 'cfee-blanc-2014-corrige-decouverte-du-monde-controle-des-competences',              'title' => 'CFEE Blanc 2014 — Corrigé Découverte du Monde (CC)',     'year' => 2014, 'matiere_id' => $sciences, 'type' => 'corrige'],
            ['slug' => 'cfee-blanc-2014-corrige-education-au-developpement-durable-controle-des-competences','title' => 'CFEE Blanc 2014 — Corrigé Développement Durable (CC)',    'year' => 2014, 'matiere_id' => $edciviq,  'type' => 'corrige'],
            ['slug' => 'cfee-blanc-2014-corrige-education-au-developpement-durable-controle-des-ressources', 'title' => 'CFEE Blanc 2014 — Corrigé Développement Durable (CR)',    'year' => 2014, 'matiere_id' => $edciviq,  'type' => 'corrige'],
            ['slug' => 'cfee-blanc-2014-corrige-langue-et-communication-controle-des-competences',          'title' => 'CFEE Blanc 2014 — Corrigé Langue et Communication (CC)', 'year' => 2014, 'matiere_id' => $francais, 'type' => 'corrige'],
            ['slug' => 'cfee-blanc-2014-corrige-langue-et-communication-controle-des-ressources',           'title' => 'CFEE Blanc 2014 — Corrigé Langue et Communication (CR)', 'year' => 2014, 'matiere_id' => $francais, 'type' => 'corrige'],
            ['slug' => 'cfee-blanc-2014-corrige-mathematiques-controle-des-ressources',                     'title' => 'CFEE Blanc 2014 — Corrigé Mathématiques (CR)',           'year' => 2014, 'matiere_id' => $maths,    'type' => 'corrige'],

            // ── CORRIGÉ 2013 ─────────────────────────────────────────────────
            ['slug' => 'corrige-cfee-2013-decouverte-du-monde-controle-des-competences', 'title' => 'CFEE 2013 — Corrigé Découverte du Monde (CC)', 'year' => 2013, 'matiere_id' => $sciences, 'type' => 'corrige'],
        ];
    }
}
