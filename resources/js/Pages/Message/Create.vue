<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import toast from "@/Stores/Toast.js";
import TextareaInput from "@/Components/TextareaInput.vue";

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
    phone: "",
    group: "",
    message: "",
    recipient: "contact",
})

const handleSendSMS = () => {
    form.post('/message/send', {
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            toast.add({
                message: 'Something went wrong',
                type: 'error',
                duration: 5000
            })
        },
    })
}

const addFieldToMessage = (field) => {
    const curPos = document.getElementById("message").selectionStart;
    form.message = form.message.slice(0, curPos) + field + form.message.slice(curPos);
}

</script>

<template>
    <Head title="Compose Message" />

    <div>
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Compose Message
        </h2>
    </div>

    <div class="bg-white sm:rounded-md p-6">

        <form @submit.prevent="handleSendSMS()">

            <div>
                <InputLabel for="recipient">Recipient</InputLabel>

                <div class="grid grid-cols-2 gap-4 mt-1">
                    <div class="flex items-center ps-4 border border-gray-200 rounded-lg">
                        <input v-model="form.recipient" :checked="form.recipient === 'group' " id="bordered-radio-1" type="radio" value="group" name="bordered-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-1" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Group</label>
                    </div>
                    <div class="flex items-center ps-4 border border-gray-200 rounded-lg">
                        <input v-model="form.recipient" :checked="form.recipient === 'contact' " id="bordered-radio-2" type="radio" value="contact" name="bordered-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="bordered-radio-2" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Contact</label>
                    </div>
                </div>
                <InputError class="mt-2" :message="form.errors.recipient" />
            </div>

            <div class="mt-4" v-if="form.recipient === 'group' ">
                <InputLabel for="group">Group</InputLabel>

                <select
                    id="group"
                        v-model="form.group"
                        :class="form.group === ''? 'text-gray-500' : '' "
                        class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                >
                    <option value="">select</option>
                    <option v-for="group in groups" :value="group.id">{{ group.name.slice(0,100) }} ({{ group.contacts }})</option>
                </select>

                <InputError class="mt-2" :message="form.errors.group" />
            </div>

            <div v-else class="mt-4">
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
                <InputLabel for="message">Message</InputLabel>
                <TextareaInput rows="3" class="mt-1 block w-full" id="message" v-model="form.message"></TextareaInput>
                <InputError class="mt-2" :message="form.errors.message" />
            </div>

            <div v-if="form.recipient === 'group' " class="mt-4 space-y-4 sm:space-y-0 sm:space-x-4 flex flex-col sm:flex-row">
                <SecondaryButton type="button" @click="addFieldToMessage('{{ first_name }} ')">First Name</SecondaryButton>
                <SecondaryButton type="button" @click="addFieldToMessage('{{ last_name }} ')">Last Name</SecondaryButton>
                <SecondaryButton type="button" @click="addFieldToMessage('{{ phone }} ')">Phone</SecondaryButton>
            </div>

            <div class="mt-4">
                <PrimaryButton type="submit">send</PrimaryButton>
            </div>

        </form>

    </div>

</template>
