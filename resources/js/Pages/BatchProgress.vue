<script setup>
import {Head, router} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {ref} from "vue";
const props = defineProps({
    batchinfo: {
        type: Object,
        required: true,
    },
})
const is_failed = ref(props.batchinfo.finished && props.batchinfo.progress < 100)

setInterval(() => {
    if(props.batchinfo.finished) return
    router.post(route('batch.progress',props.batchinfo.id))
},2000)
</script>

<template>
    <Head title="Processing" />

    <AppLayout>

        <template #header>
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">

            </h2>
        </template>

        <div class="bg-white p-10 rounded-lg shadow-md">
            <h1 class="text-xl font-bold">
                {{ !batchinfo.finished ? 'Processing...' : 'Finished processing' }} <span v-if="is_failed">(failed)</span>
            </h1>
            <div class="mt-4 mb-10">
                <p class="text-gray-600"> {{ batchinfo.progress }}% completed</p>
                <div class="bg-gray-400 w-100 h-3 rounded-lg mt-2 overflow-hidden">
                    <div :style="`width: `+batchinfo.progress+`%`" class="bg-pink-400 h-full rounded-lg shadow-md"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
