<template>
    <Link
        :href="href"
        :method="method"
        :as="as"
        class="inline-flex hover:bg-gray-200 items-center leading-none px-3 py-2 border border-transparent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
    >
        <slot />
    </Link>
</template>
<script setup>
import {Link} from "@inertiajs/vue3";
defineProps({
    href: {
        type: String,
    },
    method: {
        type: String,
    },
    as: {
        type: String,
    },
})
</script>
