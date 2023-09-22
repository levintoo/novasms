<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
import IconLink from "@/Components/IconLink.vue";
import TableHead from "@/Components/TableHead.vue";
import IconButton from "@/Components/IconButton.vue";
import TableBody from "@/Components/TableBody.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import Pagination from "@/Components/Pagination.vue";
import Table from "@/Components/Table.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import TableData from "@/Components/TableData.vue";

defineOptions({
    layout: AppLayout,
})

defineProps({
    transactions: {
        type: Object,
        required: true,
    }
})

const handle_reverse = () => {
    if(!confirm('Are you sure you want to reverse this transaction')) return;
}
</script>

<template>
    <Head title="Transactions" />

    <div>
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Transactions
        </h2>
    </div>

    <Table>
        <template #thead>
            <TableHead>

                <TableHeadItem field="id" />

                <TableHeadItem field="user" />

                <TableHeadItem field="amount" />

                <TableHeadItem field="type" />

                <TableHeadItem field="status" />

                <TableHeadItem field="transacted" />

                <TableHeadItem
                    class="text-center"
                    colspan="2"
                    field="Actions" />
            </TableHead>
        </template>
        <template #tbody>
            <TableBody v-if="transactions.data.length > 0">
                <TableBodyItem v-for="contact in transactions.data">
                    <TableData>
                        {{ contact.transaction_id ?? '-' }}
                    </TableData>
                    <TableData>
                        {{ contact.name ?? '-' }}
                    </TableData>
                    <TableData>
                        {{ contact.amount ?? '-' }}
                    </TableData>
                    <TableData>
                        {{ contact.type ?? '-' }}
                    </TableData>
                    <TableData>
                        {{ contact.status ?? '-' }}
                    </TableData>
                    <TableData>
                        {{ contact.transacted_at ?? '-' }}
                    </TableData>
                    <td class="text-center">
                        <IconButton @click="handle_reverse()"
                                    class="text-red-500 focus:ring-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>

                        </IconButton>
                    </td>

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
            <Pagination :from="transactions.from" :to="transactions.to" :total="transactions.total" :links="transactions.links" />
        </template>
    </Table>
</template>
