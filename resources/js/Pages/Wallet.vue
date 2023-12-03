<script setup>
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import {computed} from "vue";
import toast from "@/Stores/Toast.js";

const page = usePage()

defineOptions({
    layout: AppLayout
})

defineProps({
    balance: {
        type: Number,
        required: true,
    }
})

const form = useForm({
    phone: "",
    amount: "",
})

const sms = computed(() => ((form.amount / page.props.app.config.sms_rate ?? 1).toFixed(1)).toLocaleString() + " sms" ) // 0.8 is the sms rate

const handle_submit = () => {
    form.post(route('wallet.top_up'),{
        preserveScroll: true,
        onError: () => {
            toast.add({
                message: 'something went wrong',
                type: 'error',
                duration: 5000
            })
        },
        onSuccess: () => {
            form.reset()
        },
    })
}

</script>

<template>
    <Head title="Wallet" />

    <div>
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Wallet
        </h2>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card -->
        <div
            class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800"
        >
            <div
                class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        clip-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                        fill-rule="evenodd"
                    ></path>
                </svg>
            </div>
            <div>
                <p
                    class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400"
                >
                    Credit balance
                </p>
                <p
                    class="text-lg font-semibold text-gray-700 dark:text-gray-200"
                >
                    {{ balance ?? '-' }} sms
                </p>
            </div>
        </div>
    </div>

    <h2
        class="mb-6 text-xl font-semibold text-gray-700 dark:text-gray-200"
    >
        Top up
    </h2>
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div class="min-w-0 p-6 bg-white rounded-lg shadow-xs">
            <form @submit.prevent="handle_submit()" class="w-full space-y-3">
                <div>
                    <InputLabel class="my-2" for="amount" value="Amount(ksh)" />
                    <TextInput placeholder="amount here..." v-model="form.amount" class="w-full h-10" id="amount" type="number"/>
                    <InputLabel class="my-2 text-purple-500" for="amount" :value=sms />
                </div>
                <PrimaryButton class="h-8" type="submit">Buy</PrimaryButton>
            </form>
        </div>
    </div>

</template>
