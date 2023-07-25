<script setup>
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SelectInput from "@/Components/SelectInput.vue";
import toast from "@/Stores/Toast.js";
import PrimaryLink from "@/Components/PrimaryLink.vue";

defineProps({
    groups: {
        type: Object,
        default: {},
    }
})

const page = usePage()
const form = useForm({
    file: null,
    group: "",
})
const handleUploadContacts = () => {
    form.post(route('contacts.upload'), {
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
    <Head title="Upload Contacts" />

    <AppLayout >

        <template #header>
            <div class="grid grid-cols-2">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Upload Contacts
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
        </template>

        <div class="bg-white rounded-md p-6">

            <form @submit.prevent="handleUploadContacts()">


                <div class="mt-4">
                    <InputLabel for="file" value="File" />

                    <input
                        id="file"
                        type="file"
                        class="mt-1 block w-full"
                        @input="form.file = $event.target.files[0]"
                        autocomplete="file"
                    />

                    <InputError class="mt-2" :message="form.errors.file" />
                </div>

                <div class="mt-4">
                    <InputLabel for="group" value="Add to group" />

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
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                </div>

                <div class="mt-4">
                    <PrimaryButton type="submit">save</PrimaryButton>
                </div>

            </form>
        </div>


    </AppLayout>
</template>
