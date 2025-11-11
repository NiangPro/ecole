<?php

namespace App\Services;

class UserAgentParser
{
    public static function parse($userAgent)
    {
        if (!$userAgent) {
            return ['browser' => 'Unknown', 'device' => 'Unknown'];
        }

        $browser = self::getBrowser($userAgent);
        $device = self::getDevice($userAgent);

        return [
            'browser' => $browser,
            'device' => $device
        ];
    }

    private static function getBrowser($userAgent)
    {
        $browsers = [
            'Edge' => '/Edge|Edg/i',
            'Chrome' => '/Chrome/i',
            'Firefox' => '/Firefox/i',
            'Safari' => '/Safari/i',
            'Opera' => '/Opera|OPR/i',
            'Internet Explorer' => '/MSIE|Trident/i',
        ];

        foreach ($browsers as $browser => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $browser;
            }
        }

        return 'Other';
    }

    private static function getDevice($userAgent)
    {
        if (preg_match('/mobile|android|iphone|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            return 'Mobile';
        }

        if (preg_match('/tablet|ipad/i', $userAgent)) {
            return 'Tablet';
        }

        return 'Desktop';
    }
}
