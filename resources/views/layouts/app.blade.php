<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

            /*body {*/
            /*    margin: 0;*/
            /*    text-align: center;*/
            /*    font-family: 'Press Start 2P', cursive;*/
            /*    user-select: none;*/
            /*}*/
            /*header {*/
            /*    margin: 0 auto;*/
            /*    width: 431px;*/
            /*}*/
            /*h1 {*/
            /*    background: url("https://i.ibb.co/Q9yv5Jk/flappy-bird-set.png") 0% 340px;*/
            /*    padding: 1.2rem 0;*/
            /*    margin: 0;*/
            /*}*/
            /*.score-container {*/
            /*    display: flex;*/
            /*    justify-content: space-between;*/
            /*    padding: 8px 6px;*/
            /*    background: #5EE270;*/
            /*}*/

            canvas {
                display: block;
                margin: 0 auto;
                background: #70c5ce;
            }

            /* Popup container */
            .popup {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }

            /* Popup content */
            .popup-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                max-width: 400px;
                text-align: center;
                position: relative;
            }

            /* Close button */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
