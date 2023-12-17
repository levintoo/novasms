<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import toast from "@/Stores/Toast.js";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {defineOptions} from "vue";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    groups: {
        type: Object,
        default: {},
    },
    contact: {
        type: Object,
        default: {},
    },
})

const form = useForm({
    first_name: props.contact.first_name ?? '',
    last_name: props.contact.last_name ?? '',
    phone: props.contact.phone ?? '',
    group: props.contact.group ?? '',
})

const handleUpdateContact = () => {
    form.patch(route('contact.update', props.id), {
        preserveScroll: true,
        onError: () => {
            toast.add({
                message: 'something went wrong',
                duration: 5000
            })
        },
    })
}
</script>

<template>
    <Head title="Edit Contact" />

        <div>
            <div class="grid grid-cols-2">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Edit Contact
                </h2>

                <span class="flex align-center justify-end">
                        <PrimaryLink class="flex justify-between my-6"
                                     :href="route('contacts')">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" d="M15 10a.75.75 0 01-.75.75H7.612l2.158 1.96a.75.75 0 11-1.04 1.08l-3.5-3.25a.75.75 0 010-1.08l3.5-3.25a.75.75 0 111.04 1.08L7.612 9.25h6.638A.75.75 0 0115 10z" fill-rule="evenodd"/>
                            </svg>
                            <span class="mx-1">Back</span>
                        </PrimaryLink>
                    </span>
            </div>
        </div>

        <div class="bg-white rounded-md p-6">

            <form @submit.prevent="handleUpdateContact()">


                <div class="mt-4">
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
                    <InputLabel for="group" value="Belongs to group" />

                    <SelectInput
                        id="group"
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
                    <PrimaryButton type="submit">update</PrimaryButton>
                </div>

            </form>
        </div>
</template>

