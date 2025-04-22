<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel App')</title>
    <!-- Import styling from the bolt.new project -->
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    <!-- You might need to adjust the asset path if you copy these files to public -->
</head>

<body>
    <div id="app" class="container">
        <!-- Sidebar section modeled after bolt.new -->
        <div class="sidebar">
            <h2>Laravel API Actions</h2>
            <div class="button-group" id="button-container">
                @yield('sidebar-buttons')
            </div>
        </div>

        <!-- Main content area -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    <!-- Import JS from the bolt.new project -->
</body>

</html>
