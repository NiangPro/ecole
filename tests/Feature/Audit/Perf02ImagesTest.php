<?php

namespace Tests\Feature\Audit;

use App\Models\SiteSetting;
use App\Support\ImageVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Audit PERF-02 : images servies à une taille proche de leur taille d'affichage.
 */
class Perf02ImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_variante_redimensionnee_en_webp(): void
    {
        $dir = sys_get_temp_dir() . '/np-variants-' . uniqid();
        $variant = ImageVariant::make(public_path('images/logo.png'), 64, $dir);

        $this->assertStringEndsWith('.webp', $variant);
        [$w] = getimagesize($variant);
        $this->assertSame(64, $w);

        // Une image déjà assez petite est renvoyée telle quelle.
        $this->assertSame(public_path('images/sokhna-80.webp'), ImageVariant::make(public_path('images/sokhna-80.webp'), 440, $dir));
    }

    public function test_logo_en_64_et_128_px_avec_srcset(): void
    {
        $url64 = SiteSetting::logoVariantUrl(64);
        $url128 = SiteSetting::logoVariantUrl(128);
        $this->assertStringContainsString('logos/variants/', $url64);

        $this->get('/')->assertOk()
            ->assertSee('srcset="' . $url64 . ' 1x, ' . $url128 . ' 2x"', false)
            ->assertSee('images/sokhna-80.webp', false);
    }

    /** Bout en bout : la couverture signée avec ?w=440 est servie redimensionnée (WebP). */
    public function test_couverture_signee_servie_en_440_px(): void
    {
        $rel = 'document-covers/test-perf02-' . uniqid() . '.png';
        $abs = storage_path('app/public/' . $rel);
        @mkdir(dirname($abs), 0755, true);
        $img = imagecreatetruecolor(1086, 1448);
        imagepng($img, $abs);
        imagedestroy($img);

        try {
            $cat = \App\Models\DocumentCategory::forceCreate(['name' => 'Cat', 'slug' => 'cat-perf02']);
            $doc = \App\Models\Document::forceCreate([
                'category_id' => $cat->id,
                'author_id' => \App\Models\User::forceCreate(['name' => 'Auteur', 'email' => 'auteur-perf02@example.com', 'password' => 'x'])->id,
                'title' => 'Doc test', 'slug' => 'doc-test-perf02', 'description' => 'd',
                'file_path' => 'documents/x.pdf', 'file_name' => 'x.pdf', 'file_size' => 1, 'file_type' => 'application/pdf', 'file_extension' => 'pdf',
                'cover_image' => $rel, 'cover_type' => 'internal', 'price' => 0, 'is_free' => true,
                'is_active' => true, 'status' => 'published',
            ]);

            $url = \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHour(), ['id' => $doc->id, 'w' => 440]);
            $res = $this->get($url)->assertOk();
            $file = $res->baseResponse->getFile()->getPathname();
            [$w, $h] = getimagesize($file);
            $this->assertSame([440, 587], [$w, $h]); // 1448 × 440 / 1086
            $this->assertSame('image/webp', $res->headers->get('Content-Type'));

            // Sans w : l'original.
            $orig = \Illuminate\Support\Facades\URL::temporarySignedRoute('document.cover.signed', now()->addHour(), ['id' => $doc->id]);
            $this->assertSame(1086, getimagesize($this->get($orig)->assertOk()->baseResponse->getFile()->getPathname())[0]);
        } finally {
            @unlink($abs);
        }
    }
}
