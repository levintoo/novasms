<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
import TableData from "@/Components/TableData.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import IconButton from "@/Components/IconButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TableHead from "@/Components/TableHead.vue";
import TableBody from "@/Components/TableBody.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import IconLink from "@/Components/IconLink.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import Dropdown from "@/Components/Dropdown.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    user: {
        type: Object,
    }
})

const form = useForm({
    amount: null,
})
</script>

<template>

    <Head title="Account"/>

    <div>
        <h2 class="py-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Account
        </h2>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- balance card -->
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
                    Credit Balance
                </p>
                <p
                    class="text-lg font-semibold text-gray-700 dark: text-gray-200"
                >
                    69 sms
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white sm:rounded-md shadow-sm mb-8 p-4 sm:p-6">
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">Top Up Account</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Please input the desired amount and submit to initiate payment.
                </p>
            </header>

            <form @submit.prevent="form.post('/account/pay')" class="mt-6 space-y-6">
                <div>
                    <InputLabel for="amount" value="Amount" />

                    <TextInput
                        id="amount"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.amount"
                        required
                        autofocus
                        autocomplete="amount"
                    />

                    <InputError class="mt-2" :message="form.errors.amount" />
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton>Submit</PrimaryButton>
                </div>
            </form>
        </section>
    </div>

    <div class="bg-white sm:rounded-md shadow-sm mb-8">
        <section class="space-y-6">
            <header class="pt-4 sm:pt-6 px-4 sm:px-6">
                <h2 class="text-lg font-medium text-gray-900">Account History</h2>
            </header>
            <Table>
                <template #thead>
                    <TableHead>
                        <TableHeadItem field="group" />
                    </TableHead>
                </template>
                <template #tbody>
                    <TableBody>
                        <TableBodyItem v-for="contact in [1,2,3,4,5,5]">
                            <TableData>
                                {{ contact ?? '-' }}
                            </TableData>
                        </TableBodyItem>
                    </TableBody>
                </template>

            </Table>
        </section>
    </div>

</template>
