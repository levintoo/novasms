<script setup>
import {Head, Link} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
    contacts: {
        type: Object
    }
})
</script>

<template>
    <Head title="Contacts" />

        <AppLayout >

            <template #header>
                <div class="grid grid-cols-2">
                    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        Contacts
                    </h2>

                    <span class="flex align-center justify-end">
                        <Link class="flex justify-between my-6 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                              :href="route('contact.create')">
                            <span aria-hidden="true" class="mr-2">+</span>
                            <span>Add new</span>
                        </Link>
                    </span>
                </div>
            </template>


                <section class="container mx-auto overflow-y-auto">
                    <div class="w-full mb-8 rounded-md shadow-md">
                        <div class="w-full">
                            <table class="w-full">
                                <thead>
                                <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                                    <th class="px-4 py-3">First Name</th>
                                    <th class="px-4 py-3">Last Name</th>
                                    <th class="px-4 py-3">Phone</th>
                                    <th class="px-4 py-3">Created</th>
                                    <th colspan="3" class="px-4 py-3 text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody v-if="contacts.data.length > 0" class="bg-white">
                                    <tr v-for="contact in contacts.data" class="text-gray-700">
                                        <td class="px-4 py-3 text-ms border font-medium">{{ contact.first_name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm border font-medium">{{ contact.last_name ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm border font-mono">{{ contact.phone ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm border font-medium">{{ contact.created ?? '-' }}</td>
                                        <td class="pl-5 border">
                                            <button class="py-3 px-3 text-sm focus:outline-none leading-none text-red-700 bg-red-100 rounded">Remove</button>
                                        </td>
                                        <td class="pl-4 border">
                                            <button class="focus:ring-2 focus:ring-offset-2 focus:ring-red-300 text-sm leading-none text-gray-600 py-3 px-5 bg-gray-100 rounded hover:bg-gray-200 focus:outline-none">Edit</button>
                                        </td>
                                    </tr>

                                </tbody>
                                <tbody v-else>
                                    <tr class="text-gray-700">
                                        <td colspan="5" class="px-4 py-3 text-ms text-gray-500 text-center">There is nothing to show here</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                            <Pagination :links="contacts.links" class="mb-6" />
                </section>

        </AppLayout>
</template>
