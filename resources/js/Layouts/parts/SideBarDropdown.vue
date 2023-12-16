<template>
    <li
        class="relative p-4 my-2"
        :class="active ? 'bg-gradient-to-r from-white to-teal-50 text-blue-600' : '' "
    >
        <button
            aria-haspopup="true"
            class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
            @click="isPagesMenuOpen = !isPagesMenuOpen"
        >
                <span class="inline-flex items-center" >

                    <slot name="toggler" />

                </span>
            <svg
                aria-hidden="true"
                class="w-4 h-4"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    clip-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    fill-rule="evenodd"
                ></path>
            </svg>
        </button>
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <ul v-if="isPagesMenuOpen"
                aria-label="submenu"
                class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50"
            >

                <slot name="links" />

            </ul>
        </transition>
    </li>
</template>
<script setup>
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";
const isPagesMenuOpen = ref(false)
defineProps({
    active: Boolean,
})
</script>
