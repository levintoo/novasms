<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
defineOptions({
    layout: AppLayout,
})
</script>

<template>
    <Head title="Admin Dashboard" />

    <div>
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Admin Dashboard
        </h2>
    </div>

</template>
