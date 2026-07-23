<?php

if (!function_exists('countries_list_fr')) {
    /**
     * Liste des pays en français (code ISO2 => nom), triée alphabétiquement.
     * Utilise l'extension intl si disponible, avec une liste de secours sinon.
     *
     * @return array<string, string>
     */
    function countries_list_fr(): array
    {
        return \Illuminate\Support\Facades\Cache::rememberForever('countries_list_fr', function () {
            if (class_exists('ResourceBundle')) {
                $bundle = ResourceBundle::create('fr', 'ICUDATA-region');
                $countries = [];

                if ($bundle !== null) {
                    foreach ($bundle->get('Countries') as $code => $name) {
                        if (preg_match('/^[A-Z]{2}$/', $code)) {
                            $countries[$code] = $name;
                        }
                    }
                }

                if (!empty($countries)) {
                    asort($countries, SORT_LOCALE_STRING);
                    return $countries;
                }
            }

            // Liste de secours si l'extension intl n'est pas disponible
            $fallback = [
                'SN' => 'Sénégal', 'FR' => 'France', 'CI' => 'Côte d\'Ivoire', 'ML' => 'Mali',
                'BF' => 'Burkina Faso', 'GN' => 'Guinée', 'TG' => 'Togo', 'BJ' => 'Bénin',
                'NE' => 'Niger', 'MR' => 'Mauritanie', 'GW' => 'Guinée-Bissau', 'GA' => 'Gabon',
                'CM' => 'Cameroun', 'CD' => 'RD Congo', 'CG' => 'Congo', 'MA' => 'Maroc',
                'DZ' => 'Algérie', 'TN' => 'Tunisie', 'BE' => 'Belgique', 'CH' => 'Suisse',
                'CA' => 'Canada', 'US' => 'États-Unis', 'GB' => 'Royaume-Uni', 'DE' => 'Allemagne',
                'ES' => 'Espagne', 'IT' => 'Italie', 'PT' => 'Portugal', 'CN' => 'Chine',
                'IN' => 'Inde', 'BR' => 'Brésil', 'ZA' => 'Afrique du Sud', 'NG' => 'Nigeria',
                'GH' => 'Ghana', 'EG' => 'Égypte', 'RW' => 'Rwanda', 'MG' => 'Madagascar',
                'TD' => 'Tchad', 'CF' => 'Centrafrique', 'HT' => 'Haïti',
            ];
            asort($fallback, SORT_LOCALE_STRING);

            return $fallback;
        });
    }
}
