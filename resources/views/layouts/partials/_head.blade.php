<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name', 'Laravel') }}</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
@if (LaravelLocalization::setLocale() === 'ar')
    <link href="{{ asset('css/bootstrap.rtl.min.css') }}" rel="stylesheet">
@else
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
@endif
<!-- Styles -->
<link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@livewireStyles
