<template>
    <th class="px-4 py-3">
        <div class="flex justify-between">
            <span>
                {{ field }}
            </span>
            <span v-if="params" class="ml-2" >
                <svg v-if="params && params.field === sort_field && params.direction === 'desc'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
                <svg v-if="params && params.field === sort_field && params.direction === 'asc'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                </svg>
            </span>
        </div>
    </th>
</template>
<script setup>
import {computed} from "vue";

const props = defineProps({
    params: Object,
    field: {
        type: String,
        required: true,
    },
    sort: {
        type: String,
    }
})
const sort_field = computed(() => props.sort ?? props.field)
</script>
