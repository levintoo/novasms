<script setup>
import {Head, router, usePage, Link} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import IconButton from "@/Components/IconButton.vue";
import IconLink from "@/Components/IconLink.vue";
import toast from "@/Stores/Toast.js";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import TableHead from "@/Components/TableHead.vue";
import TableBody from "@/Components/TableBody.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import Table from "@/Components/Table.vue";
import TableData from "@/Components/TableData.vue";
import {ref, watch} from "vue";
import {debounce, omitBy} from "lodash";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
defineOptions({
    layout: AppLayout,
})
const props = defineProps({
    groups: {
        type: Object
    },
    filters: {
        type: Object,
        default: {},
    },
})

const page = usePage()

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
    router.get('/group', {...omitBy(params, v => v === "")}, {preserveScroll: true, replace: true, preserveState: true},)
},150));

const handleDelete = (id) => {

    if(!confirm('Are you sure you want to continue, this is a destructive action')) return;

    router.delete('/group/'+ id, {

        preserveScroll: true,

        onError: (errors) => {
            toast.add({
                type: 'error',
                message: 'something went wrong',
                duration: 5000
            })
        },

    })
}
</script>

<template>
    <Head title="Groups"/>

        <div>
            <h2 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
                Groups
            </h2>
        </div>

    <div class="w-full grid grid-cols-1 md:grid-cols-2 justify-center">

        <div class="md:space-x-2 grid grid-cols-1 md:grid-cols-2  space-y-2 md:space-y-0 md:my-auto">
            <div class="block relative">
                    <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                        <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                            <path
                                d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                            </path>
                        </svg>
                    </span>
                <TextInput v-model="params.search" type="text" placeholder="Search"
                           class="block h-10 w-full pl-8 pr-6 py-2 text-sm placeholder-gray-400" />
            </div>
        </div>

        <div class="md:flex justify-end items-center">
            <Link href="/group/create">
                <PrimaryButton class="flex justify-between my-6">
                    <span aria-hidden="true" class="mr-2">+</span>
                    <span>Add new</span>
                </PrimaryButton>
            </Link>
        </div>

    </div>

    <Table >

            <template #thead>
                <TableHead>
                    <TableHeadItem :params="params" @click="sort('name')" class="cursor-pointer" field="name"/>
                    <TableHeadItem :params="params" @click="sort('description')" class="cursor-pointer" field="description"/>
                    <TableHeadItem :params="params" @click="sort('size')" sort="size" class="cursor-pointer"  field="contacts" />
                    <TableHeadItem :params="params" @click="sort('created')" class="cursor-pointer" field="created"/>
                    <TableHeadItem field="Actions" class="text-center" colspan="3"/>
                </TableHead>
            </template>

            <template #tbody>
                <TableBody v-if="groups.data.length > 0">
                    <TableBodyItem v-for="group in groups.data">
                        <TableData class="whitespace-nowrap lg:whitespace-normal" >
                            {{ group.name ?? '-' }}
                        </TableData>
                        <TableData>
                            {{ group.description ?? '-' }}
                        </TableData>
                        <TableData>
                            {{ group.size ?? '-' }}
                        </TableData>
                        <TableData>
                            {{ group.created ?? '-' }}
                        </TableData>
                        <td class="text-center">
                            <IconLink :href="'/group/edit/' + group.id"
                                      class="text-gray-500 focus:ring-gray-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                    <path
                                        d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                                </svg>
                            </IconLink>
                        </td>
                        <td class="text-center">
                            <IconButton @click="handleDelete(group.id)"
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
                <Pagination v-if="groups.links" :to="groups.to" :from="groups.from" :total="groups.total" :links="groups.links" />
            </template>

        </Table>
</template>
