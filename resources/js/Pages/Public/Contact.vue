<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {ref} from "vue";
import ApplicationLogoMini from "@/Components/ApplicationLogoMini.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const is_sidebar_open = ref(false);
</script>

<template>
    <Head title="Welcome" />

    <div class="bg-white">
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-6 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <ApplicationLogoMini class="rounded-lg shadow-lg shadow-purple-500"/>
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button @click="is_sidebar_open = !is_sidebar_open" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <Link href="/" class="text-sm font-semibold leading-6 text-gray-900">Home</Link>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Pricing</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Features</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Contact</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Company</a>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <div v-if="canLogin">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Dashboard</Link
                        >

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                            >Log in</Link
                            >

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                            >Register</Link
                            >
                        </template>
                    </div>
                </div>
            </nav>
            <!-- Mobile menu, show/hide based on menu open state. -->
            <div v-if="is_sidebar_open" class="lg:hidden" role="dialog" aria-modal="true">
                <!-- Background backdrop, show/hide based on slide-over state. -->
                <div class="fixed inset-0 z-50"></div>
                <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">Your Company</span>
                            <ApplicationLogoMini class="h-8 w-auto shadow rounded-lg shadow-purple-500 shadow-lg"/>
                        </a>
                        <button @click="is_sidebar_open = !is_sidebar_open" type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                            <span class="sr-only">Close menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-6 flow-root">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="space-y-2 py-6">
                                <a href="#" class="text-sm block  font-semibold leading-6 text-gray-900">Home</a>
                                <a href="#" class="text-sm  block font-semibold leading-6 text-gray-900">Pricing</a>
                                <a href="#" class="text-sm block  font-semibold leading-6 text-gray-900">Features</a>
                                <a href="#" class="text-sm block  font-semibold leading-6 text-gray-900">Contact</a>
                                <a href="#" class="text-sm block  font-semibold leading-6 text-gray-900">Company</a>
                            </div>
                            <div class="py-6">
                                <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">Log in</a>
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

            <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
        </div>
    </div>


    <section class="relative py-16">

                <div class="container mx-auto my-4 px-4 lg:px-20">

                    <div class="w-full p-8 my-4 md:px-12 lg:w-9/12 lg:pl-20 lg:pr-40 mr-auto rounded-2xl">
                        <div class="flex">
                            <h1 class="font-bold uppercase text-4xl">Send us a <br /> message</h1>
                        </div>
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 mt-5">
                            <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
                                   type="text" placeholder="First Name*" />
                            <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
                                   type="text" placeholder="Last Name*" />
                            <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
                                   type="email" placeholder="Email*" />
                            <input class="w-full bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"
                                   type="number" placeholder="Phone*" />
                        </div>
                        <div class="my-4">
                            <textarea placeholder="Message*" class="w-full h-32 bg-gray-100 text-gray-900 mt-2 p-3 rounded-lg focus:outline-none focus:shadow-outline"></textarea>
                        </div>
                        <div class="my-2 w-1/2 lg:w-1/4">
                            <button class="uppercase text-sm font-bold tracking-wide bg-purple-600 text-gray-100 p-3 rounded-lg w-full
                      focus:outline-none focus:shadow-outline">
                                Send Message
                            </button>
                        </div>
                    </div>

                    <div
                        class="w-full lg:-mt-96 lg:w-2/6 px-8 py-12 ml-auto bg-purple-800 rounded-2xl">
                        <div class="flex flex-col text-white">
                            <h1 class="font-bold uppercase text-3xl my-4">Drop in our office</h1>
                            <p class="text-white px-1">
                                open sun-friday 8am-5pm
                            </p>

                            <div class="flex my-4 w-2/3 lg:w-1/2">
                                <div class="flex flex-col">
                                    <i class="fas fa-map-marker-alt pt-2 pr-2" />
                                </div>
                                <div class="flex flex-col">
                                    <p class="text-gray-400">5555 Tailwind RD, Pleasant Grove, UT 73533</p>
                                </div>
                            </div>

                            <div class="flex my-4 w-2/3 lg:w-1/2">
                                <div class="flex flex-col">
                                    <i class="fas fa-phone-alt pt-2 pr-2" />
                                </div>
                                <div class="flex flex-col">
                                    <h2 class="text-2xl">Call Us</h2>
                                    <p class="text-gray-400">Tel: xxx-xxx-xxx</p>
                                    <p class="text-gray-400">Fax: xxx-xxx-xxx</p>
                                </div>
                            </div>

                            <div class="flex my-4 w-2/3 lg:w-1/2">
                                <a href="https://www.facebook.com/ENLIGHTENEERING/" target="_blank" rel="noreferrer" class="rounded-full bg-white h-8 w-8 inline-block mx-1 text-center pt-1">
                                    <i class="fab fa-facebook-f text-blue-900" />
                                </a>
                                <a href="https://www.linkedin.com/company/enlighteneering-inc-" target="_blank" rel="noreferrer" class="rounded-full bg-white h-8 w-8 inline-block mx-1 text-center pt-1">
                                    <i class="fab fa-linkedin-in text-blue-900" />
                                </a>
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
                            <span>&copy; novasms 2023 - Present</span>
                            <span>All right reserved</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</template>
