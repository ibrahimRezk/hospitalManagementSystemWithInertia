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

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />


        {{-- //////////////////// theme files///////////////////////////////////// --}}

<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

<link href="{{ asset('admin/assets/css/styles.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/assets/css/tooltips.css') }}" rel="stylesheet" />


<link href="{{ asset('admin/assets/css/soft-ui-dashboard-tailwind.min.css?v=1.0.4" rel="stylesheet') }}" />


<link href="{{ asset('admin/assets/css/perfect-scrollbar.css') }}" rel="stylesheet" />
<script src="{{ asset('admin/assets/js/plugins/perfect-scrollbar.min.js') }}" type="text/javascript" async="true"></script>

</body>

<script src="https://unpkg.com/@popperjs/core@2"></script>

{{-- // not present in dashboard files --}}
{{-- <link href="../assets/css/soft-ui-dashboard-tailwind.min.css?v=1.0.4" rel="stylesheet" /> --}}






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
