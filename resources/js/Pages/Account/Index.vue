<script setup>
import {Head, router, useForm} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions, ref, watch} from "vue";
import TableData from "@/Components/TableData.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import TextInput from "@/Components/TextInput.vue";
import TableHead from "@/Components/TableHead.vue";
import TableBody from "@/Components/TableBody.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {debounce, omitBy} from "lodash";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    account: {
        type: Object,
        required: true,
    },
    transactions: {
        type: Object,
        required: true,
        default: {},
    },
    filters: {
        type: Object,
        default: {},
    },
})

const form = useForm({
    amount: null,
})

const params = ref({
    search: props.filters.search ?? "",
    field: props.filters.field ?? "",
    direction: props.filters.direction ?? "",
})

const sort = (field) => {
    params.value.field = field
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc'
}

watch(params.value, debounce((params) => {
    router.get('/account', { ...omitBy(params, v => v === "") }, { preserveScroll: true, replace: true, preserveState: true },)
},150));
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
                    {{ account.balance.toLocaleString() ?? '-' }} sms
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
                    <InputLabel for="amount" value="Amount (Ksh)" />

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
                        <TableHeadItem
                            sort="amount"
                            field="Amount (Ksh)"
                            class="cursor-pointer"
                            :params=params
                            @click="sort('amount')"  />
                        <TableHeadItem
                            sort="status"
                            field="Status"
                            class="cursor-pointer"
                            :params=params
                            @click="sort('status')"  />
                        <TableHeadItem
                            sort="transacted_at"
                            field="transacted_at"
                            class="cursor-pointer"
                            :params=params
                            @click="sort('transacted_at')"  />
                    </TableHead>
                </template>
                <template #tbody>
                    <TableBody v-if="transactions.data.length > 0">
                        <TableBodyItem v-for="transaction in transactions.data">
                            <TableData>
                                {{ transaction.amount ?? '-' }}
                            </TableData>
                            <TableData>
                                <span v-if="transaction.status === 'completed' "
                                      class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ transaction.status ?? '-' }}
                                </span>
                                <span v-else-if="transaction.status === 'cancelled'"
                                      class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ transaction.status ?? '-' }}
                                </span>
                                <span v-else
                                      class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                    {{ transaction.status ?? '-' }}
                                </span>
                            </TableData>
                            <TableData>
                                {{ transaction.transacted_at ?? '-' }}
                            </TableData>
                        </TableBodyItem>
                    </TableBody>

                    <TableBody v-else>
                        <TableBodyItem>
                            <TableData colspan="6" class="text-center text-gray-500">
                                There is nothing to show here
                            </TableData>
                        </TableBodyItem>
                    </TableBody>

                </template>
                <template #pagination>
                    <Pagination v-if="transactions.links" :from="transactions.from" :to="transactions.to" :total="transactions.total" :links="transactions.links" />
                </template>
            </Table>
        </section>
    </div>

</template>
