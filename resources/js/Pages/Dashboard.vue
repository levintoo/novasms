<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <div>
            <h2 class="pt-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Dashboard
            </h2>
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
