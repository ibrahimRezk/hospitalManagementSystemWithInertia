<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir = "{{ app()->getlocale() == 'ar' ? 'rtl'  : 'ltr' }}"
    >
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title inertia>{{ config('app.name', 'Laravel') }}</title> 
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        
        {{-- //////////////////// theme files///////////////////////////////////// --}}
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <link href="{{ asset('admin/assets/css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/assets/css/tooltips.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/assets/css/soft-ui-dashboard-tailwind.min.css?v=1.0.4" rel="stylesheet') }}" />
        
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        <script src="{{ asset('admin/assets/js/plugins/chartjs.min.js') }}" async></script>
        <script src="{{ asset('admin/assets/js/soft-ui-dashboard-tailwind.min.js?v=1.0.4') }}" async></script>
{{-- //////////////////////////////////end theme files////////////////////////////////////////// --}}

<!-- Scripts -->
@routes
@vite('resources/js/app.js')

@inertiaHead
</head>
{{-- <body > --}}
  <body class="font-sans antialiased bg-gray-100">
    @inertia
    </body>
</html>
