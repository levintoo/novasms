<script setup>
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

const page = usePage()

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    user: {
        type: Object,
        default: {},
    },
})
</script>

<template>

    <Head :title="user.name ?? 'User'" />

    <div>
        <div class="grid grid-cols-2">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                User - {{ user.name ?? 'User' }}
            </h2>

            <span class="flex align-center justify-end">
                <Link href="/admin/user">
                     <PrimaryButton class="flex justify-between my-6">
                         <svg class="w-5 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M15 10a.75.75 0 01-.75.75H7.612l2.158 1.96a.75.75 0 11-1.04 1.08l-3.5-3.25a.75.75 0 010-1.08l3.5-3.25a.75.75 0 111.04 1.08L7.612 9.25h6.638A.75.75 0 0115 10z" fill-rule="evenodd"/>
                            </svg>
                            <span class="mx-1">Back</span>
                     </PrimaryButton>
                </Link>
            </span>
        </div>
    </div>

    <div class="p-4 sm:p-6 bg-white sm:rounded-md shadow-sm">
        <section class="bg-white">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
            </header>

            <form class="mt-6 space-y-6">
                <div v-for="key in Object.keys(user)">
                    <InputLabel class="uppercase" for="name" :value="key" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full bg-gray-50 border-none"
                        v-model="user[key]"
                        disabled
                    />
                </div>
            </form>
        </section>
    </div>
</template>
