<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\NewsletterController;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('contact.send');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/exercices', [PageController::class, 'exercices'])->name('exercices');
Route::get('/exercices/{language}', [PageController::class, 'exercicesLanguage'])->name('exercices.language');
Route::get('/exercices/{language}/{id}', [PageController::class, 'exerciceDetail'])->name('exercices.detail');
Route::post('/exercices/{language}/{id}/submit', [PageController::class, 'exerciceSubmit'])->name('exercices.submit');
Route::get('/quiz', [PageController::class, 'quiz'])->name('quiz');
Route::get('/quiz/{language}', [PageController::class, 'quizLanguage'])->name('quiz.language');
Route::post('/quiz/{language}/submit', [PageController::class, 'quizSubmit'])->name('quiz.submit');
Route::get('/legal', [PageController::class, 'legal'])->name('legal');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::post('/newsletter/subscribe', [PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [PageController::class, 'newsletterUnsubscribe'])->name('newsletter.unsubscribe');

// Routes Formations
Route::get('/formations/html5', [PageController::class, 'html5'])->name('formations.html5');
Route::get('/formations/css3', [PageController::class, 'css3'])->name('formations.css3');
Route::get('/formations/javascript', [PageController::class, 'javascript'])->name('formations.javascript');
Route::get('/formations/php', [PageController::class, 'php'])->name('formations.php');
Route::get('/formations/bootstrap', [PageController::class, 'bootstrap'])->name('formations.bootstrap');
Route::get('/formations/git', [PageController::class, 'git'])->name('formations.git');
Route::get('/formations/wordpress', [PageController::class, 'wordpress'])->name('formations.wordpress');
Route::get('/formations/ia', [PageController::class, 'ia'])->name('formations.ia');

// Routes Admin (pas de lien public)
Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.post');
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
Route::post('/admin/profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('admin.profile.update');
Route::get('/admin/statistics', [App\Http\Controllers\AdminController::class, 'statistics'])->name('admin.statistics');
Route::get('/admin/adsense', [App\Http\Controllers\AdminController::class, 'adsense'])->name('admin.adsense');
Route::post('/admin/adsense', [App\Http\Controllers\AdminController::class, 'updateAdsense'])->name('admin.adsense.update');
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

// Newsletter Admin
Route::get('/admin/newsletter', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('admin.newsletter.index');
Route::get('/admin/newsletter/export', [\App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('admin.newsletter.export');
Route::post('/admin/newsletter/{id}/toggle', [\App\Http\Controllers\Admin\NewsletterController::class, 'toggleStatus'])->name('admin.newsletter.toggle');
Route::delete('/admin/newsletter/{id}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('admin.newsletter.destroy');

Route::get('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
