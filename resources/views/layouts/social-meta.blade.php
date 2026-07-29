@php
    $seoProductionOrigin = 'https://www.engipi.com';
    $seoDefaultTitle = 'EngiPi | بازار تخصصی پروژه‌های مهندسی';
    $seoDefaultDescription = 'پروژه مهندسی خود را ثبت کنید و با متخصصان واقعی در حوزه‌های مختلف مهندسی همکاری کنید.';
    $seoDefaultImage = $seoProductionOrigin . '/images/engipi-og.jpg';

    $seoSectionTitle = trim($__env->yieldContent('meta_title'));
    $seoPageTitle = trim($__env->yieldContent('title'));
    $seoResolvedTitle = $metaTitle ?? (
        $seoSectionTitle !== ''
            ? $seoSectionTitle
            : ($seoPageTitle !== '' ? $seoPageTitle . ' | EngiPi' : $seoDefaultTitle)
    );

    $seoSectionDescription = trim($__env->yieldContent('meta_description'));
    $seoResolvedDescription = $metaDescription
        ?? ($seoSectionDescription !== '' ? $seoSectionDescription : $seoDefaultDescription);

    $seoPath = request()->path();
    $seoCurrentCanonical = $seoProductionOrigin . ($seoPath === '/' ? '' : '/' . ltrim($seoPath, '/'));
    $seoSectionCanonical = trim($__env->yieldContent('canonical_url'));
    $seoResolvedCanonical = $canonicalUrl
        ?? ($seoSectionCanonical !== '' ? $seoSectionCanonical : $seoCurrentCanonical);
    if (!str_starts_with($seoResolvedCanonical, 'https://')) {
        $seoResolvedCanonical = $seoProductionOrigin . '/' . ltrim($seoResolvedCanonical, '/');
    }

    $seoSectionImage = trim($__env->yieldContent('social_image'));
    $seoResolvedImage = $socialImage
        ?? ($seoSectionImage !== '' ? $seoSectionImage : $seoDefaultImage);
    if (!str_starts_with($seoResolvedImage, 'https://')) {
        $seoResolvedImage = $seoProductionOrigin . '/' . ltrim($seoResolvedImage, '/');
    }

    $seoResolvedImageAlt = $socialImageAlt ?? ('تصویر معرفی ' . $seoResolvedTitle);
@endphp

<title>{{ $seoResolvedTitle }}</title>
<meta name="description" content="{{ $seoResolvedDescription }}">
<meta name="author" content="EngiPi">
<link rel="canonical" href="{{ $seoResolvedCanonical }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="EngiPi">
<meta property="og:title" content="{{ $seoResolvedTitle }}">
<meta property="og:description" content="{{ $seoResolvedDescription }}">
<meta property="og:url" content="{{ $seoResolvedCanonical }}">
<meta property="og:image" content="{{ $seoResolvedImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="{{ $socialImageType ?? 'image/jpeg' }}">
<meta property="og:image:alt" content="{{ $seoResolvedImageAlt }}">
<meta property="og:locale" content="fa_IR">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoResolvedTitle }}">
<meta name="twitter:description" content="{{ $seoResolvedDescription }}">
<meta name="twitter:image" content="{{ $seoResolvedImage }}">
