<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
import TableHead from "@/Components/TableHead.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import TableBody from "@/Components/TableBody.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import TableData from "@/Components/TableData.vue";
import Pagination from "@/Components/Pagination.vue";
import Table from "@/Components/Table.vue";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    jobs: {
        type: Object,
    }
})
</script>

<template>

    <Head title="Pending Jobs"/>

    <div>
        <h2 class="py-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Pending Jobs
        </h2>
    </div>

    <Table >
        <template #thead>
            <TableHead>
                <TableHeadItem field="name"/>
                <TableHeadItem field="progress"/>
                <TableHeadItem field="status"/>
                <TableHeadItem field="finished"/>
                <!--                <TableHeadItem field="Actions" class="text-center"/>-->
            </TableHead>
        </template>
        <template #tbody>
            <TableBody v-if="jobs.data.length > 0">
                <TableBodyItem v-for="job in jobs.data">
                    <TableData >
                        {{ job.name ?? '-' }}
                    </TableData>
                    <TableData >
                        {{ `${job.batch[0].progress}%` ?? '-' }}
                    </TableData>
                    <TableData >
                        <span v-if="!job.batch[0].cancelled && job.batch[0].finishedAt" class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                           completed
                        </span>
                        <span v-else-if="job.batch[0].cancelled && job.batch[0].finishedAt" class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                             failed
                        </span>
                        <span v-else class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                           pending
                        </span>
                    </TableData>
                    <TableData>
                        {{ job.batch[0].finishedAt ?? '-' }}
                    </TableData>
                    <!--                    <td>-->
                    <!--                        <IconButton v-if="job.batch[0].cancelledAt" @click="alert('dadfs')"-->
                    <!--                                    class="text-purple-500 focus:ring-purple-300">-->
                    <!--                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">-->
                    <!--                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />-->
                    <!--                            </svg>-->

                    <!--                        </IconButton>-->
                    <!--                        <span v-else>-->
                    <!--                            - -->
                    <!--                        </span>-->
                    <!--                    </td>-->
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
            <Pagination v-if="jobs.links" :to="jobs.to" :from="jobs.from" :total="jobs.total" :links="jobs.links" />
        </template>
    </Table>
</template>
