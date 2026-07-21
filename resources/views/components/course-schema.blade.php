@props(['slug'])
@php
    $title = trans("app.formations.$slug.title");
    $description = \Illuminate\Support\Str::limit(strip_tags(trans("app.formations.$slug.description")), 200);
    $courseSchema = [
        "@context" => "https://schema.org",
        "@type" => "Course",
        "name" => $title,
        "description" => $description,
        "url" => url()->current(),
        "provider" => ["@type" => "Organization", "name" => "NiangProgrammeur", "url" => "https://www.niangprogrammeur.com"],
        "instructor" => ["@type" => "Person", "name" => "Bassirou Niang", "url" => "https://www.niangprogrammeur.com"],
        "inLanguage" => "fr",
        "isAccessibleForFree" => true,
        "hasCourseInstance" => ["@type" => "CourseInstance", "courseMode" => "online"]
    ];
@endphp
<script type="application/ld+json">{!! json_encode($courseSchema, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_PRETTY_PRINT) !!}</script>
