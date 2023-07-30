<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
defineOptions({
    layout: AppLayout,
})

defineProps({
    user: {
        type: Object,
        required: true,
    },
    roles: {
        type: Object,
        required: true,
    },
    permissions: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <Head title="Manage Users" />

    <div>
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Edit User
        </h2>
    </div>

    <pre>{{ roles }}</pre>
    <pre>{{ permissions }}</pre>
    <pre>{{ user }}</pre>

</template>
