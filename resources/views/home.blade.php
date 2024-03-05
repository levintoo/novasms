<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home - {{ config('app.name', 'Novasms') }}</title>

        <link rel="icon" href="{{ asset('icon.png') }}" />

        <!-- Scripts -->
        @vite(['resources/css/app.css','resources/css/app.js'])
    </head>
    <body>
        <div class="bg-white">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Novasms</span>
                        <img src="{{ asset('assets/images/logo.png') }}" class="h-8 md:h-12 w-auto"  alt="logo.png"/>
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button id="toggleSidebarButton" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Home</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Pricing</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Features</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Contact</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Company</a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <div>
                        @auth()
                            <a
                                href="{{ route('dashboard') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                            >Dashboard</a>
                        @endauth

                        @guest()
                            <a
                                href="{{ route('login') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                            >Log in</a>

                            <a
                                href="{{ route('register') }}"
                                class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                            >Register</a>
                        @endguest
                    </div>
                </div>
            </nav>
            <!-- Mobile menu, show/hide based on menu open state. -->
            <div id="sidebar" class="lg:hidden hidden" role="dialog" aria-modal="true">
                <!-- Background backdrop, show/hide based on slide-over state. -->
                <div class="fixed inset-0 z-50"></div>
                <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">Your Company</span>
                            <img src="{{ asset('assets/images/logo.png') }}" class="h-8 w-auto rounded-lg"  alt="logo.png"/>
                        </a>
                        <button id="closeNavbarToggle" type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                            <span class="sr-only">Close menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-3 py-6">
                                <a href="#" class="block font-medium px-2 -mx-3 rounded leading-6 text-gray-900 hover:bg-gray-50">Home</a>
                                <a href="#" class="block font-medium px-2 -mx-3 rounded leading-6 text-gray-900 hover:bg-gray-50">Pricing</a>
                                <a href="#" class="block font-medium px-2 -mx-3 rounded leading-6 text-gray-900 hover:bg-gray-50">Features</a>
                                <a href="#" class="block font-medium px-2 -mx-3 rounded leading-6 text-gray-900 hover:bg-gray-50">Contact</a>
                                <a href="#" class="block font-medium px-2 -mx-3 rounded leading-6 text-gray-900 hover:bg-gray-50">Company</a>
                            </div>
                            <div class="py-6 space-y-3">
                                @auth()
                                    <a href="{{ route('dashboard') }}" class="-mx-3 block font-semibold rounded px-3 text-base leading-7 text-gray-900 hover:bg-gray-50">Dashboard</a>
                                @endauth
                                @guest()
                                    <a href="{{ route('login') }}" class="-mx-3 block font-semibold rounded px-3 text-base leading-6 text-gray-900 hover:bg-gray-50">Login</a>
                                    <a href="{{ route('register') }}" class="-mx-3 block font-semibold rounded px-3 text-base leading-7 text-gray-900 hover:bg-gray-50">Register</a>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-28">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Instant Reach, Instant Impact with Novasms</h1>
                    <p class="mt-6 text-sm leading-8 text-gray-600">Novasms is your ultimate bulk SMS solution, designed to streamline communication and engagement. With seamless ease, reach your audience instantly, delivering personalized messages tailored to their need. Experience efficiency and impact as you connect with the masses effortlessly, one text at a time.</p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <a href="{{ route('register') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started</a>
                        <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Learn more <span aria-hidden="true">→</span></a>
                    </div>
                </div>
            </div>
            <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
        </div>
    </div>

        <section class="relative py-32">
            <div aria-hidden="true" class="absolute inset-0 top-60 grid grid-cols-2 -space-x-52 opacity-50 dark:opacity-30">
                <div class="h-60 bg-gradient-to-br from-primary to-purple-400 blur-[106px] dark:from-blue-700"></div>
                <div class="h-40 bg-gradient-to-r from-cyan-400 to-sky-300 blur-[106px] dark:to-indigo-600"></div>
            </div>
            <div class="relative mx-auto px-4 sm:px-12 xl:max-w-6xl xl:px-0">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white md:text-4xl xl:text-5xl">Elevate Your Bulk SMS Experience</h2>
                    <p class="mx-auto mt-6 text-sm text-gray-700 dark:text-gray-300 md:w-3/4 lg:w-3/5">Explore a comprehensive suite of features tailored for efficient and personalized bulk SMS management. Enhance communication effortlessly.</p>
                </div>
                <div class="mt-16 grid gap-8 sm:mx-auto sm:w-2/3 md:w-full md:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-3xl border border-gray-100 bg-transparent p-8 py-12 shadow-2xl shadow-gray-600/10 sm:p-12">
                        <div class="space-y-12 text-center">
                            <svg class="mx-auto h-14 w-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5h3m-6.75 2.25h10.5a2.25 2.25 0 002.25-2.25v-15a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 4.5v15a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <div class="space-y-6">
                                <h3 class="text-2xl font-semibold text-gray-800 transition">SMS Shortcode</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">Shortcodes are concise, easy-to-remember numeric or alphanumeric sequences used for streamlined communication. In SMS, they play a vital role, providing shortcuts for engaging and interactive interactions.</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-gray-100 bg-gradient-to-l from-white p-8 py-12 shadow-2xl shadow-gray-600/10 sm:p-12">
                        <div class="space-y-12 text-center">
                            <svg class="mx-auto h-14 w-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>

                            <div class="space-y-6">
                                <h3 class="text-2xl font-semibold text-gray-800 transition">Sender ID</h3>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">SMS Sender ID is a powerful tool to boost business engagement. By displaying a recognizable sender, you reinforce brand presence and credibility. When customers receive messages from your brand, they are more likely to trust the content and take desired actions.</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-3xl border border-gray-100 bg-white p-8 py-12 shadow-2xl shadow-gray-600/10 sm:p-12">
                        <div class="space-y-12 text-center">
                            <svg class="mx-auto h-14 w-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <div class="space-y-6">
                                <h3 class="text-2xl font-semibold text-gray-800 transition">Personalised bulk sms</h3>
                                <p class="text-gray-600 text-sm">Reach your audience on a personal level with our personalised bulk SMS service. Craft individualized messages that resonate, fostering stronger connections. Personalisation enhances engagement and response rates, ensuring your messages are not just read, but acted upon.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="border-t border-gray-100 pt-32 pb-8 dark:border-gray-800">
            <div>
                <div class="m-auto space-y-8 px-4 text-gray-600 dark:text-gray-400 sm:px-12 xl:max-w-6xl xl:px-0">
                    <div class="grid grid-cols-8 gap-6 md:gap-0">
                        <div class="col-span-8 md:col-span-2 lg:col-span-3">
                            <div class="flex h-full items-center justify-between gap-6 border-b border-white py-6 dark:border-gray-800 md:flex-col md:items-start md:justify-between md:space-y-6 md:border-none md:py-0">
                                <div>
                                    <a aria-label='ampire logo' class='flex items-center' href='index.html'>
                                        <ApplicationLogoMini class="rounded-lg shadow shadow-lg shadow-purple-500" />
                                    </a>
                                    <a href="" class="mt-2 inline-block text-sm">Designed by cfe </a>
                                </div>

                                <div class="flex gap-6">
                                    <a href="index.html#" target="blank" aria-label="github" class="hover:text-primary dark:hover:text-primaryLight">
                                        <span class="sr-only">Github</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                                            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                        </svg>
                                    </a>
                                    <a href="index.html#" target="blank" aria-label="twitter" class="hover:text-primary dark:hover:text-primaryLight">
                                        <span class="sr-only">Twitter</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-8 md:col-span-6 lg:col-span-5">
                            <div class="grid grid-cols-2 gap-6 pb-16 sm:grid-cols-3 md:pl-16">
                                <div>
                                    <h2 class="text-base font-medium text-gray-800 dark:text-gray-200">Company</h2>
                                    <ul class="mt-4 list-inside space-y-4">
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">About</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Customers</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Enterprise</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Partners</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Jobs</a>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h2 class="text-base font-medium text-gray-800 dark:text-gray-200">Products</h2>
                                    <ul class="mt-4 list-inside space-y-4">
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">About</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Customers</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Enterprise</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Partners</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Jobs</a>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <h2 class="text-base font-medium text-gray-800 dark:text-gray-200">Ressources</h2>
                                    <ul class="mt-4 list-inside space-y-4">
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">About</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Customers</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Enterprise</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Partners</a>
                                        </li>
                                        <li>
                                            <a href="index.html#" class="text-sm duration-100 hover:text-primary dark:hover:text-white">Jobs</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex justify-between text-sm md:pl-16">
                                <span>&copy; {{ config('app.name') }} {{ now()->year }} - Present</span>
                                <span>All right reserved</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script>
        const toggle = document.querySelector('#toggleSidebarButton')
        const closeNavbarToggle = document.querySelector('#closeNavbarToggle')
        const sidebar = document.querySelector('#sidebar')

        toggle.addEventListener('click' ,toggleSidebar)
        closeNavbarToggle.addEventListener('click' ,toggleSidebar)

        function toggleSidebar() {
            sidebar.classList.toggle('hidden')
        }
    </script>
    </body>
</html>
