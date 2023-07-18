<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    method: {
        type: String,
    },
    as: {
        type: String,
    },
});
</script>

<template>
    <li class="flex">
    <Link
        :href="href"
        :method="method"
        :as="as"
        class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
    >
        <slot />
    </Link>
    </li>
</template>
