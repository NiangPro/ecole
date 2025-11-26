<?php

namespace App\Mail;

use App\Models\JobArticle;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewsletterArticleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $article;
    public $unsubscribeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(JobArticle $article, $subscriberEmail)
    {
        $this->article = $article;
        $this->unsubscribeUrl = route('newsletter.unsubscribe', [
            'token' => \App\Models\Newsletter::where('email', $subscriberEmail)->value('token')
        ]);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $articleUrl = str_contains(config('app.url'), 'niangprogrammeur.com') 
            ? 'https://www.niangprogrammeur.com/emplois/article/' . $this->article->slug
            : route('emplois.article', $this->article->slug);
        
        $coverImage = $this->article->cover_image 
            ? ($this->article->cover_type === 'internal' 
                ? url('storage/' . $this->article->cover_image) 
                : $this->article->cover_image)
            : url('images/logo.png');

        return $this->subject('📰 Nouvel article : ' . $this->article->title)
                    ->view('emails.newsletter.article')
                    ->with([
                        'article' => $this->article,
                        'articleUrl' => $articleUrl,
                        'coverImage' => $coverImage,
                        'unsubscribeUrl' => $this->unsubscribeUrl,
                    ]);
    }
}
