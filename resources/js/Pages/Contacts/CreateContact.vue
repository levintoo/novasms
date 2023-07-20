<script setup>
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import toast from "@/Stores/Toast.js";

defineProps({
    groups: {
        type: Object,
        default: {},
    }
})

const page = usePage()
const form = useForm({
    first_name: '',
    last_name: '',
    phone: '',
    group: '',
})
const handleCreateContact = () => {
    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            toast.add({
                message: page.props.toast,
                duration: 5000
            })
        },
    })
}
</script>

<template>
    <Head title="Contacts" />

    <AppLayout >

        <template #header>
            <div class="grid grid-cols-2">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Create Contact
                </h2>

                <span class="flex align-center justify-end">
                        <Link class="flex justify-between my-6 px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                              :href="route('contacts')">
                            <span aria-hidden="true" class="mr-2">&backsim;</span>
                            <span>Back</span>
                        </Link>
                    </span>
            </div>
        </template>

        <div class="bg-white rounded-md p-6">

            <form @submit.prevent="handleCreateContact()">


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
                </div>

                <div class="mt-4">
                    <PrimaryButton type="submit">save</PrimaryButton>
                </div>

            </form>
        </div>


    </AppLayout>
</template>
