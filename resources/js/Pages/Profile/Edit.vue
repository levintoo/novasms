<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

defineOptions({
    layout: AppLayout,
})
</script>

<template>
    <Head title="Profile" />

    <div>
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Profile
        </h2>
    </div>
    <div class="space-y-6 mb-6">

        <div class="p-4 sm:p-6 bg-white sm:rounded-md shadow-sm">
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
                class="max-w-xl"
            />
        </div>

        <div class="p-4 sm:p-6 bg-white sm:rounded-md shadow-sm">
            <UpdatePasswordForm class="max-w-xl" />
        </div>

        <div class="p-4 sm:p-6 bg-white sm:rounded-md shadow-sm">
            <DeleteUserForm class="max-w-xl" />
        </div>

    </div>
</template>
