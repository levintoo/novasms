<script setup>
import SideBarDropdown from "@/Components/SideBarDropdown.vue";
import SideBarDropdownLink from "@/Components/SideBarDropdownLink.vue";
import SideBarItem from "@/Components/SideBarItem.vue";
import {computed, ref} from "vue";
import {Link, usePage} from "@inertiajs/vue3"

const page = usePage()

const state = ref('dashboard')

const isadmin = computed(() => page.props.auth.roles.includes('admin') || page.props.auth.roles.includes('super admin') )
</script>

<template>

    <slot name="head"/>

    <ul class="mt-6">
        <SideBarItem :href="route('dashboard')" :active="route().current('dashboard')">
              <svg
                  aria-hidden="true"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  viewBox="0 0 24 24"
              >
                  <path
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                  ></path>
              </svg>
            <span class="ml-4">
                Dashboard
            </span>
        </SideBarItem>
    </ul>
    <ul>
        <input type="hidden" v-model="state" class="border" />
        <div class="mt-2 hidden">{{ $page.component === state }}</div>
        <div class="mt-2 hidden">{{ $page.url.startsWith(state) }}</div>
        <div class="mt-2 hidden">{{ route(state) }}</div>
        <SideBarItem :href="route('send-sms')" :active="route().current('send-sms')">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path d="M16.1 260.2c-22.6 12.9-20.5 47.3 3.6 57.3L160 376V479.3c0 18.1 14.6 32.7 32.7 32.7c9.7 0 18.9-4.3 25.1-11.8l62-74.3 123.9 51.6c18.9 7.9 40.8-4.5 43.9-24.7l64-416c1.9-12.1-3.4-24.3-13.5-31.2s-23.3-7.5-34-1.4l-448 256zm52.1 25.5L409.7 90.6 190.1 336l1.2 1L68.2 285.7zM403.3 425.4L236.7 355.9 450.8 116.6 403.3 425.4z"/>
            </svg>
            <span class="ml-4">
                Send SMS
            </span>
        </SideBarItem>
        <SideBarItem :href="route('messages')" :active="route().current('messages')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>

            <span class="ml-4">
                Messages
            </span>
        </SideBarItem>
        <SideBarItem :href="route('contacts')" :active="route().current('contacts') || route().current('contact.create') || route().current('contacts.create')"  >
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M384 48c8.8 0 16 7.2 16 16V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16H384zM96 0C60.7 0 32 28.7 32 64V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H96zM240 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zm-32 32c-44.2 0-80 35.8-80 80c0 8.8 7.2 16 16 16H336c8.8 0 16-7.2 16-16c0-44.2-35.8-80-80-80H208zM512 80c0-8.8-7.2-16-16-16s-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V80zM496 192c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm16 144c0-8.8-7.2-16-16-16s-16 7.2-16 16v64c0 8.8 7.2 16 16 16s16-7.2 16-16V336z"/></svg>
            <span class="ml-4">
                Contacts
            </span>
        </SideBarItem>
        <SideBarItem :href="route('groups')" :active="route().current('groups')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>

            <span class="ml-4">
                Groups
            </span>
        </SideBarItem>
        <SideBarDropdown v-if="isadmin">
            <template #toggler>
                <svg
                    aria-hidden="true"
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                    ></path>
                </svg>
                <span class="ml-4">Admin</span>
            </template>
            <template #links>
                <SideBarDropdownLink :href="route('admin.dashboard')" >
                    Dashboard
                </SideBarDropdownLink>
                <SideBarDropdownLink :href="route('admin.users')" >
                    Users
                </SideBarDropdownLink>
                <SideBarDropdownLink href="#" >
                    Roles
                </SideBarDropdownLink>
                <SideBarDropdownLink href="#" >
                    Groups
                </SideBarDropdownLink>
                <SideBarDropdownLink href="#" >
                    Messages
                </SideBarDropdownLink>
                <SideBarDropdownLink href="#" >
                    Jobs
                </SideBarDropdownLink>
            </template>
        </SideBarDropdown>
    </ul>

    <div class="px-6 my-6">
        <Link  :href="route('logout')" as="button" method="POST"
            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
        >
            <svg
                aria-hidden="true"
                class="w-4 h-4 mr-2"
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
            Sign out
        </Link>
    </div>
</template>
