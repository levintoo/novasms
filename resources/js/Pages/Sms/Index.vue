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
import {defineOptions, ref, watch} from "vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import {debounce, omitBy} from "lodash";

const page = usePage()

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    messages: {
        type: Object
    },
    filters: {
        type: Object,
        default: {},
    },
    messages_count: Number,
})

const params = ref({
    search: props.filters.search ?? "",
    field: props.filters.field ?? "",
    direction: props.filters.direction ?? "",
    status: props.filters.status ?? "",
})

const sort = (field) => {
    params.value.field = field
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc'
}

watch(params.value, debounce((params) => {
    router.get(route('messages'), { ...omitBy(params, v => v === "") }, { preserveScroll: true, replace: true, preserveState: true },)
},150));

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

    <div class="grid grid-cols-1 md:grid-cols-2 justify-center">
        <div class="md:space-x-2 grid grid-cols-1 md:grid-cols-2  space-y-2 md:space-y-0 md:my-auto">
            <SelectInput v-model="params.status" class="h-10 text-sm" :class="params.status === '' ? 'text-gray-500' : '' ">
                <option value="">select</option>
                <option >delivered</option>
                <option >undelivered</option>
            </SelectInput>
            <div class="block relative">
                    <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                            <path
                                d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                            </path>
                        </svg>
                    </span>
                <TextInput v-model="params.search" type="text" placeholder="Search"
                           class="block h-10 pl-8 pr-6 py-2 text-sm placeholder-gray-400" />
            </div>
        </div>
        <div class="md:justify-end flex items-center">
            <PrimaryLink :href="route('admin.user.create')" class="flex justify-between my-6">
                <span aria-hidden="true" class="mr-2">+</span>
                <span>Add new</span>
            </PrimaryLink>
        </div>
    </div>

    <Table >
        <template #thead>
            <TableHead>
                <TableHeadItem @click="sort('recipient')" class="cursor-pointer" :params="params" field="recipient" />
                <TableHeadItem @click="sort('content')" class="cursor-pointer" :params="params" field="content" />
                <TableHeadItem @click="sort('sent')" class="cursor-pointer" :params="params" field="sent" />
                <TableHeadItem field="delivered" />
                <TableHeadItem field="Actions"/>
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
                    <TableData class="text-xs">
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
            <Pagination :to="messages.to" :from="messages.from" :count="messages_count" :links="messages.links" />
        </template>
    </Table>
</template>
