to use any bootstrap theme like admin lte rtl ltr use the following 

we put css files and js files in app.blade.php not app.js witch use vite

in app.blade.php

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">



    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">



    {{-- // important  to append styles in html file //// --}}
    <link id="rtlBootstrapCss" rel="stylesheet">
    <link id="rtlCustumCss" rel="stylesheet">

    @if (app()->getLocale() == 'ar')
        <link id="rtlBootstrapCss2" rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">
        <!-- Custom style for RTL -->
        <link id="rtlCustumCss2" rel="stylesheet" href="{{ asset('assets/dist/css/custom.css') }}">
    @endif



    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 rtl -->
    <script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.world.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>



    <!-- Scripts -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body class="font-sans antialiased ">
    @inertia
</body>

</html>
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
in layout.vue use this 
script section 


const changeLanguage = () => {
  // var a = document.createElement('link');

  let currentLang = document.getElementsByTagName("html")[0].getAttribute("lang");

  if (currentLang == "en") {
    document.documentElement.setAttribute("lang", "ar");

    document.getElementById("rtlBootstrapCss").href = "https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css";

    document.getElementById("rtlCustumCss").href = "http://127.0.0.1:8002/assets/dist/css/custom.css";

  } else {

    document.documentElement.setAttribute("lang", "en");

    document.getElementById("rtlBootstrapCss").removeAttribute("href");
    document.getElementById("rtlBootstrapCss2").removeAttribute("href");

    document.getElementById("rtlCustumCss").removeAttribute("href");
    document.getElementById("rtlCustumCss2").removeAttribute("href");
  }

  Inertia.get(route("lang"));
};


// //// tip : to append any code in html page use this  you can use it to append new css files in several pages rather than upload all css files in the first time in  app.blade.php .$props
// var a = document.createElement('a'); // a can be any thing else like link or div 
//       var linkText = document.createTextNode("my title text");
//       a.appendChild(linkText);
//       a.title = "my title text";
//       a.href = "http://example.com";
//       document.body.appendChild(a);

template section 

just link to the function of lang change 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
middle ware for lang like this project 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

route like this or the one in the project and it can be custimized 


Route::get('/change_lang', function () {
   
   $currentlocale = app()->getLocale();

   if($currentlocale == 'ar'){
       App::setLocale('en');
   }else{
        App::setLocale('ar');
       }

       $locale = App::getLocale();
       session()->put('lang',  $locale);

    return redirect()->back();
})->name('lang')->middleware('Lang');