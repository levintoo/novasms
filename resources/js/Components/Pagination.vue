<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
    to: Number,
    from: Number,
    total: Number,
})

</script>

<template>
    <div v-if="links.length > 3"
        class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800"
    >
                <span class="flex items-center col-span-3">
                  Showing {{ from ?? '1' }}-{{ to ?? '15' }} of {{ total ?? 'many' }}
                </span>
        <span class="col-span-2"></span>
        <!-- Pagination -->
        <span class="flex col-span-4 mt-4 sm:mt-auto sm:justify-end">
                  <nav aria-label="Table navigation">
                    <ul class="inline-flex items-center" v-for="(link, key) in links" :key="key">
                      <li class="mb-1">
                        <span
                            v-if="link.url === null"
                            class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-blue"
                        v-html="link.label">
                        </span>
                          <Link
                              preserve-state
                              preserve-scroll
                              v-else
                              class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-blue"
                              :class="{ 'px-3 py-1 text-white transition-colors duration-150 bg-blue-600 border border-r-0 border-blue-600 rounded-md focus:outline-none focus:shadow-outline-blue': link.active }"
                              :href="link.url"
                              v-html="link.label"
                          />
                      </li>
                    </ul>
                  </nav>
                </span>
    </div>
</template>

