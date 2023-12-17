<script setup>
import {Head, useForm, usePage, Link} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import toast from "@/Stores/Toast.js";
import {defineOptions} from "vue";

const page = usePage()

defineOptions({
    layout: AppLayout,
})

defineProps({
    groups: {
        type: Object,
        default: {},
    }
})

const form = useForm({
    first_name: '',
    last_name: '',
    phone: '',
    group: '',
})

const handleCreateContact = () => {
    form.post('/contact/create', {
        preserveScroll: true,
        onError: () => {
            toast.add({
                type: 'error',
                message: 'something went wrong',
                duration: 5000
            })
        },
    })
}
</script>

<template>
    <Head title="Create Contact" />

        <div>
            <div class="grid grid-cols-2">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Create Contact
                </h2>

                <div class="flex align-center justify-end">
                    <Link href="/contact">
                        <PrimaryButton class="flex justify-between my-6">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M15 10a.75.75 0 01-.75.75H7.612l2.158 1.96a.75.75 0 11-1.04 1.08l-3.5-3.25a.75.75 0 010-1.08l3.5-3.25a.75.75 0 111.04 1.08L7.612 9.25h6.638A.75.75 0 0115 10z" fill-rule="evenodd"/>
                            </svg>
                            <span class="mx-1">Back</span>
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md p-6">

            <form @submit.prevent="handleCreateContact()">

                <div>
                    <InputLabel for="first_name" value="First name" />

                    <TextInput
                        id="first_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.first_name"
                        autocomplete="first_name"
                    />

                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="last_name" value="Last name" />

                    <TextInput
                        id="last_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.last_name"
                        autocomplete="last_name"
                    />

                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>

                <div class="mt-4">
                    <InputLabel for="phone" value="Phone" />

                    <TextInput
                        id="phone"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone"
                        autocomplete="phone"
                    />

                    <InputError class="mt-2" :message="form.errors.phone" />
                </div>

                <div class="mt-4">
                    <InputLabel for="group" value="Group" />

                    <SelectInput
                        id="group"
                        :class="form.group === '' ? 'text-gray-500' : '' "
                        class="mt-1 block w-full"
                        v-model="form.group"
                        autocomplete="group"
                    >
                        <option value=''>Select</option>
                        <option v-for="group in groups" :value=group.id>{{ group.name }}</option>
                    </SelectInput>

                    <InputError class="mt-2" :message="form.errors.group" />
                </div>

                <div class="mt-4">
                    <PrimaryButton type="submit">save</PrimaryButton>
                </div>

            </form>
        </div>
</template>
