<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Planifier la sauvegarde automatique de la base de données tous les jours à 2h du matin
Schedule::command('backup:database')->dailyAt('02:00');

// Planifier le nettoyage du cache tous les jours à minuit
Schedule::command('cache:clear')->dailyAt('00:00');

// Planifier le nettoyage des vues compilées tous les jours à 1h du matin
Schedule::command('view:clear')->dailyAt('01:00');
