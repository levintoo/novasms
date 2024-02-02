<script setup>
import {computed, ref, watch} from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import SideBar from "./parts/SideBar.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {usePage} from "@inertiajs/vue3";
import ToastList from "@/Components/ToastList.vue";
import Search from "@/Layouts/parts/Search.vue";

const isSideMenuOpen = ref(false)

const page = usePage()

const currentUrl = computed(() => page.url )

watch(currentUrl,(value, oldValue) => {
    if(value !== oldValue) {
        isSideMenuOpen.value = false
    }
} )

</script>

<template>

    <ToastList />

    <div
        class="flex"
    >
        <!-- Desktop sidebar -->
        <aside
            class="z-20 hidden lg:w-64 bg-white md:block flex-shrink-0"
        >
            <div class="py-4 text-gray-500">
                <SideBar>
                    <template #head>
                         <div class="flex justify-center mx-6">
                                <a
                                    class=" text-lg font-bold text-gray-800"
                                    href="/"
                                >
                                    <ApplicationLogo/>
                                </a>
                            </div>
                    </template>
                </SideBar>
            </div>
        </aside>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        <div
            v-if="isSideMenuOpen"
            class="fixed inset-0 z-10 flex md:hidden items-end bg-black bg-opacity-50 md:items-center md:justify-center"
            @click="isSideMenuOpen = !isSideMenuOpen"></div>

        <aside
            v-if="isSideMenuOpen"
            class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white md:hidden"
        >
            <div class="py-4 text-gray-500">
                <SideBar />
            </div>
        </aside>

        <div class="flex flex-col flex-1 w-full">
            <header class="z-10 py-4 bg-white">
                <div
                    class="container flex items-center justify-between h-full px-6 mx-auto text-blue-600">
                    <!-- Mobile hamburger -->
                    <button
                            class="p-1 mr-3 -ml-1 md:hidden w-10 h-10 rounded-xl md:border md:bg-gray-100 focus:bg-gray-100 active:bg-gray-200"
                    @click="isSideMenuOpen = !isSideMenuOpen"
                    >
                        <svg
                            v-if="!isSideMenuOpen"
                            aria-hidden="true"
                            class="w-6 h-6 m-auto text-gray-600"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                fill-rule="evenodd"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            class="w-6 h-6 m-auto text-gray-600"
                            fill="currentColor"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <!-- Search input -->
                    <div class="hidden md:block">
                        <Search />
                    </div>

                    <!-- Mobile Logo -->
                    <div class="block md:hidden">
                        <ApplicationLogo class="h-10" />
                    </div>

                    <ul class="items-center flex-shrink-0 space-x-6 flex ml-3">
                        <!-- Notifications menu -->
                        <li>
                            <button aria-label="notification"
                                    class="relative w-10 h-10 rounded-xl sm:border sm:bg-gray-100 focus:bg-gray-100 active:bg-gray-200">
                                <svg class="h-5 w-5 m-auto text-gray-600" fill="currentColor"
                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                </svg>
                                <!-- Notification badge -->
                                <span
                                    aria-hidden="true"
                                    class="absolute top-2 md:top-0 right-3 md:right-0 inline-block w-3 h-3 transform translate-x-1 -translate-y-1 bg-red-600 border-2 border-white rounded-full"
                                ></span>
                            </button>
                        </li>

                        <!-- profile menu -->
                        <li class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                                type="button"
                                            >
                                                {{ $page.props.auth.user.name }}

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
                                        <DropdownLink href="/profile"> Profile</DropdownLink>
                                        <DropdownLink as="button" href="/logout" method="post">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </li>

                    </ul>
                </div>
            </header>

            <main class="min-h-screen">
                <div class="container px-2 sm:px-6 mx-auto grid">
                    <slot/>
                </div>
            </main>

        </div>

    </div>
</template>

