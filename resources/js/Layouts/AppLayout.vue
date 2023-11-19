<script setup>
import {ref} from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ToastList from "@/Components/ToastList.vue";
import SideBar from "@/Components/SideBar.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {Link} from "@inertiajs/vue3";

const isSideMenuOpen = ref(false)
const isNotificationDropdownOpen = ref(false)
</script>

<template>

    <ToastList />

    <div
        class="flex min-h-screen bg-gray-50 dark:bg-gray-900"
        :class="{ 'overflow-hidden': isSideMenuOpen }"
    >
        <!-- Desktop sidebar -->
        <aside
            class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0"
        >
            <div class="py-4 text-gray-500 dark:text-gray-400">
                <SideBar>
                    <template #head>
                        <Link
                            class="text-lg text-gray-800 font-bold flex ml-6"
                            href="/"
                        >
                            NOVASMS
                        </Link>
                    </template>
                </SideBar>
            </div>
        </aside>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->

        <div
            @click="isSideMenuOpen = !isSideMenuOpen"
            v-if="isSideMenuOpen"
            class="fixed inset-0 z-10 flex md:hidden items-end bg-black bg-opacity-50 md:items-center md:justify-center"></div>

        <aside
            v-if="isSideMenuOpen"
            class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white md:hidden"
        >
            <div class="py-4 text-gray-500 dark:text-gray-400">
                <SideBar>
                    <template #head>
                        <div class="flex justify-between mx-6">
                            <Link
                                class=" text-lg font-bold text-gray-800 dark:text-gray-200"
                                href="/"
                            >
                                <ApplicationLogo />
                            </Link>
                            <span
                                @click="isSideMenuOpen = !isSideMenuOpen"
                                class="cursor-pointer rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
                            >
                       <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                       </svg>
                   </span>
                        </div>
                    </template>
                </SideBar>
            </div>
        </aside>

        <div class="flex flex-col flex-1 w-full">
            <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-800">
                <div class="container flex items-center justify-between h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
                    <!-- Mobile hamburger -->
                    <a href="#"
                        class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
                        @click="isSideMenuOpen = !isSideMenuOpen"
                       >
                        <svg
                            class="w-6 h-6"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </a>
                    <!-- Search input -->
                    <div class="flex justify-center flex-1 lg:mr-32">
                        <div class="relative w-full max-w-xl mr-6 focus-within:text-purple-500">
                            <div class="absolute inset-y-0 flex items-center pl-2">
                                <svg
                                    class="w-4 h-4"
                                    aria-hidden="true"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                            </div>
                            <input
                                class="block border-gray-300 w-full text-sm focus:outline-none form-input leading-5 focus:border-purple-600 focus:shadow-outline-purple pl-8 text-gray-700"
                                type="text"
                                placeholder="Search"
                                aria-label="Search"
                            />
                        </div>
                    </div>

                    <ul class="items-center flex-shrink-0 space-x-6 flex">

                           <!-- Notifications menu -->
                           <li class="relative">
                               <button
                                   @click="isNotificationDropdownOpen = !isNotificationDropdownOpen"
                                   aria-label="Notifications"
                                   class="relative align-middle rounded-md focus:outline-none focus:shadow-outline-purple"
                                   >
                                       <svg
                                           aria-hidden="true"
                                           class="w-5 h-5"
                                           fill="currentColor"
                                           viewBox="0 0 20 20"
                                       >
                                           <path
                                               d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"
                                           ></path>
                                       </svg>
                                       <!-- Notification badge -->
                                       <span
                                           aria-hidden="true"
                                           class="absolute top-0 right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 bg-red-600 border-2 border-white rounded-full dark:border-gray-800"
                                       ></span>
                                   </button>

                               <div v-show="isNotificationDropdownOpen" @click="isNotificationDropdownOpen = !isNotificationDropdownOpen" class="fixed inset-0 h-full w-full z-10"></div>

                               <div v-show="isNotificationDropdownOpen" class="absolute right-0 mt-6 bg-white rounded-md shadow-lg overflow-hidden z-20" style="width:20rem;">
                                   <div class="py-2">
                                       <a class="flex items-center px-4 py-3 -mx-2">
                                           <p class="text-gray-600 text-sm mx-2">
                                               <span class="font-bold">System</span> new login . 5m
                                           </p>
                                       </a>
                                   </div>
                                   <a @click="isNotificationDropdownOpen = !isNotificationDropdownOpen" class="cursor-pointer block bg-gray-100 text-gray-800 text-center font-bold py-2">Close notifications</a>
                               </div>
                           </li>

                           <!-- Profile menu -->
                           <Dropdown class="hidden md:block" align="right" width="48">
                               <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                                type="button"
                                            >
                                                {{ $page.props.auth.user.name ?? '-' }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        clip-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        fill-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                               </template>

                               <template #content>
                                   <ul aria-label="submenu"
                                       class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700">
                                       <DropdownLink :href="route('profile.edit')">
                                           <svg
                                               aria-hidden="true"
                                               class="w-4 h-4 mr-3"
                                               fill="none"
                                               stroke="currentColor"
                                               stroke-linecap="round"
                                               stroke-linejoin="round"
                                               stroke-width="2"
                                               viewBox="0 0 24 24"
                                           >
                                               <path
                                                   d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                               ></path>
                                           </svg>
                                           <span>Profile</span>
                                       </DropdownLink>

                                       <DropdownLink :href="route('logout')" as="button" method="POST">
                                           <svg
                                               aria-hidden="true"
                                               class="w-4 h-4 mr-3"
                                               fill="none"
                                               stroke="currentColor"
                                               stroke-linecap="round"
                                               stroke-linejoin="round"
                                               stroke-width="2"
                                               viewBox="0 0 24 24"
                                           >
                                               <path
                                                   d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                                               ></path>
                                           </svg>
                                           <span>Log out</span>
                                       </DropdownLink>
                                   </ul>
                               </template>
                           </Dropdown>

                    </ul>
                </div>
            </header>

            <main class="min-h-screen">


                <div class="container px-6 mx-auto grid">

                    <slot />

                </div>
            </main>

        </div>

    </div>
</template>


