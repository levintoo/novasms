<template>
    <li class="relative px-6 py-3">
        <span v-if="active" aria-hidden="true"
              class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"></span>
        <Link
            :class="active ? 'text-gray-800' : 'text-gray-500' "
            class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
            :href="href"
        >
            <slot />
        </Link>
    </li>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    active: {
        type: Boolean,
    },
    href: {
        type: String,
        required: true,
    }
})

</script>
