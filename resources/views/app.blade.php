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
<link href="{{ asset('admin/assets/css/perfect-scrollbar.css') }}" rel="stylesheet" />
<link href="{{ asset('admin/assets/css/tooltips.css') }}" rel="stylesheet" />


<script src="https://unpkg.com/@popperjs/core@2"></script>

{{-- // not present in dashboard files --}}
{{-- <link href="../assets/css/soft-ui-dashboard-tailwind.min.css?v=1.0.4" rel="stylesheet" /> --}}

        {{-- <script>
            (function(a, s, y, n, c, h, i, d, e) {
              s.className += ' ' + y;
              h.start = 1 * new Date;
              h.end = i = function() {
                s.className = s.className.replace(RegExp(' ?' + y), '')
              };
              (a[n] = a[n] || []).hide = h;
              setTimeout(function() {
                i();
                h.end = null
              }, c);
              h.timeout = c;
            })(window, document.documentElement, 'async-hide', 'dataLayer', 4000, {
              'GTM-K9BGS8K': true
            });
          </script>
      <script>
            (function(i, s, o, g, r, a, m) {
              i['GoogleAnalyticsObject'] = r;
              i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
              }, i[r].l = 1 * new Date();
              a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
              a.async = 1;
              a.src = g;
              m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-46172202-22', 'auto', {
              allowLinker: true
            });
            ga('set', 'anonymizeIp', true);
            ga('require', 'GTM-K9BGS8K');
            ga('require', 'displayfeatures');
            ga('require', 'linker');
            ga('linker:autoLink', ["2checkout.com", "avangate.com"]);
          </script>
      <script>
            (function(w, d, s, l, i) {
              w[l] = w[l] || [];
              w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
              });
              var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
              j.async = true;
              j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
              f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', 'GTM-NKDMSK6');
          </script>

 --}}

{{-- 
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194" integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw==" data-cf-beacon='{"rayId":"75743818f9025fa0","token":"1b7cbb72744b40c580f8633c6b62637e","version":"2022.8.1","si":100}' crossorigin="anonymous"></script> --}}


<script src="{{ asset('admin/assets/js/plugins/chartjs.min.js') }}" async></script>
<script src="{{ asset('admin/assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>


<script async defer src="https://buttons.github.io/buttons.js"></script>

<script src="{{ asset('admin/assets/js/soft-ui-dashboard-tailwind.min.js') }}" async></script>

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
