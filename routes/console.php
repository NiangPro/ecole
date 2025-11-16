<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Planifier la sauvegarde automatique de la base de données tous les jours à 2h du matin
// Note: Pour Windows, utilisez Task Scheduler pour exécuter: php artisan schedule:run
Schedule::command('backup:database')->dailyAt('02:00')->withoutOverlapping();

// Planifier le nettoyage du cache tous les jours à minuit
Schedule::command('cache:clear')->dailyAt('00:00')->withoutOverlapping();

// Planifier le nettoyage des vues compilées tous les jours à 1h du matin
Schedule::command('view:clear')->dailyAt('01:00')->withoutOverlapping();

// Planifier la régénération du sitemap tous les jours à 3h du matin
Schedule::command('sitemap:generate')->dailyAt('03:00')->withoutOverlapping();
