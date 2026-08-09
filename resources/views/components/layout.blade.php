@props(['title' => null, 'noindex' => false])

<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title ? $title.' — '.config('festival.name') : config('festival.name').' — 26 september 2026' }}</title>
<meta name="description" content="{{ config('festival.lead') }}">
@if ($noindex)
<meta name="robots" content="noindex, nofollow">
@endif

<meta property="og:title" content="{{ config('festival.name') }}">
<meta property="og:description" content="{{ config('festival.lead') }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('assets/apa.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Archivo:wght@400;500;700;900&family=Bagel+Fat+One&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/t-shirt.css') }}">
</head>
<body>

{{ $slot }}

<script src="{{ asset('js/site.js') }}" defer></script>
@stack('scripts')
</body>
</html>
