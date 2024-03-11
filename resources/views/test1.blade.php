<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Elevate your messaging experience by seamlessly grouping contacts and delivering personalized bulk messages that resonate uniquely with each audience. Ignite engagement beyond words with {{ config('app.name') }}!">

        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="Tailoring Texts One Group At A Time">
        <meta property="og:image" content="">
        <meta property="og:image:url" content="">
        <meta property="og:image:secure_url" content="">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:height" content="">
        <meta property="og:image:width" content="">
        <meta property="og:image:alt" content="">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ config('app.url') }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@_m0ssad">
        <meta name="twitter:creator" content="@_m0ssad">
        <meta name="twitter:description" content="Elevate your messaging experience by seamlessly grouping contacts and delivering personalized bulk messages that resonate uniquely with each audience. Ignite engagement beyond words with {{ config('app.name') }}!">
        <meta name="twitter:image" content="">
        <meta name="twitter:image:alt" content="flairsms-twitter-image.png">
        <meta name="twitter:title" content="Tailoring Texts One Group At A Time">

        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('/site.webmanifest') }}">

        <meta name="theme-color" content="#7e22ce">

        <title>Tailoring Texts One Group At A Time</title>

        <link rel="canonical" href="{{ config('app.url') }}">

        <link rel="alternate" type="application/atom+xml" href="{{ config('app.url') }}" title="Tailoring Texts One Group At A Time">

        <link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

        @vite('resources/css/app.css')

        @livewireStyles
    </head>

    <body aria-description="Tailoring Texts One Group At A Time" class="bg-white dark:bg-gray-900 poppins-regular">

    <header>
        <nav x-data="{ open: true }" class="absolute z-10 w-full">
            <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                <div class="relative flex flex-wrap items-center justify-between gap-6 py-3 md:gap-0 md:py-4">
                    <div class="relative z-20 flex w-full justify-between md:px-0 lg:w-max">
                        <a href="{{ route('home') }}" aria-label="logo" class="flex items-center space-x-3">
                            <div aria-hidden="true">
                                <x-icon class="h-6 sm:h-7 lg:h-10 rounded" />
                            </div>
                            <span class="text-xl lg:text-2xl font-bold tracking-wide text-gray-900 dark:text-white">
                                <span class="text-purple-800">{{ substr(config('app.name'),0,1) }}</span>{{ substr(config('app.name'),1, strlen(config('app.name'))) }}
                            </span>
                        </a>

                        <div class="relative flex max-h-10 items-center lg:hidden">
                            <button @click="open = ! open" aria-label="humburger" id="hamburger" class="relative -mr-6 p-6">
                                <span aria-label="label" class="sr-only">toggle nav</span>
                                <!-- hamburger -->
                                <span aria-hidden="true" x-cloack x-show="!open">
                                    <x-icons.hamburger/>
                                </span>
                                <!-- close -->
                                <span aria-hidden="true" x-cloack x-show="open">
                                    <x-icons.close/>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div id="navLayer" aria-hidden="true" class="fixed inset-0 z-10 h-screen w-screen origin-bottom scale-y-0 bg-white/70 backdrop-blur-2xl transition duration-500 dark:bg-gray-900/70 lg:hidden" x-bind:class="open ? 'origin-top scale-y-100' : ''"></div>

                    <div id="navlinks" class="absolute top-full left-0 z-20 w-full origin-top-right translate-y-1 scale-90 flex-col flex-wrap justify-end gap-6 rounded-lg border border-gray-100 bg-white p-8 opacity-0 shadow-2xl shadow-gray-600/10 transition-all duration-300 dark:border-gray-700 dark:bg-gray-800 dark:shadow-none lg:visible lg:relative lg:flex lg:w-7/12 lg:translate-y-0 lg:scale-100 lg:flex-row lg:items-center lg:gap-0 lg:border-none lg:bg-transparent lg:p-0 lg:opacity-100 lg:shadow-none" x-bind:class="open ? 'lg:shadow-none!visible  !scale-100 !opacity-100 !lg:translate-y-0' : ''">
                        <div class="w-full text-gray-600 dark:text-gray-200 lg:w-auto lg:pr-4 lg:pt-0 ">
                            <ul class="flex flex-col gap-6 tracking-wide lg:flex-row lg:gap-0 lg:text-sm ">
                                <li class="">
                                    <a href="/#features" class="hover:text-purple-700 block transition dark:hover:text-white md:px-4 ">
                                        <span class="">Features</span>
                                    </a>
                                </li><li class="">
                                    <a href="/#solution" class="hover:text-purple-700 block transition dark:hover:text-white md:px-4 ">
                                        <span class="">Solution</span>
                                    </a>
                                </li><li class="">
                                    <a href="/#reviews" class="hover:text-purple-700 block transition dark:hover:text-white md:px-4 ">
                                        <span class="">Reviews</span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#" target="_blank" class="flex gap-2 font-semibold text-gray-700 transition hover:text-purple-700 dark:text-white dark:hover:text-white md:px-4 ">
                                        <span class="">Premium</span>
                                        <span class="flex rounded-full bg-purple-700/20 px-1.5 py-0.5 text-xs tracking-wider text-purple-700 dark:bg-white/10 dark:text-orange-300 "> new</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="mt-8 lg:mt-0 ">
                            <a href="/register" class="relative flex h-9 w-full items-center justify-center px-4 before:absolute before:inset-0 before:rounded-full before:bg-purple-700 before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 sm:w-max">
                                <span class="relative text-sm font-semibold text-white"> Get Started</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="space-y-40 mb-40">
        <div class="relative" id="home">
            <div aria-hidden="true" class="absolute inset-0 grid grid-cols-2 -space-x-52 opacity-40 dark:opacity-20">
                <div class="blur-[106px] h-56 bg-gradient-to-br from-purple-700 to-purple-400 dark:from-blue-700"></div>
                <div class="blur-[106px] h-32 bg-gradient-to-r from-cyan-400 to-sky-300 dark:to-indigo-600"></div>
            </div>
            <div class="max-w-7xl mx-auto px-6 md:px-12 xl:px-6">
                <div class="relative pt-36 ml-auto">
                    <div class="lg:w-2/3 text-center mx-auto">
                        <h1 class="text-gray-900 dark:text-white font-bold text-5xl md:text-6xl xl:text-7xl">Tailoring Texts, One <span class="text-purple-700 dark:text-white">Group.</span> at a Time</h1>
                        <p class="mt-8 text-gray-700 dark:text-gray-300">With {{ config('app.name') }} we Elevate your messaging experience by seamlessly grouping contacts and delivering personalized messages that resonate uniquely with each audience. Craft connections that go beyond words, ignite engagement with {{ config('app.name') }}!</p>
                        <div class="mt-16 flex flex-wrap justify-center gap-y-4 gap-x-6">
                            <a href="#" class="relative flex h-11 w-full items-center justify-center px-6 before:absolute before:inset-0 before:rounded-full before:bg-purple-700 before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 sm:w-max">
                                <span class="relative text-base font-semibold text-white">Get started</span>
                            </a>
                            <a href="/#" class="relative flex h-11 w-full items-center justify-center px-6 before:absolute before:inset-0 before:rounded-full before:border before:border-transparent before:bg-purple-700/10 before:bg-gradient-to-b before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 dark:before:border-gray-700 dark:before:bg-gray-800 sm:w-max">
                                <span class="relative text-base font-semibold text-purple-700 dark:text-white">Learn more</span>
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </main>

    @livewireScripts

    </body>
</html>
