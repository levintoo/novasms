<script setup>
import {computed} from "vue";
import {Link, usePage} from "@inertiajs/vue3"
import SideBarItem from "@/Layouts/parts/SideBarItem.vue";
import SideBarDropdownLink from "@/Layouts/parts/SideBarDropdownLink.vue";
import SideBarDropdown from "@/Layouts/parts/SideBarDropdown.vue";

// const page = usePage()

// const isAdmin = computed(() => {
//     return page.props.auth.role === 'admin' || page.props.auth.role === 'super admin'
// })
</script>

<template>
    <div class="h-screen my-4">
        <div class="h-full bg-white rounded-2xl">

            <slot name="head"/>

            <nav class="mt-6">
                <ul>

                    <SideBarItem href="/dashboard" :active="$page.url.startsWith('/dashboard')">
                        <span class="text-left">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                class="w-5 h-5"
                            >
                                <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                                <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z" />
                            </svg>
                        </span>
                        <span class="mx-4 text-sm font-normal">
                                Home
                            </span>
                    </SideBarItem>

                    <SideBarItem href="/profile" :active="$page.url.startsWith('/profile')">
                            <span class="text-left">
                                <svg height="20" width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span class="mx-4 text-sm font-normal">
                                Profile
                            </span>
                    </SideBarItem>

                    <!-- upgrade subscription button -->
                    <li class="px-6 my-6 bottom-0">
                        <Link href="/subscription" as="button"
                              class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-4 h-4 mr-2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 12.75l7.5-7.5 7.5 7.5m-15 6l7.5-7.5 7.5 7.5" />
                            </svg>
                            UPGRADE
                        </Link>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</template>
