<script setup>
import {Head, Link} from '@inertiajs/vue3';
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
                <TableHeadItem field="batch id" />
                <TableHeadItem field="progress" />
                <TableHeadItem field="finished" />
                <TableHeadItem field="time taken" />
                <TableHeadItem field="jobs" />
            </TableHead>
        </template>
        <template #tbody>
            <TableBody class="whitespace-nowrap" v-if="jobs.data.length > 0">
                <TableBodyItem v-for="job in jobs.data">
                    <TableData>{{ job.name }}</TableData>
                    <TableData>
                        <div class="text-sm">
                            <Link :href='`/admin/user/${job.owner.id}`' class="underline hover:text-blue-800">{{ job.owner.name }}</Link>
                            <p class="text-xs text-gray-600">{{ job.owner.email ?? '-' }}</p>
                        </div>
                    </TableData>
                    <TableData>{{ job.batch[0].id }}</TableData>
                    <TableData>{{ `${job.batch[0].progress}%` }}</TableData>
                    <TableData>
                        <span v-if="!job.batch[0].failed && job.batch[0].finishedAt" class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                           {{ job.batch[0].finishedAt }}
                        </span>
                        <span v-else-if="job.batch[0].failed && job.batch[0].finishedAt" class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                             {{ job.batch[0].finishedAt }}
                        </span>
                        <span v-else class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                           {{ job.batch[0].finishedAt }}
                        </span>
                    </TableData>
                    <TableData>{{ job.batch[0].timeTaken }}</TableData>
                    <TableData>{{ job.batch[0].jobs }}</TableData>
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
