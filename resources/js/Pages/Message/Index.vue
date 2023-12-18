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
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import {debounce, omitBy} from "lodash";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";

const page = usePage()

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    messages: {
        type: Object
    },
    groups: {
        type: Object,
        default: {},
    },
    filters: {
        type: Object,
        default: {},
    },
})

const params = ref({
    search: props.filters.search ?? "",
    field: props.filters.field ?? "",
    direction: props.filters.direction ?? "",
    status: props.filters.status ?? "",
    group: props.filters.group ?? "",
    start_date: props.filters.start_date ?? "",
    end_date: props.filters.end_date ?? "",
})

const sort = (field) => {
    params.value.field = field
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc'
}

watch(params.value, debounce((params) => {
    router.get('/message', { ...omitBy(params, v => v === "") }, { preserveScroll: true, replace: true, preserveState: true },)
},150));

const resetFilters = () => {
    params.value.search = ""
    params.value.field = ""
    params.value.direction = ""
    params.value.status = ""
    params.value.start_date = ""
    params.value.end_date = ""
    params.value.group = ""
}

const handleDelete = (id) => {
    if(!confirm('Are you sure you want to continue, this is a destructive action')) return;
    router.delete(route('message.delete',id), {
        preserveScroll: true,
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

    <div class="w-full rounded-t p-5 bg-white">
        <div class="md:flex items-center justify-between">
            <p class="font-medium">
                Filters
            </p>

            <SecondaryButton @click="resetFilters()" class="px-4 py-2 text-sm font-medium rounded-md">
                Reset Filters
            </SecondaryButton>
        </div>

        <div>
            <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-4 mt-4">

                <div class="space-y-1">
                    <InputLabel for="search" value="Search" />
                    <TextInput placeholder="search here..." id="search" v-model="params.search" type="text" class="px-3 py-2 w-full text-sm" />
                </div>

                <div class="space-y-1">
                    <InputLabel for="status" value="Delivery status" />
                    <SelectInput id="status" v-model="params.status" class="px-3 py-2 w-full text-sm" :class="params.status === '' ? 'text-gray-500' : '' ">
                        <option value="">select</option>
                        <option value="delivered">delivered</option>
                        <option value="undelivered">undelivered</option>
                    </SelectInput>
                </div>

                <div class="space-y-1">
                    <InputLabel for="start_date" value="Start date" />
                    <TextInput id="start_date" v-model="params.start_date" type="datetime-local" class="px-3 py-2 w-full text-sm" :class="params.start_date === '' ? 'text-gray-500' : '' "/>
                </div>

                <div class="space-y-1">
                    <InputLabel for="end_date" value="End date" />
                    <TextInput id="end_date" v-model="params.end_date" type="datetime-local" class="px-3 py-2 w-full text-sm" :class="params.end_date === '' ? 'text-gray-500' : '' "/>
                </div>

                <div class="space-y-1">
                    <InputLabel for="status" value="Group" />
                    <SelectInput id="status" v-model="params.group" :class="params.group === '' ? 'text-gray-500' : '' " class="px-3 py-2 w-full text-sm">
                        <option value="">select</option>
                        <template v-if="Object.keys(groups).length > 0">
                            <option v-for="group in groups" :value="group.id">{{ group.name ?? '-' }}</option>
                        </template>
                    </SelectInput>
                </div>
            </div>
        </div>
    </div>

    <Table >
        <template #thead>
            <TableHead>
                <TableHeadItem @click="sort('recipient')" class="cursor-pointer" :params="params" field="recipient" />
                <TableHeadItem @click="sort('content')" class="cursor-pointer" :params="params" field="content" />
                <TableHeadItem @click="sort('sent')" class="cursor-pointer" :params="params" field="sent" />
                <TableHeadItem field="delivered" />
                <TableHeadItem field="group" />
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
                    <TableData  class=" whitespace-nowrap">
                        <span v-if="message.group">
                            {{ message.group.name ?? '-' }}
                        </span>
                        <span v-else>-</span>
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
            <Pagination v-if="messages.links" :to="messages.to" :from="messages.from" :total="messages.total" :links="messages.links" />
        </template>
    </Table>
</template>
