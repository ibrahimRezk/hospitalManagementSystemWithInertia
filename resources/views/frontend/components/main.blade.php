
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<x-head/>

<body id="top">

<x-header/>

{{ $slot }}

<x-footer/>


<x-main-scripts/>

  </body>
  </html>