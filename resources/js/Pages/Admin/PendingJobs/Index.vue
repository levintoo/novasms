<script setup>
import {Head} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
import Table from "@/Components/Table.vue";
import TableHead from "@/Components/TableHead.vue";
import TableBody from "@/Components/TableBody.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import TableData from "@/Components/TableData.vue";
import Pagination from "@/Components/Pagination.vue";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    jobs: {
        type: Object,
        required: true,
    },
})
</script>

<template>
<pre>{{ jobs }}</pre>
    <Head title="Manage Pending Jobs" />

    <div class="w-full md:flex md:justify-between mb-4">
        <h2 class="mt-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Manage Pending Jobs
        </h2>
    </div>

    <Table>
        <template #thead>
            <TableHead>
                <TableHeadItem field="name" />
                <TableHeadItem field="owner" />
            </TableHead>
        </template>
        <template #tbody>
            <TableBody class="whitespace-nowrap" v-if="jobs.data.length > 0">
                <TableBodyItem v-for="job in jobs.data">
                    <TableData>{{ job.id }}</TableData>
                </TableBodyItem>
            </TableBody>
            <TableBodyItem v-else class="bg-white">
                <TableData colspan="10" class="text-center text-gray-500">
                    There is nothing to show here
                </TableData>
            </TableBodyItem>
        </template>
        <template #pagination>
            <Pagination v-if="jobs.links" :from="jobs.from" :to="jobs.to" :total="jobs.total" :links="jobs.links" />
        </template>
    </Table>

</template>
