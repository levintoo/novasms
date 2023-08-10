<script setup>
import {Head, router, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import IconButton from "@/Components/IconButton.vue";
import toast from "@/Stores/Toast.js";
import Table from "@/Components/Table.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import TableHead from "@/Components/TableHead.vue";
import TableBody from "@/Components/TableBody.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import TableData from "@/Components/TableData.vue";
import {defineOptions} from "vue";

const page = usePage()

defineOptions({
    layout: AppLayout,
})

defineProps({
    messages: {
        type: Object
    }
})


const handleDelete = (id) => {
    if(!confirm('Are you sure you want to continue, this is a destructive action')) return;
    router.delete(route('message.delete',id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                message: page.props.toast,
                duration: 5000
            })
        },
        onError: () => {
            toast.add({
                message: 'something went wrong',
                duration: 5000
            })
        },
    })
}
</script>

<template>
    <Head title="Messages"/>

    <div>
        <h2 class="my-6 text-xl sm:text-xl font-bold text-gray-700 dark:text-gray-200">
            Sent Messages
        </h2>
    </div>

    <Table >
        <template #thead>
            <TableHead>
                <TableHeadItem field="recipient" />
                <TableHeadItem field="message" />
                <TableHeadItem field="sent" />
                <TableHeadItem field="delivered" />
                <TableHeadItem field="Actions" class="text-center"/>
            </TableHead>
        </template>
        <template #tbody>
            <TableBody v-if="messages.data.length > 0">
                <TableBodyItem v-for="message in messages.data">
                    <TableData class="whitespace-nowrap">
                        {{ message.recipient ?? '-' }}
                    </TableData>
                    <TableData class="">
                        {{ message.content ?? '-' }}
                    </TableData>
                    <TableData class=" whitespace-nowrap">
                        {{ message.sent ?? '-' }}
                    </TableData>
                    <TableData class=" text-xs">
                        <!--                        {{ message.delivered ?? null }}-->
                        <span
                            v-if="message.delivered"
                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full"
                        >
                          Delivered
                        </span>
                        <span
                            v-else
                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full"
                        >
                          Pending
                        </span>
                    </TableData>
                    <td class="text-center ">
                        <IconButton @click="handleDelete(message.id)"
                                    class="text-red-500 focus:ring-red-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd"
                                      d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                      fill-rule="evenodd"/>
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
            <Pagination :links="messages.links" />
        </template>
    </Table>
</template>
