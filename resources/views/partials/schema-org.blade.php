@php
try {
    $siteSettings = \App\Models\SiteSetting::first();
    $siteName = ($siteSettings && isset($siteSettings->site_name)) ? $siteSettings->site_name : 'NiangProgrammeur';
    $siteUrl = url('/');
    $description = ($siteSettings && isset($siteSettings->site_description)) ? $siteSettings->site_description : 'Plateforme de formation gratuite en développement web';
    
    $socialLinks = [];
    if ($siteSettings) {
        if (!empty($siteSettings->facebook_url)) $socialLinks[] = $siteSettings->facebook_url;
        if (!empty($siteSettings->twitter_url)) $socialLinks[] = $siteSettings->twitter_url;
        if (!empty($siteSettings->instagram_url)) $socialLinks[] = $siteSettings->instagram_url;
        if (!empty($siteSettings->linkedin_url)) $socialLinks[] = $siteSettings->linkedin_url;
        if (!empty($siteSettings->youtube_url)) $socialLinks[] = $siteSettings->youtube_url;
    }
    
    $sameAsArray = [];
    foreach ($socialLinks as $link) {
        if (!empty($link)) {
            $sameAsArray[] = json_encode($link, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT);
        }
    }
    $sameAsJson = count($sameAsArray) > 0 ? '[' . implode(',', $sameAsArray) . ']' : '[]';
    
    $contactPhone = ($siteSettings && isset($siteSettings->contact_phone)) ? $siteSettings->contact_phone : '+221783123657';
    $contactEmail = ($siteSettings && isset($siteSettings->contact_email)) ? $siteSettings->contact_email : 'contact@niangprogrammeur.com';
    
    // Organization Schema
    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $siteName,
        'url' => $siteUrl,
        'logo' => asset('images/logo.png'),
        'description' => $description,
        'sameAs' => $socialLinks,
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => $contactPhone,
            'contactType' => 'customer service',
            'email' => $contactEmail
        ]
    ];
    $orgJson = json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT);
    
    // Website Schema
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteName,
        'url' => $siteUrl,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => url('/search?q={search_term_string}')
            ],
            'query-input' => 'required name=search_term_string'
        ]
    ];
    $websiteJson = json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT);
    
    // Article Schema (if article exists)
    $articleJson = null;
    if (isset($article) && !empty($article)) {
        try {
            $articleUrl = route('emplois.article', $article->slug);
            $articleImage = $article->cover_image 
                ? ($article->cover_type === 'internal' ? asset(\Illuminate\Support\Facades\Storage::url($article->cover_image)) : $article->cover_image)
                : asset('images/logo.png');
            $articleTitle = isset($article->title) ? $article->title : '';
            $articleDesc = isset($article->meta_description) ? $article->meta_description : (isset($article->excerpt) ? $article->excerpt : (isset($article->content) ? substr(strip_tags($article->content), 0, 160) : ''));
            $articlePubDate = ($article->published_at) ? $article->published_at->toIso8601String() : (($article->created_at) ? $article->created_at->toIso8601String() : '');
            $articleModDate = ($article->updated_at) ? $article->updated_at->toIso8601String() : '';
            
            if (!empty($articleUrl) && !empty($articleTitle)) {
                $articleSchema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $articleTitle,
                    'description' => $articleDesc,
                    'image' => $articleImage,
                    'datePublished' => $articlePubDate,
                    'dateModified' => $articleModDate,
                    'author' => ['@type' => 'Organization', 'name' => $siteName],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => $siteName,
                        'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo.png')]
                    ],
                    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $articleUrl]
                ];
                $articleJson = json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            $articleJson = null;
        }
    }
    
    // Course Schema (if formation page)
    $formationJson = null;
    if (request()->routeIs('formations.*')) {
        $formationName = '';
        $formationUrl = url()->current();
        
        if (request()->routeIs('formations.html5')) $formationName = 'Formation HTML5';
        elseif (request()->routeIs('formations.css3')) $formationName = 'Formation CSS3';
        elseif (request()->routeIs('formations.javascript')) $formationName = 'Formation JavaScript';
        elseif (request()->routeIs('formations.php')) $formationName = 'Formation PHP';
        elseif (request()->routeIs('formations.laravel')) $formationName = 'Formation Laravel';
        elseif (request()->routeIs('formations.bootstrap')) $formationName = 'Formation Bootstrap';
        elseif (request()->routeIs('formations.git')) $formationName = 'Formation Git';
        elseif (request()->routeIs('formations.wordpress')) $formationName = 'Formation WordPress';
        elseif (request()->routeIs('formations.ia')) $formationName = 'Formation Intelligence Artificielle';
        
        if (!empty($formationName)) {
            $formationSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'Course',
                'name' => $formationName,
                'description' => 'Formation complète et gratuite en ' . $formationName,
                'provider' => ['@type' => 'Organization', 'name' => $siteName, 'url' => $siteUrl],
                'url' => $formationUrl,
                'educationalLevel' => 'beginner',
                'courseMode' => 'online'
            ];
            $formationJson = json_encode($formationSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_PRETTY_PRINT);
        }
    }
} catch (\Exception $e) {
    $orgJson = null;
    $websiteJson = null;
    $articleJson = null;
    $formationJson = null;
}
@endphp
<script type="application/ld+json">
{!! $orgJson ?? '' !!}
</script>
<script type="application/ld+json">
{!! $websiteJson ?? '' !!}
</script>
@php if (!empty($articleJson)) { @endphp
<script type="application/ld+json">
{!! $articleJson !!}
</script>
@php } @endphp
@php if (!empty($formationJson)) { @endphp
<script type="application/ld+json">
{!! $formationJson !!}
</script>
@php } @endphp

