<template>
    <li
        class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
    >
        <Link class="w-full" :href="href">
            <slot />
        </Link>
    </li>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    href: {
        type: String,
        required: true,
    },
})
</script>
