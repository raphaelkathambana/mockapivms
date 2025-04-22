{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body
    class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <h1 class="dark:text-[#EDEDEC] text-[#1b1b18]">API MOCK UP - VMS</h1>
    <h2 class="dark:text-[#EDEDEC] text-[#1b1b18]">Manna - Siemon</h2>

    {{-- Success / Error Message Object --}}

    {{-- @if (session('status'))
        <div class="mt-6 w-full lg:max-w-4xl max-w-[335px]">
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        </div>
    @endif


    <div class="mt-6 w-full lg:max-w-4xl max-w-[335px]">
        <h3 class="dark:text-[#EDEDEC] text-[#1b1b18]">Items</h3>
        @if (isset($items) && count($items) > 0)
            <ul class="list-disc pl-5">
                @foreach ($items as $item)
                    <li class="dark:text-[#EDEDEC] text-[#1b1b18]">
                        {{ $item['name'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="dark:text-[#EDEDEC] text-[#1b1b18]">No items available yet.</p>
        @endif
    </div>

    {{-- Create Button --}}

    {{-- <div class="mt-6 w-full lg:max-w-4xl max-w-[335px]">
        <a href="{{ route('items.create') }}"
            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
            Create Item
        </a>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </div>
</body>

</html> --}}
@extends('layouts.app')

@section('title', 'Items')

@section('sidebar-buttons')
    <!-- Instead of buttons created via JS, you can add Blade links or even let main.js generate dynamic buttons -->
    <a href="{{ route('index') }}" class="btn">Home</a>
    <a href="{{ route('items.index') }}" class="btn">Items</a>
    <a href="{{ route('items.create') }}" class="btn">Create Item</a>
@endsection

@section('content')
    {{-- Success / Error Message Object --}}
    @if (session('status'))
        <div class="mt-6 w-full lg:max-w-4xl max-w-[335px]">
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <div class="mt-6 w-full lg:max-w-4xl max-w-[335px]">
        <h3 class="dark:text-[#EDEDEC] text-[#1b1b18]">Items</h3>
        @if (isset($items) && count($items) > 0)
            <ul class="list-disc pl-5">
                @foreach ($items as $item)
                    <li class="dark:text-[#EDEDEC] text-[#1b1b18]">
                        {{ $item['name'] }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="dark:text-[#EDEDEC] text-[#1b1b18]">No items available yet.</p>
        @endif
    </div>
@endsection
