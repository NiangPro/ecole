<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\NewsletterController;

// Sitemaps SEO
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [\App\Http\Controllers\SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-articles.xml', [\App\Http\Controllers\SitemapController::class, 'articles'])->name('sitemap.articles');

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/search', [PageController::class, 'search'])->middleware('throttle:30,1')->name('search');
Route::post('/contact', [PageController::class, 'sendContact'])->middleware('throttle:5,1')->name('contact.send');
Route::post('/newsletter/subscribe', [PageController::class, 'newsletterSubscribe'])->middleware('throttle:10,1')->name('newsletter.subscribe');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/exercices', [PageController::class, 'exercices'])->name('exercices');
Route::get('/exercices/{language}', [PageController::class, 'exercicesLanguage'])->name('exercices.language');
Route::get('/exercices/{language}/{id}', [PageController::class, 'exerciceDetail'])->name('exercices.detail');
Route::post('/exercices/{language}/{id}/submit', [PageController::class, 'exerciceSubmit'])->name('exercices.submit');
Route::post('/exercices/{language}/run', [PageController::class, 'runCode'])->middleware('throttle:30,1')->name('exercices.run');
Route::get('/quiz', [PageController::class, 'quiz'])->name('quiz');
Route::get('/quiz/{language}', [PageController::class, 'quizLanguage'])->name('quiz.language');
Route::post('/quiz/{language}/submit', [PageController::class, 'quizSubmit'])->name('quiz.submit');
Route::get('/quiz/{language}/result', [PageController::class, 'quizResult'])->name('quiz.result');
Route::get('/all-links', [PageController::class, 'allLinks'])->name('all.links');
Route::get('/legal', [PageController::class, 'legal'])->name('legal');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::post('/newsletter/subscribe', [PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [PageController::class, 'newsletterUnsubscribe'])->name('newsletter.unsubscribe');

// Route pour changer la langue
Route::get('/language/{locale}', [PageController::class, 'setLanguage'])->name('language.set');

// Routes Formations
Route::get('/formations', [PageController::class, 'allFormations'])->name('formations.all');
Route::get('/formations/html5', [PageController::class, 'html5'])->name('formations.html5');
Route::get('/formations/css3', [PageController::class, 'css3'])->name('formations.css3');
Route::get('/formations/javascript', [PageController::class, 'javascript'])->name('formations.javascript');
Route::get('/formations/php', [PageController::class, 'php'])->name('formations.php');
Route::get('/formations/bootstrap', [PageController::class, 'bootstrap'])->name('formations.bootstrap');
Route::get('/formations/java', [PageController::class, 'java'])->name('formations.java');
Route::get('/formations/sql', [PageController::class, 'sql'])->name('formations.sql');
Route::get('/formations/c', [PageController::class, 'c'])->name('formations.c');
Route::get('/formations/git', [PageController::class, 'git'])->name('formations.git');
Route::get('/formations/wordpress', [PageController::class, 'wordpress'])->name('formations.wordpress');
Route::get('/formations/ia', [PageController::class, 'ia'])->name('formations.ia');
Route::get('/formations/python', [PageController::class, 'python'])->name('formations.python');
Route::get('/formations/cpp', [PageController::class, 'cpp'])->name('formations.cpp');
Route::get('/formations/csharp', [PageController::class, 'csharp'])->name('formations.csharp');
Route::get('/formations/dart', [PageController::class, 'dart'])->name('formations.dart');

// Routes Emplois
Route::get('/emplois', [PageController::class, 'emplois'])->name('emplois');
Route::get('/emplois/offres', [PageController::class, 'offresEmploi'])->name('emplois.offres');
Route::get('/emplois/bourses', [PageController::class, 'bourses'])->name('emplois.bourses');
Route::get('/emplois/candidature-spontanee', [PageController::class, 'candidatureSpontanee'])->name('emplois.candidature');
Route::get('/emplois/opportunites', [PageController::class, 'opportunites'])->name('emplois.opportunites');
Route::get('/emplois/concours', [PageController::class, 'concours'])->name('emplois.concours');
Route::get('/emplois/categorie/{slug}', [PageController::class, 'categoryArticles'])->name('emplois.category');
// Route spécifique avant la route avec paramètre pour éviter les conflits
Route::get('/emplois/articles-recents', [PageController::class, 'recentArticles'])->name('emplois.recent-articles');
// Route avec paramètre - exclut "articles-recents" pour éviter les conflits
Route::get('/emplois/article/{slug}', [PageController::class, 'showArticle'])
    ->where('slug', '^(?!articles-recents$).+')
    ->name('emplois.article');

// Commentaires (publiques - avec rate limiting)
Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store'])->middleware('throttle:5,15')->name('comments.store');
Route::post('/comments/{id}/like', [\App\Http\Controllers\CommentController::class, 'like'])->middleware('throttle:10,1')->name('comments.like');

// Routes d'authentification utilisateur
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.post');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Routes utilisateur authentifié - Dashboard
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    // Badges
    Route::get('/badges', [App\Http\Controllers\BadgeController::class, 'index'])->name('badges');
    
    // Certificats
    Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'index'])->name('certificates');
    Route::get('/certificates/{id}', [App\Http\Controllers\CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{id}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');
    Route::post('/certificates/generate/{formationSlug}', [App\Http\Controllers\CertificateController::class, 'generate'])->name('certificates.generate');
    // Page principale (redirige vers overview)
    Route::get('/', [\App\Http\Controllers\ProfileController::class, 'overview'])->name('index');
    
    // Routes pour chaque section
    Route::get('/overview', [\App\Http\Controllers\ProfileController::class, 'overview'])->name('overview');
    Route::get('/formations', [\App\Http\Controllers\ProfileController::class, 'formations'])->name('formations');
    Route::get('/exercices', [\App\Http\Controllers\ProfileController::class, 'exercices'])->name('exercices');
    Route::get('/quiz', [\App\Http\Controllers\ProfileController::class, 'quiz'])->name('quiz');
    Route::get('/goals', [\App\Http\Controllers\ProfileController::class, 'goals'])->name('goals');
    Route::get('/activities', [\App\Http\Controllers\ProfileController::class, 'activities'])->name('activities');
    Route::get('/statistics', [\App\Http\Controllers\ProfileController::class, 'statistics'])->name('statistics');
    Route::get('/settings', [\App\Http\Controllers\ProfileController::class, 'settings'])->name('settings');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Routes pour les objectifs (API)
    Route::post('/goals', [\App\Http\Controllers\UserGoalController::class, 'store'])->name('goals.store');
    Route::put('/goals/{id}', [\App\Http\Controllers\UserGoalController::class, 'update'])->name('goals.update');
    Route::delete('/goals/{id}', [\App\Http\Controllers\UserGoalController::class, 'destroy'])->name('goals.destroy');
    Route::post('/goals/{id}/complete', [\App\Http\Controllers\UserGoalController::class, 'complete'])->name('goals.complete');
    Route::post('/goals/{id}/progress', [\App\Http\Controllers\UserGoalController::class, 'updateProgress'])->name('goals.progress');
});

