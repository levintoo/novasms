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
    recipients: 'group',
    phone: "",
    group: "26",
    message: "hello {{ first_name }}",
})

const handleSendSMS = () => {
    form.post(route('send-sms.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                message: page.props.toast,
                duration: 5000
            })
        },
        onError: (e) => {
            toast.add({
                message: 'you have an error ',
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
    <Head title="Send SMS" />

        <div>
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Send SMS
            </h2>
        </div>

        <div class="bg-white rounded-md p-6">

            <div>
<!--                <h2 class="text-lg font-medium text-gray-900">Send an sms to group or to a contact</h2>-->

                <p class="mt-1 text-sm text-gray-600">
                    Set recipients to group to reveal more options
                </p>
            </div>

            <form @submit.prevent="handleSendSMS()">

                <div class="mt-4">
                    <InputLabel>Recipients</InputLabel>
                    <select v-model="form.recipients" class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm">
                        <option value="one">One</option>
                        <option value="group">Group</option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.recipients" />
                </div>

                <div class="mt-4" v-if="form.recipients === 'group' ">
                    <InputLabel>Group</InputLabel>
                    <select v-model="form.group" class="mt-1 block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm">
                        <option value="">select</option>
                        <option v-for="group in groups" :value="group.id">{{ group.name }} ({{ group.size }})</option>
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

                <div v-if="form.recipients === 'group' " class="mt-4 space-x-4">
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
