<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class GeoIPService
{
    /** Durée du cache par IP (24 h) pour éviter les appels API bloquants. */
    private const CACHE_TTL = 86400;

    public static function getCountry($ip)
    {
        if (in_array($ip, ['127.0.0.1', '::1']) || self::isPrivateIP($ip)) {
            return 'SN';
        }

        $cleCache = 'geo_ip_' . $ip;

        return Cache::remember($cleCache, self::CACHE_TTL, function () use ($ip) {
            try {
                $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
                if ($response) {
                    $data = json_decode($response, true);
                    return $data['countryCode'] ?? 'XX';
                }
            } catch (\Exception $e) {
                //
            }
            return 'XX';
        });
    }

    private static function isPrivateIP($ip)
    {
        $private_ranges = [
            '10.0.0.0' => '10.255.255.255',
            '172.16.0.0' => '172.31.255.255',
            '192.168.0.0' => '192.168.255.255',
        ];

        $long_ip = ip2long($ip);
        
        foreach ($private_ranges as $start => $end) {
            if ($long_ip >= ip2long($start) && $long_ip <= ip2long($end)) {
                return true;
            }
        }

        return false;
    }

    public static function getCountryName($code)
    {
        $countries = [
            'SN' => 'Sénégal',
            'FR' => 'France',
            'US' => 'États-Unis',
            'GB' => 'Royaume-Uni',
            'DE' => 'Allemagne',
            'CA' => 'Canada',
            'MA' => 'Maroc',
            'CI' => 'Côte d\'Ivoire',
            'ML' => 'Mali',
            'BF' => 'Burkina Faso',
            'BJ' => 'Bénin',
            'TG' => 'Togo',
            'GN' => 'Guinée',
            'XX' => 'Inconnu',
        ];

        return $countries[$code] ?? $code;
    }

    public static function getCountryFlag($code)
    {
        $flags = [
            'SN' => '🇸🇳',
            'FR' => '🇫🇷',
            'US' => '🇺🇸',
            'GB' => '🇬🇧',
            'DE' => '🇩🇪',
            'CA' => '🇨🇦',
            'MA' => '🇲🇦',
            'CI' => '🇨🇮',
            'ML' => '🇲🇱',
            'BF' => '🇧🇫',
            'BJ' => '🇧🇯',
            'TG' => '🇹🇬',
            'GN' => '🇬🇳',
            'XX' => '🏳️',
        ];

        return $flags[$code] ?? '🌍';
    }
}
