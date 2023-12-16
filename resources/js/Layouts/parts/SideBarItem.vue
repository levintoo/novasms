<template>
    <li>
        <Link :class="active ? 'bg-gradient-to-r from-white to-teal-50 border-r-4 border-blue-600 text-blue-600' : '' " class="flex items-center justify-start w-full p-4 my-2 font-thin uppercase transition-colors duration-200 hover:text-gray-800"
              :href="href">
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
