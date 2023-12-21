<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title> {{ returnSiteSetting('site_title') ?? 'Hajir'}} @yield('title','')</title>
    <script>
        window.laravel = {
            csrfToken: '{{ csrf_token() }}'
        }
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ getOrginalUrl(returnSiteSetting('favicon')) }}" />

    @include('layouts.admin.style')
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

</head>
<body>
    <!-- BEGIN LOADER -->
    @include('layouts.admin.loader')
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
    @include('layouts.admin.navbar')
    <!--  END NAVBAR  -->

    <!--  BEGIN NAVBAR  -->
    @include('layouts.admin.subheader')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        @include('layouts.admin.sidebar')
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                @yield('content')
        
            </div>
            {{-- @include('layouts.admin.footer') --}}
        </div>
        <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    @include('layouts.admin.script')
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>
</html>
