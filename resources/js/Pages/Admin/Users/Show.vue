<script setup>
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import toast from "@/Stores/Toast.js";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import {defineOptions} from "vue";

const page = usePage()

defineOptions({
    layout: AppLayout,
})

defineProps({
    user: {
        type: Object,
        default: {},
    }
})
</script>

<template>
    <Head :title="user.name ?? '-'" />

        <div>
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                {{ user.name ?? 'User' }}
            </h2>
        </div>

    <pre>{{ user }}</pre>
</template>
