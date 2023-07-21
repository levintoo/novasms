<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
})

</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap mt-8">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="text-gray-500 mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-primary focus:text-primary"
                    v-html="link.label"
                />

                <Link
                    preserve-state
                    preserve-scroll
                    v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-primary focus:text-primary"
                    :class="{ 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150': link.active }"
                    :href="link.url"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

