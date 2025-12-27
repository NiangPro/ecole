<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\NewsletterController;

// Favicon.ico - DOIT être défini EN PREMIER pour être prioritaire
Route::get('/favicon.ico', function () {
    $logoPath = public_path('images/logo.png');
    
    if (file_exists($logoPath)) {
        return response()->file($logoPath, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
    
    // Si le logo n'existe pas, vérifier s'il y a un favicon.ico dans public/
    $faviconPath = public_path('favicon.ico');
    if (file_exists($faviconPath)) {
        return response()->file($faviconPath, [
            'Content-Type' => 'image/x-icon',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
    
    // Fallback : retourner un favicon vide si rien n'existe
    return response('', 404);
})->name('favicon');

// Fichier ads.txt - Redirection vers adstxtmanager.com
Route::get('/ads.txt', function () {
    return redirect('https://srv.adstxtmanager.com/19390/niangprogrammeur.com', 301)
        ->header('Content-Type', 'text/plain; charset=utf-8');
})->name('ads.txt');

// Sitemaps SEO
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-pages.xml', [\App\Http\Controllers\SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-articles.xml', [\App\Http\Controllers\SitemapController::class, 'articles'])->name('sitemap.articles');
Route::get('/sitemap-documents.xml', [\App\Http\Controllers\SitemapController::class, 'documents'])->name('sitemap.documents');

Route::get('/', [PageController::class, 'index'])->name('home');
// Route Recherche - Utiliser SearchController
use App\Http\Controllers\SearchController;
Route::get('/search', [SearchController::class, 'index'])->middleware('throttle:30,1')->name('search');

// Routes Forum (publiques)
use App\Http\Controllers\ForumController;
Route::prefix('forum')->name('forum.')->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/search', [ForumController::class, 'search'])->name('search');
    Route::get('/category/{slug}', [ForumController::class, 'category'])->name('category');
    Route::get('/{categorySlug}/{topicSlug}', [ForumController::class, 'show'])->name('show');
    
    // Routes authentifiées
    Route::middleware('auth')->group(function () {
        Route::get('/create', [ForumController::class, 'create'])->name('create');
        Route::post('/create', [ForumController::class, 'store'])->name('store');
        Route::post('/{categorySlug}/{topicSlug}/reply', [ForumController::class, 'reply'])->name('reply');
        Route::post('/reply/{replyId}/vote', [ForumController::class, 'vote'])->name('vote');
        Route::post('/reply/{replyId}/best-answer', [ForumController::class, 'markBestAnswer'])->name('mark-best-answer');
        Route::delete('/topic/{topicId}', [ForumController::class, 'deleteTopic'])->name('delete-topic');
        Route::delete('/reply/{replyId}', [ForumController::class, 'deleteReply'])->name('delete-reply');
    });
    
    // Routes admin
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/topic/{topicId}/pin', [ForumController::class, 'togglePin'])->name('toggle-pin');
        Route::post('/topic/{topicId}/lock', [ForumController::class, 'toggleLock'])->name('toggle-lock');
    });
});
Route::post('/contact', [PageController::class, 'sendContact'])->middleware('throttle:5,1')->name('contact.send');
Route::post('/newsletter/subscribe', [PageController::class, 'newsletterSubscribe'])->middleware('throttle:10,1')->name('newsletter.subscribe');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/docs', [PageController::class, 'docs'])->name('docs');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
// Routes Exercices - Utiliser ExerciceController
use App\Http\Controllers\ExerciceController;
Route::get('/exercices', [ExerciceController::class, 'index'])->name('exercices');
Route::get('/exercices/{language}', [ExerciceController::class, 'language'])->name('exercices.language');
Route::get('/exercices/{language}/{id}', [ExerciceController::class, 'detail'])->name('exercices.detail');
Route::post('/exercices/{language}/{id}/submit', [ExerciceController::class, 'submit'])->name('exercices.submit');
Route::post('/exercices/{language}/run', [ExerciceController::class, 'runCode'])->middleware('throttle:30,1')->name('exercices.run');
// Routes Quiz - Utiliser QuizController
use App\Http\Controllers\QuizController;
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz');
Route::get('/quiz/{language}', [QuizController::class, 'language'])->name('quiz.language');
Route::post('/quiz/{language}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
Route::get('/quiz/{language}/result', [QuizController::class, 'result'])->name('quiz.result');
Route::get('/all-links', [PageController::class, 'allLinks'])->name('all.links');
Route::get('/legal', [PageController::class, 'legal'])->name('legal');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::post('/newsletter/subscribe', [PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [PageController::class, 'newsletterUnsubscribe'])->name('newsletter.unsubscribe');

// Route pour changer la langue
Route::get('/language/{locale}', [PageController::class, 'setLanguage'])->name('language.set');

// Routes Documents Frontend
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentCartController;

// Routes spécifiques AVANT les routes avec paramètres pour éviter les conflits
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::get('/documents/category/{slug}', [DocumentController::class, 'category'])->name('documents.category');
Route::get('/documents/download/{token}', [DocumentController::class, 'downloadByToken'])->name('documents.download.token');
Route::get('/documents/my-documents', [DocumentController::class, 'myDocumentsByEmail'])->name('documents.my-documents-email');

// Routes Panier Documents (doivent être AVANT /documents/{slug})
Route::get('/documents/cart', [DocumentCartController::class, 'index'])->name('documents.cart');
Route::get('/documents/cart/total', [DocumentCartController::class, 'getTotal'])->name('documents.cart.total');
Route::post('/documents/{documentId}/cart/add', [DocumentCartController::class, 'add'])->name('documents.cart.add');
Route::put('/documents/cart/{cartItemId}/update', [DocumentCartController::class, 'update'])->name('documents.cart.update');
Route::delete('/documents/cart/{cartItemId}/remove', [DocumentCartController::class, 'remove'])->name('documents.cart.remove');
Route::post('/documents/cart/clear', [DocumentCartController::class, 'clear'])->name('documents.cart.clear');
Route::get('/documents/checkout/payment', [DocumentCartController::class, 'checkoutPayment'])->name('documents.checkout.payment');
Route::post('/documents/checkout/process', [DocumentCartController::class, 'processCheckout'])->name('documents.checkout.process');
Route::get('/documents/guest-success/{paymentId}', [DocumentCartController::class, 'guestSuccess'])->name('documents.guest-success');

// Route téléchargement gratuit (avant /documents/{slug})
Route::get('/documents/{id}/download-free', [DocumentController::class, 'downloadFree'])->name('documents.download-free');

// Routes avis/commentaires
use App\Http\Controllers\DocumentReviewController;
Route::post('/documents/{documentId}/reviews', [DocumentReviewController::class, 'store'])
    ->middleware('auth')
    ->name('documents.reviews.store');
Route::delete('/documents/reviews/{reviewId}', [DocumentReviewController::class, 'destroy'])
    ->middleware('auth')
    ->name('documents.reviews.destroy');

// Routes wishlist
use App\Http\Controllers\DocumentWishlistController;
Route::get('/dashboard/wishlist', [DocumentWishlistController::class, 'index'])
    ->middleware('auth')
    ->name('wishlist.index');
Route::post('/documents/{documentId}/wishlist', [DocumentWishlistController::class, 'toggle'])
    ->middleware('auth')
    ->name('wishlist.toggle');
Route::delete('/documents/{documentId}/wishlist', [DocumentWishlistController::class, 'remove'])
    ->middleware('auth')
    ->name('wishlist.remove');

// Routes codes promo
use App\Http\Controllers\DocumentCouponController;
Route::post('/coupons/validate', [DocumentCouponController::class, 'validate'])
    ->name('coupons.validate');
Route::post('/coupons/apply', [DocumentCouponController::class, 'apply'])
    ->name('coupons.apply');
Route::post('/coupons/remove', [DocumentCouponController::class, 'remove'])
    ->name('coupons.remove');

// Routes bundles
use App\Http\Controllers\DocumentBundleController;
Route::get('/bundles', [DocumentBundleController::class, 'index'])
    ->name('bundles.index');
Route::get('/bundles/{slug}', [DocumentBundleController::class, 'show'])
    ->name('bundles.show');

// Route avec paramètre en DERNIER pour éviter les conflits
Route::get('/documents/{slug}', [DocumentController::class, 'show'])->name('documents.show');

// Routes Monétisation
use App\Http\Controllers\MonetizationController;
use App\Http\Controllers\PaymentController;
Route::get('/monetization', [MonetizationController::class, 'index'])->name('monetization.index');
Route::get('/donations', [MonetizationController::class, 'donations'])->name('monetization.donations');
Route::get('/faire-un-don', [MonetizationController::class, 'donations'])->name('monetization.donations.alias');
Route::get('/courses', [MonetizationController::class, 'courses'])->name('monetization.courses');
Route::get('/courses/{slug}', [MonetizationController::class, 'showCourse'])->name('monetization.course.show');
Route::get('/affiliates', [MonetizationController::class, 'affiliates'])->name('monetization.affiliates');
Route::post('/affiliates/become', [MonetizationController::class, 'becomeAffiliate'])->middleware('auth')->name('monetization.affiliates.become');
Route::get('/affiliates/dashboard', function() {
    return redirect()->route('dashboard.affiliates')->setStatusCode(301);
})->middleware('auth')->name('monetization.affiliates.dashboard');
Route::post('/payment/subscription', [PaymentController::class, 'processSubscription'])->middleware('auth')->name('payment.subscription');
Route::get('/payment/course/{courseId}', function($courseId) {
    $course = \App\Models\PaidCourse::findOrFail($courseId);
    return redirect()->route('monetization.course.show', $course->slug)
        ->setStatusCode(301)
        ->with('error', 'Veuillez utiliser le formulaire d\'achat pour procéder au paiement.');
})->name('payment.course.get');
Route::post('/payment/course/{courseId}', [PaymentController::class, 'processCoursePurchase'])->middleware('auth')->name('payment.course');
Route::get('/payment/donation', function () {
    return redirect()->route('monetization.donations')->setStatusCode(301);
})->name('payment.donation.get');
Route::post('/payment/donation', [PaymentController::class, 'processDonation'])->name('payment.donation');
Route::get('/payment/confirm/{paymentId}', [PaymentController::class, 'confirm'])->name('payment.confirm');
Route::put('/payment/{paymentId}/update-method', [PaymentController::class, 'updatePaymentMethod'])->middleware('auth')->name('payment.update-method');
Route::get('/payment/wave/{paymentId}', [PaymentController::class, 'waveRedirect'])->name('payment.wave');
Route::get('/payment/paypal/return', [PaymentController::class, 'paypalReturn'])->name('payment.paypal.return');
Route::get('/payment/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('payment.paypal.cancel');
Route::get('/payment/stripe/success', [PaymentController::class, 'stripeSuccess'])->name('payment.stripe.success');
Route::get('/payment/stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('payment.stripe.cancel');
Route::get('/payment/success/{paymentId}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Routes Formations - Utiliser FormationController
use App\Http\Controllers\FormationController;
Route::get('/formations', [FormationController::class, 'index'])->name('formations.all');
Route::get('/formations/html5', [FormationController::class, 'html5'])->name('formations.html5');
Route::get('/formations/css3', [FormationController::class, 'css3'])->name('formations.css3');
Route::get('/formations/javascript', [FormationController::class, 'javascript'])->name('formations.javascript');
Route::get('/formations/php', [FormationController::class, 'php'])->name('formations.php');
Route::get('/formations/bootstrap', [FormationController::class, 'bootstrap'])->name('formations.bootstrap');
Route::get('/formations/java', [FormationController::class, 'java'])->name('formations.java');
Route::get('/formations/sql', [FormationController::class, 'sql'])->name('formations.sql');
Route::get('/formations/c', [FormationController::class, 'c'])->name('formations.c');
Route::get('/formations/git', [FormationController::class, 'git'])->name('formations.git');
Route::get('/formations/wordpress', [FormationController::class, 'wordpress'])->name('formations.wordpress');
Route::get('/formations/ia', [FormationController::class, 'ia'])->name('formations.ia');
Route::get('/formations/python', [FormationController::class, 'python'])->name('formations.python');
Route::get('/formations/cpp', [FormationController::class, 'cpp'])->name('formations.cpp');
Route::get('/formations/csharp', [FormationController::class, 'csharp'])->name('formations.csharp');
Route::get('/formations/dart', [FormationController::class, 'dart'])->name('formations.dart');
Route::get('/formations/go', [FormationController::class, 'go'])->name('formations.go');
Route::get('/formations/rust', [FormationController::class, 'rust'])->name('formations.rust');
Route::get('/formations/ruby', [FormationController::class, 'ruby'])->name('formations.ruby');
Route::get('/formations/cybersecurite', [FormationController::class, 'cybersecurite'])->name('formations.cybersecurite');
Route::get('/formations/data-science', [FormationController::class, 'dataScience'])->name('formations.data-science');
Route::get('/formations/big-data', [FormationController::class, 'bigData'])->name('formations.big-data');
Route::get('/formations/swift', [FormationController::class, 'swift'])->name('formations.swift');
Route::get('/formations/perl', [FormationController::class, 'perl'])->name('formations.perl');
Route::get('/formations/typescript', [FormationController::class, 'typescript'])->name('formations.typescript');

// Routes Emplois - Utiliser EmploiController
use App\Http\Controllers\EmploiController;
Route::get('/emplois', [EmploiController::class, 'index'])->name('emplois');
Route::get('/emplois/tous-les-articles', [EmploiController::class, 'allArticles'])->name('emplois.all-articles');
Route::get('/emplois/offres', [EmploiController::class, 'offres'])->name('emplois.offres');
Route::get('/emplois/bourses', [EmploiController::class, 'bourses'])->name('emplois.bourses');
Route::get('/emplois/candidature-spontanee', [EmploiController::class, 'candidatureSpontanee'])->name('emplois.candidature');
Route::get('/emplois/opportunites', [EmploiController::class, 'opportunites'])->name('emplois.opportunites');
Route::get('/emplois/concours', [EmploiController::class, 'concours'])->name('emplois.concours');
Route::get('/emplois/categorie/{slug}', [EmploiController::class, 'category'])->name('emplois.category');
// Route spécifique avant la route avec paramètre pour éviter les conflits
Route::get('/emplois/articles-recents', [EmploiController::class, 'recent'])->name('emplois.recent-articles');
Route::get('/articles/vedettes', [EmploiController::class, 'featured'])->name('articles.vedettes');
// Route avec paramètre - exclut "articles-recents" pour éviter les conflits
Route::get('/emplois/article/{slug}', [EmploiController::class, 'show'])
    ->where('slug', '^(?!articles-recents$).+')
    ->name('emplois.article');

// Commentaires (publiques - avec rate limiting)
Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store'])->middleware('throttle:5,15')->name('comments.store');
Route::post('/comments/{id}/like', [\App\Http\Controllers\CommentController::class, 'like'])->middleware('throttle:10,1')->name('comments.like');

// Favoris (API)
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::post('/favorites/toggle', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites/check', [\App\Http\Controllers\FavoriteController::class, 'check'])->name('favorites.check');
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'unread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // Notifications push
    Route::post('/push/subscribe', [\App\Http\Controllers\PushNotificationController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [\App\Http\Controllers\PushNotificationController::class, 'unsubscribe'])->name('push.unsubscribe');
    
    // Analytics tracking
    Route::post('/analytics/track', [\App\Http\Controllers\AnalyticsController::class, 'trackEvent'])->name('analytics.track');
    Route::post('/analytics/heatmap', [\App\Http\Controllers\AnalyticsController::class, 'trackHeatmap'])->name('analytics.heatmap');
    Route::post('/analytics/funnel', [\App\Http\Controllers\AnalyticsController::class, 'trackFunnelStep'])->name('analytics.funnel');
    Route::get('/analytics/ab-test/{testId}', [\App\Http\Controllers\AnalyticsController::class, 'getABTestVariant'])->name('analytics.ab-test');
    Route::get('/analytics/ab-tests', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getActiveABTests'])->name('analytics.ab-tests');
    Route::post('/analytics/ab-test/conversion', [\App\Http\Controllers\AnalyticsController::class, 'markABTestConversion'])->name('analytics.ab-test.conversion');
});

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
    Route::get('/subscriptions', [\App\Http\Controllers\ProfileController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/affiliates', [\App\Http\Controllers\ProfileController::class, 'affiliates'])->name('affiliates');
    Route::get('/activities', [\App\Http\Controllers\ProfileController::class, 'activities'])->name('activities');
    Route::get('/statistics', [\App\Http\Controllers\ProfileController::class, 'statistics'])->name('statistics');
    Route::get('/settings', [\App\Http\Controllers\ProfileController::class, 'settings'])->name('settings');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Mes Documents
    Route::get('/my-documents', [\App\Http\Controllers\DocumentController::class, 'myDocuments'])->name('my-documents');
    
    // Messagerie
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MessageController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\MessageController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\MessageController::class, 'store'])->name('store');
        Route::get('/{conversationId}', [\App\Http\Controllers\MessageController::class, 'show'])->name('show');
        Route::post('/{conversationId}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('reply');
        Route::post('/{conversationId}/delete', [\App\Http\Controllers\MessageController::class, 'deleteConversation'])->name('delete-conversation');
        Route::post('/message/{messageId}/read', [\App\Http\Controllers\MessageController::class, 'markAsRead'])->name('mark-read');
        Route::post('/message/{messageId}/delete', [\App\Http\Controllers\MessageController::class, 'deleteMessage'])->name('delete-message');
        Route::post('/mark-all-read', [\App\Http\Controllers\MessageController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/api/new-messages', [\App\Http\Controllers\MessageController::class, 'getNewMessages'])->name('api.new-messages');
        Route::get('/attachment/{attachmentId}/download', [\App\Http\Controllers\MessageController::class, 'downloadAttachment'])->name('download-attachment');
    });
    
    // Favoris
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    
    // Cours Payants
    Route::get('/paid-courses', [\App\Http\Controllers\ProfileController::class, 'paidCourses'])->name('paid-courses');
    Route::get('/paid-courses/{courseId}', [\App\Http\Controllers\ProfileController::class, 'showPaidCourse'])->name('paid-courses.show');
    
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
    
    // Routes pour la gestion des unités AdSense
    Route::resource('admin/adsense-units', \App\Http\Controllers\Admin\AdSenseUnitController::class)->names([
        'index' => 'admin.adsense-units.index',
        'create' => 'admin.adsense-units.create',
        'store' => 'admin.adsense-units.store',
        'show' => 'admin.adsense-units.show',
        'edit' => 'admin.adsense-units.edit',
        'update' => 'admin.adsense-units.update',
        'destroy' => 'admin.adsense-units.destroy',
    ]);
    
    // Routes pour la gestion des annonces AdSense par formation
    Route::get('/admin/formation-adsense', [\App\Http\Controllers\Admin\FormationAdSenseController::class, 'index'])->name('admin.formation-adsense.index');
    Route::get('/admin/formation-adsense/{slug}', [\App\Http\Controllers\Admin\FormationAdSenseController::class, 'show'])->name('admin.formation-adsense.show');
    Route::post('/admin/formation-adsense', [\App\Http\Controllers\Admin\FormationAdSenseController::class, 'store'])->name('admin.formation-adsense.store');
    
    Route::put('/admin/formation-adsense/{id}', [\App\Http\Controllers\Admin\FormationAdSenseController::class, 'update'])->name('admin.formation-adsense.update');
    
    // Routes pour la gestion des catégories du forum
    Route::resource('admin/forum/categories', \App\Http\Controllers\Admin\ForumCategoryController::class)->names([
        'index' => 'admin.forum.categories.index',
        'create' => 'admin.forum.categories.create',
        'store' => 'admin.forum.categories.store',
        'show' => 'admin.forum.categories.show',
        'edit' => 'admin.forum.categories.edit',
        'update' => 'admin.forum.categories.update',
        'destroy' => 'admin.forum.categories.destroy',
    ]);
    Route::delete('/admin/formation-adsense/{id}', [\App\Http\Controllers\Admin\FormationAdSenseController::class, 'destroy'])->name('admin.formation-adsense.destroy');
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
    
    // Configuration des moyens de paiement
    Route::get('/admin/payment-gateways', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'index'])->name('admin.payment-gateways.index');
    Route::put('/admin/payment-gateways', [App\Http\Controllers\Admin\PaymentGatewayController::class, 'update'])->name('admin.payment-gateways.update');
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

    // Routes Analytics Admin
    Route::prefix('admin/analytics')->name('admin.analytics.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('index');
        Route::get('/funnel/{id}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'showFunnel'])->name('funnel');
        Route::get('/ab-test/{id}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'showABTest'])->name('ab-test');
        Route::get('/heatmap', [\App\Http\Controllers\Admin\AnalyticsController::class, 'showHeatmap'])->name('heatmap');
        Route::get('/ab-tests', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getActiveABTests'])->name('ab-tests');
    });

    // Routes Logs Admin
    Route::prefix('admin/logs')->name('admin.logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('index');
    });

    // Routes Audit de Sécurité Admin
    Route::prefix('admin/security-audit')->name('admin.security-audit.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SecurityAuditController::class, 'index'])->name('index');
        Route::get('/{audit}', [\App\Http\Controllers\Admin\SecurityAuditController::class, 'show'])->name('show');
        Route::get('/export/csv', [\App\Http\Controllers\Admin\SecurityAuditController::class, 'export'])->name('export');
    });

    // Routes Monétisation Admin
    Route::prefix('admin/monetization')->name('admin.monetization.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\MonetizationController::class, 'dashboard'])->name('dashboard');
        Route::get('/subscriptions', [\App\Http\Controllers\Admin\MonetizationController::class, 'subscriptions'])->name('subscriptions');
        Route::get('/subscriptions/{id}', [\App\Http\Controllers\Admin\MonetizationController::class, 'showSubscription'])->name('subscriptions.show');
        Route::post('/subscriptions/{id}/activate', [\App\Http\Controllers\Admin\MonetizationController::class, 'activateSubscription'])->name('subscriptions.activate');
        Route::post('/subscriptions/{id}/deactivate', [\App\Http\Controllers\Admin\MonetizationController::class, 'deactivateSubscription'])->name('subscriptions.deactivate');
        Route::put('/subscriptions/{id}', [\App\Http\Controllers\Admin\MonetizationController::class, 'updateSubscription'])->name('subscriptions.update');
        
        // Routes Plans d'Abonnement (CRUD complet)
        Route::resource('subscription-plans', \App\Http\Controllers\Admin\SubscriptionPlanController::class)->names([
            'index' => 'subscription-plans.index',
            'create' => 'subscription-plans.create',
            'store' => 'subscription-plans.store',
            'show' => 'subscription-plans.show',
            'edit' => 'subscription-plans.edit',
            'update' => 'subscription-plans.update',
            'destroy' => 'subscription-plans.destroy',
        ]);
        
        // Route de compatibilité pour les cours payants (redirige vers l'index)
        Route::get('/courses', function() {
            return redirect()->route('admin.monetization.courses.index');
        })->name('courses');
        
        // Routes Cours Payants (CRUD complet)
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PaidCourseController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\PaidCourseController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\PaidCourseController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\PaidCourseController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\PaidCourseController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\PaidCourseController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\PaidCourseController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [\App\Http\Controllers\Admin\PaidCourseController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\PaidCourseController::class, 'export'])->name('export');
            Route::post('/{id}/duplicate', [\App\Http\Controllers\Admin\PaidCourseController::class, 'duplicate'])->name('duplicate');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\PaidCourseController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Routes Donations (CRUD complet)
        Route::prefix('donations')->name('donations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DonationController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\DonationController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\DonationController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\DonationController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\DonationController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DonationController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DonationController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/complete', [\App\Http\Controllers\Admin\DonationController::class, 'markCompleted'])->name('complete');
            Route::post('/{id}/fail', [\App\Http\Controllers\Admin\DonationController::class, 'markFailed'])->name('fail');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\DonationController::class, 'export'])->name('export');
            Route::get('/statistics', [\App\Http\Controllers\Admin\DonationController::class, 'statistics'])->name('statistics');
        });
        
        Route::get('/affiliates', [\App\Http\Controllers\Admin\MonetizationController::class, 'affiliates'])->name('affiliates');
        Route::get('/affiliates/create', [\App\Http\Controllers\Admin\MonetizationController::class, 'createAffiliate'])->name('affiliates.create');
        Route::post('/affiliates', [\App\Http\Controllers\Admin\MonetizationController::class, 'storeAffiliate'])->name('affiliates.store');
        Route::get('/affiliates/{id}', [\App\Http\Controllers\Admin\MonetizationController::class, 'showAffiliate'])->name('affiliates.show');
        Route::get('/affiliates/{id}/edit', [\App\Http\Controllers\Admin\MonetizationController::class, 'editAffiliate'])->name('affiliates.edit');
        Route::put('/affiliates/{id}', [\App\Http\Controllers\Admin\MonetizationController::class, 'updateAffiliate'])->name('affiliates.update');
        Route::delete('/affiliates/{id}', [\App\Http\Controllers\Admin\MonetizationController::class, 'destroyAffiliate'])->name('affiliates.destroy');
        Route::post('/affiliates/{affiliateId}/referrals/{referralId}/approve', [\App\Http\Controllers\Admin\MonetizationController::class, 'approveReferral'])->name('affiliates.referrals.approve');
        Route::post('/affiliates/{affiliateId}/referrals/{referralId}/pay', [\App\Http\Controllers\Admin\MonetizationController::class, 'payReferral'])->name('affiliates.referrals.pay');
        Route::post('/affiliates/{affiliateId}/referrals/{referralId}/reject', [\App\Http\Controllers\Admin\MonetizationController::class, 'rejectReferral'])->name('affiliates.referrals.reject');
        Route::get('/payments', [\App\Http\Controllers\Admin\MonetizationController::class, 'payments'])->name('payments');
        Route::get('/payments/course/{paymentId}', [\App\Http\Controllers\Admin\MonetizationController::class, 'showCoursePayment'])->name('payments.course.show');
        Route::post('/payments/course/{paymentId}/accept', [\App\Http\Controllers\Admin\MonetizationController::class, 'acceptCoursePayment'])->name('payments.course.accept');
        Route::post('/payments/course/{paymentId}/reject', [\App\Http\Controllers\Admin\MonetizationController::class, 'rejectCoursePayment'])->name('payments.course.reject');
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

    // Routes Documents Admin
    Route::prefix('admin/documents')->name('admin.documents.')->group(function () {
        // Documents
        Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class);
        Route::post('documents/{id}/publish', [\App\Http\Controllers\Admin\DocumentController::class, 'publish'])->name('documents.publish');
        Route::post('documents/{id}/unpublish', [\App\Http\Controllers\Admin\DocumentController::class, 'unpublish'])->name('documents.unpublish');
        Route::get('statistics', [\App\Http\Controllers\Admin\DocumentController::class, 'statistics'])->name('statistics');
        
        // Catégories
        Route::resource('categories', \App\Http\Controllers\Admin\DocumentCategoryController::class);
        
        // Achats
        Route::prefix('purchases')->name('purchases.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DocumentPurchaseController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Admin\DocumentPurchaseController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [\App\Http\Controllers\Admin\DocumentPurchaseController::class, 'approve'])->name('approve');
            Route::post('/{id}/cancel', [\App\Http\Controllers\Admin\DocumentPurchaseController::class, 'cancel'])->name('cancel');
        });
        
        // Modération avis
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DocumentReviewController::class, 'index'])->name('index');
            Route::post('/{reviewId}/approve', [\App\Http\Controllers\Admin\DocumentReviewController::class, 'approve'])->name('approve');
            Route::delete('/{reviewId}', [\App\Http\Controllers\Admin\DocumentReviewController::class, 'destroy'])->name('destroy');
        });
        
        // Codes promo
        Route::resource('coupons', \App\Http\Controllers\Admin\DocumentCouponController::class);
        
        // Bundles
        Route::resource('bundles', \App\Http\Controllers\Admin\DocumentBundleController::class);
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

