<script setup>
import {Head} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions, onMounted, onUnmounted} from "vue";
import Pagination from "@/Components/Pagination.vue";
import TableBody from "@/Components/TableBody.vue";
import Table from "@/Components/Table.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import IconLink from "@/Components/IconLink.vue";
import IconButton from "@/Components/IconButton.vue";
import TableData from "@/Components/TableData.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import TableHead from "@/Components/TableHead.vue";

defineOptions({
    layout: AppLayout,
})

defineProps({
    jobs: {
        type: Object,
        required: true,
    }
})

onMounted(() => {
    const update_interval = setInterval(update,1000)
    console.log(update_interval)
})

const update = () => {
    console.log("ping")
}

window.onblur = function () {

};

// If users come back to the current tab again, the below function will invoke
window.onfocus = function () {

};
</script>

<template>
    <Head title="Pending Jobs" />

    <div>
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Pending Jobs
        </h2>
    </div>


    <Table >
        <template #thead>
            <TableHead>
                <TableHeadItem  class="cursor-pointer" field="name"/>
                <TableHeadItem  class="cursor-pointer" field="progress"/>
                <TableHeadItem  class="cursor-pointer" field="finished"/>
                <TableHeadItem field="Actions" class="text-center"/>
            </TableHead>
        </template>
        <template #tbody>
            <TableBody v-if="jobs.data.length > 0">
                <TableBodyItem v-for="job in jobs.data">
                    <TableData >
                        {{ job.name ?? '-' }}
                    </TableData>
                    <TableData >
                        {{ `${job.info.progress}%` ?? '-' }}
                    </TableData>
                    <TableData >
                        <span v-if="!job.info.cancelledAt" class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                           {{ !!job.info.finishedAt ?? '-' }}
                        </span>
                        <span v-else class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                             {{ !!job.info.finishedAt ?? '-' }}
                        </span>
                    </TableData>
                    <td>
                        <IconButton v-if="job.info.cancelledAt" @click="alert('dadfs')"
                                    class="text-purple-500 focus:ring-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>

                        </IconButton>
                        <span v-else>
                            -
                        </span>
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
            <Pagination :to="jobs.to" :from="jobs.from" :total="jobs.total" :links="jobs.links" />
        </template>
    </Table>
</template>
