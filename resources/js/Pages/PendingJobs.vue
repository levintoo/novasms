<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    jobs: {
        type: Object,
    }
})
</script>

<template>

    <pre>{{ jobs }}</pre>

    <Head title="Pending Jobs"/>

    <div>
        <h2 class="py-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Pending Jobs
        </h2>
    </div>

</template>
