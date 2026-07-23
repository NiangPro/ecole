<?php

if (!function_exists('format_compact_number')) {
    /**
     * Formate un nombre en version compacte (1,2 k / 10 k / 1 M) pour les cartes de stats.
     * Gère l'arrondi en cascade (ex: 999 999 -> "1 M" et non "1000 k").
     *
     * @param int|float $number
     * @return string
     */
    function format_compact_number($number)
    {
        $number = (float) $number;

        if ($number >= 999500) {
            $value = $number / 1000000;
            $rounded = $value < 10 ? round($value, 1) : round($value);
            $formatted = $value < 10
                ? rtrim(rtrim(number_format($rounded, 1, ',', ' '), '0'), ',')
                : number_format($rounded, 0, ',', ' ');
            return $formatted . ' M';
        }

        if ($number >= 1000) {
            $value = $number / 1000;
            $rounded = $value < 10 ? round($value, 1) : round($value);
            $formatted = $value < 10
                ? rtrim(rtrim(number_format($rounded, 1, ',', ' '), '0'), ',')
                : number_format($rounded, 0, ',', ' ');
            return $formatted . ' k';
        }

        return number_format($number, 0, ',', ' ');
    }
}