// Route legacy pour compatibilité
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});


// Routes Admin (pas de lien public)
// Routes publiques (login)
Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.post');

// Routes protégées par middleware admin
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/admin/statistics', [App\Http\Controllers\AdminController::class, 'statistics'])->name('admin.statistics');
    Route::post('/admin/statistics/truncate', [App\Http\Controllers\AdminController::class, 'truncateStatistics'])->name('admin.statistics.truncate');
    Route::get('/admin/adsense', [App\Http\Controllers\AdminController::class, 'adsense'])->name('admin.adsense');
    Route::post('/admin/adsense', [App\Http\Controllers\AdminController::class, 'updateAdsense'])->name('admin.adsense.update');
    Route::get('/admin/adsense/check', [App\Http\Controllers\AdminController::class, 'adsenseCheck'])->name('admin.adsense.check');
    Route::get('/admin/backups', [App\Http\Controllers\AdminController::class, 'backups'])->name('admin.backups');
    Route::post('/admin/backups/create', [App\Http\Controllers\AdminController::class, 'createBackup'])->name('admin.backups.create');
    Route::get('/admin/backups/download/{filename}', [App\Http\Controllers\AdminController::class, 'downloadBackup'])->name('admin.backups.download');
    Route::delete('/admin/backups/{filename}', [App\Http\Controllers\AdminController::class, 'deleteBackup'])->name('admin.backups.delete');
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/admin/messages', [App\Http\Controllers\AdminController::class, 'messages'])->name('admin.messages');
    Route::post('/admin/messages/{id}/mark-read', [App\Http\Controllers\AdminController::class, 'markAsRead'])->name('admin.messages.mark-read');
    Route::delete('/admin/messages/{id}', [App\Http\Controllers\AdminController::class, 'deleteMessage'])->name('admin.messages.delete');
    Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/admin/bing-submission', [App\Http\Controllers\AdminController::class, 'bingSubmission'])->name('admin.bing.submission');
    Route::post('/admin/bing-submission/submit', [App\Http\Controllers\AdminController::class, 'submitToBing'])->name('admin.bing.submit');
    Route::get('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');

    // Routes Commentaires Admin
    Route::prefix('admin/comments')->name('admin.comments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CommentController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [\App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\Admin\CommentController::class, 'reject'])->name('reject');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\CommentController::class, 'delete'])->name('delete');
        Route::get('/{id}/whatsapp-link', [\App\Http\Controllers\Admin\CommentController::class, 'getWhatsAppLink'])->name('whatsapp-link');
        Route::get('/{id}/email-link', [\App\Http\Controllers\Admin\CommentController::class, 'getEmailLink'])->name('email-link');
    });

    // Routes Logs Admin
    Route::prefix('admin/logs')->name('admin.logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('index');
    });

    // Newsletter Admin
    Route::get('/admin/newsletter', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('admin.newsletter.index');
    Route::get('/admin/newsletter/export', [\App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('admin.newsletter.export');
    Route::post('/admin/newsletter/bulk-action', [\App\Http\Controllers\Admin\NewsletterController::class, 'bulkAction'])->name('admin.newsletter.bulk-action');
    Route::post('/admin/newsletter/{id}/toggle', [\App\Http\Controllers\Admin\NewsletterController::class, 'toggleStatus'])->name('admin.newsletter.toggle');
    Route::delete('/admin/newsletter/{id}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('admin.newsletter.destroy');

    // Routes Emplois Admin
    Route::prefix('admin/jobs')->name('admin.jobs.')->group(function () {
        // Catégories
        Route::resource('categories', \App\Http\Controllers\Admin\JobCategoryController::class);
        
        // Articles
        Route::resource('articles', \App\Http\Controllers\Admin\JobArticleController::class);
        Route::post('articles/{id}/send-newsletter', [\App\Http\Controllers\Admin\JobArticleController::class, 'sendNewsletter'])->name('articles.send-newsletter');
        Route::post('articles/recalculate-scores', [\App\Http\Controllers\Admin\JobArticleController::class, 'recalculateScores'])->name('articles.recalculate-scores');
        
        // Seeder d'articles
        Route::get('seeder', [\App\Http\Controllers\Admin\ArticleSeederController::class, 'index'])->name('seeder.index');
        Route::post('seeder/seed', [\App\Http\Controllers\Admin\ArticleSeederController::class, 'seed'])->name('seeder.seed');
    });

    // Routes Publicités Admin
    Route::prefix('admin/ads')->name('admin.ads.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AdController::class, 'store'])->name('store');
        Route::get('/{ad}', [\App\Http\Controllers\Admin\AdController::class, 'show'])->name('show');
        Route::get('/{ad}/edit', [\App\Http\Controllers\Admin\AdController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{ad}', [\App\Http\Controllers\Admin\AdController::class, 'update'])->name('update');
        Route::delete('/{ad}', [\App\Http\Controllers\Admin\AdController::class, 'destroy'])->name('destroy');
    });

    // Routes Réalisations Admin
    Route::prefix('admin/achievements')->name('admin.achievements.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AchievementController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AchievementController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AchievementController::class, 'store'])->name('store');
        Route::get('/{achievement}', [\App\Http\Controllers\Admin\AchievementController::class, 'show'])->name('show');
        Route::get('/{achievement}/edit', [\App\Http\Controllers\Admin\AchievementController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{achievement}', [\App\Http\Controllers\Admin\AchievementController::class, 'update'])->name('update');
        Route::delete('/{achievement}', [\App\Http\Controllers\Admin\AchievementController::class, 'destroy'])->name('destroy');
        Route::post('/toggle-section', [\App\Http\Controllers\Admin\AchievementController::class, 'toggleSection'])->name('toggle-section');
    });
});

// API pour tracking des clics publicitaires
Route::post('/api/ads/{id}/click', function($id) {
    $ad = \App\Models\Ad::find($id);
    if ($ad) {
        $ad->incrementClicks();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 404);
})->name('api.ads.click');

Route::get('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');

// Routes API pour la progression des formations
Route::middleware('auth')->group(function () {
    Route::post('/api/formation-progress/update', [App\Http\Controllers\FormationProgressController::class, 'update'])->name('api.formation-progress.update');
    Route::get('/api/formation-progress/{formationSlug}', [App\Http\Controllers\FormationProgressController::class, 'get'])->name('api.formation-progress.get');
});

// Robots.txt dynamique
Route::get('/robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    $content = "User-agent: *\n";
    $content .= "Allow: /\n\n";
    $content .= "Sitemap: {$sitemapUrl}\n";
    
    return response($content, 200)
        ->header('Content-Type', 'text/plain');
});
