<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.social-meta', [
        'metaTitle' => $project->title,
        'metaDescription' => $description,
        'canonicalUrl' => 'https://www.engipi.com/projects/'.$project->getRouteKey(),
        'socialImage' => $image,
        'socialImageType' => $imageType,
        'socialImageAlt' => $project->title,
    ])
    <link rel="stylesheet" href="{{ asset('vendor/engipi/fonts/vazirmatn.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/engipi/css/blueprint.css') }}">
</head>
<body>
    <main class="bp-container" style="max-width: 860px; padding-block: 48px">
        <a href="{{ route('root') }}">EngiPi</a>
        <article style="margin-top: 32px">
            <h1>{{ $project->title }}</h1>
            <p>{!! nl2br(e($project->description)) !!}</p>

            @if($project->skills->isNotEmpty())
                <h2>مهارت‌های مورد نیاز</h2>
                <ul>
                    @foreach($project->skills as $skill)
                        <li>{{ $skill->name }}</li>
                    @endforeach
                </ul>
            @endif
        </article>
    </main>
</body>
</html>
