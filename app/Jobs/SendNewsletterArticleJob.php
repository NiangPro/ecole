<?php

namespace App\Jobs;

use App\Models\JobArticle;
use App\Models\Newsletter;
use App\Mail\NewsletterArticleMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsletterArticleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $article;

    /**
     * Create a new job instance.
     */
    public function __construct(JobArticle $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Charger la configuration mail depuis la base de données
            $this->loadMailConfig();
            
            // Récupérer tous les abonnés actifs
            $subscribers = Newsletter::where('is_active', true)->get();
            
            if ($subscribers->isEmpty()) {
                Log::info('Aucun abonné actif pour envoyer la newsletter');
                return;
            }

            $count = 0;
            $articleUrl = str_contains(config('app.url'), 'niangprogrammeur.com') 
                ? 'https://www.niangprogrammeur.com/emplois/article/' . $this->article->slug
                : route('emplois.article', $this->article->slug);

            foreach ($subscribers as $subscriber) {
                try {
                    Mail::to($subscriber->email)->send(new NewsletterArticleMail($this->article, $subscriber->email));
                    $count++;
                    
                    // Délai entre chaque envoi pour éviter de surcharger le serveur
                    usleep(100000); // 0.1 seconde
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi de l\'email à ' . $subscriber->email . ': ' . $e->getMessage());
                }
            }

            Log::info("Newsletter envoyée avec succès à {$count} abonné(s) pour l'article: {$this->article->title}");
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la newsletter: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Charger la configuration mail depuis la base de données
     */
    private function loadMailConfig(): void
    {
        try {
            $settings = \App\Models\SiteSetting::first();
            if ($settings) {
                if ($settings->mail_mailer) {
                    config(['mail.default' => $settings->mail_mailer]);
                }
                if ($settings->mail_host) {
                    config(['mail.mailers.smtp.host' => $settings->mail_host]);
                }
                if ($settings->mail_port) {
                    config(['mail.mailers.smtp.port' => (int)$settings->mail_port]);
                }
                if ($settings->mail_username) {
                    config(['mail.mailers.smtp.username' => $settings->mail_username]);
                }
                if ($settings->mail_password) {
                    config(['mail.mailers.smtp.password' => $settings->mail_password]);
                }
                if ($settings->mail_encryption) {
                    config(['mail.mailers.smtp.encryption' => $settings->mail_encryption]);
                }
                if ($settings->mail_from_address) {
                    config(['mail.from.address' => $settings->mail_from_address]);
                }
                if ($settings->mail_from_name) {
                    config(['mail.from.name' => $settings->mail_from_name]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Impossible de charger la configuration mail depuis la base de données: ' . $e->getMessage());
        }
    }
}
