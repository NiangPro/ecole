<?php

namespace Tests\Feature\Audit;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Audit FCT-04 : tout lien target="_blank" des vues porte rel="noopener noreferrer".
 */
class Fct04BlankLinksTest extends TestCase
{
    public function test_aucun_lien_blank_sans_noopener_noreferrer(): void
    {
        $fautifs = [];
        foreach (File::allFiles(resource_path('views')) as $file) {
            preg_match_all('/<a\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>/s', $file->getContents(), $m);
            foreach ($m[0] as $tag) {
                if (preg_match('/\btarget\s*=\s*"_blank"/', $tag)
                    && ! preg_match('/\brel\s*=\s*"(?=[^"]*\bnoopener\b)(?=[^"]*\bnoreferrer\b)[^"]*"/', $tag)) {
                    $fautifs[] = $file->getRelativePathname() . ' : ' . preg_replace('/\s+/', ' ', substr($tag, 0, 120));
                }
            }
        }

        $this->assertSame([], $fautifs);
    }
}
