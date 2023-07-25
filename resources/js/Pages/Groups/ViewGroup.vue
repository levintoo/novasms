<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import IconLink from "@/Components/IconLink.vue";
import Pagination from "@/Components/Pagination.vue";

const page = usePage()
defineProps({
    group: {
        type: Object,
        required: true,
    },
    contacts: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <Head :title=group.name />

    <AppLayout >

        <template #header>
            <div class="grid grid-cols-2">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    {{ group.name }}
                </h2>

                <span class="flex align-center justify-end">
                        <PrimaryLink class="flex justify-between my-6"
                                     :href="route('groups')">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M15 10a.75.75 0 01-.75.75H7.612l2.158 1.96a.75.75 0 11-1.04 1.08l-3.5-3.25a.75.75 0 010-1.08l3.5-3.25a.75.75 0 111.04 1.08L7.612 9.25h6.638A.75.75 0 0115 10z" fill-rule="evenodd"/>
                            </svg>
                            <span class="mx-1">Back</span>
                        </PrimaryLink>
                    </span>
            </div>
        </template>

        <div class="bg-white rounded-md p-6 my-6">
            <p>name: {{ group.name }}</p>
            <p>desc: {{ group.description }}</p>
            <p>created: {{ group.created }}</p>
            <p>last updated: {{ group.updated }}</p>
            <pre>{{ group }}</pre>
            <pre>{{ contacts }}</pre>
        </div>

        <section class="container mx-auto overflow-y-auto">
            <div class="w-full mb-8 rounded-md shadow-md overflow-y-auto">
                <table class="w-full overflow-y-auto">
                    <thead>
                    <tr class="text-sm font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                        <th class="px-4 py-3">First Name</th>
                        <th class="px-4 py-3">Last Name</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Group</th>
                        <th class="px-4 py-3">Created</th>
                        <th class="px-4 py-3 text-center" colspan="2">Actions</th>
                    </tr>
                    </thead>
                    <tbody v-if="contacts.data.length > 0" class="bg-white">
                    <tr v-for="contact in contacts.data" class="text-gray-700">
                        <td class="px-4 py-3 text-sm border font-medium">{{ contact.first_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm border font-medium">{{ contact.last_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm border font-mono">{{ contact.phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm border font-medium">{{ contact.group ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm border font-medium">{{ contact.created ?? '-' }}</td>
                        <td class="pl-4 border">
                            <IconLink :href="route('contact.edit', contact.id)"
                                      class="text-gray-500 focus:ring-gray-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                    <path
                                        d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                                </svg>
                            </IconLink>
                        </td>
                    </tr>

                    </tbody>
                    <tbody v-else>
                    <tr class="text-gray-700">
                        <td class="px-4 py-3 text-ms border font-medium text-center" colspan="6">
                            There is nothing to show
                            here
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <Pagination :links="contacts.links" class="mb-6"/>
        </section>

    </AppLayout>
</template>
