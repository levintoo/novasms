<script setup>
import {Head, router, usePage, Link} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions, ref, watch} from "vue";
import Table from "@/Components/Table.vue";
import TableHead from "@/Components/TableHead.vue";
import TableBody from "@/Components/TableBody.vue";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import TableData from "@/Components/TableData.vue";
import Pagination from "@/Components/Pagination.vue";
import IconButton from "@/Components/IconButton.vue";
import IconLink from "@/Components/IconLink.vue";
import toast from "@/Stores/Toast.js";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import {debounce, omitBy} from "lodash";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
    },
})

const page = usePage()

const params = ref({
    search: props.filters.search ?? "",
    field: props.filters.field ?? "",
    direction: props.filters.direction ?? "",
    trashed: props.filters.trashed ?? "without",
})

const sort = (field) => {
    params.value.field = field
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc'
}

watch(params.value, debounce((params) => {
    router.get('/admin/user', { ...omitBy(params, v => v === "") }, { preserveScroll: true, replace: true, preserveState: true },)
},150));

const resetFilters = () => {
    params.value.search = ""
    params.value.field = ""
    params.value.direction = ""
    params.value.trashed = "without"
}

const handleDelete = (id) => {
    if(!confirm('Are you sure you want to continue, this is a destructive action')) return;
    router.delete('/admin/user/'+ id, {
        preserveScroll: true,
        onError: (errors) => {
            toast.add({
                message: 'something went wrong',
                type: 'error',
                duration: 5000
            })
        },
    })
}
const handleRestore = (id) => {
    if(!confirm('Are you sure you want to restore user')) return;
    router.patch('/admin/user/restore/' + id, {
        preserveScroll: true,
        onError: (errors) => {
            toast.add({
                message: 'something went wrong',
                type: 'error',
                duration: 5000
            })
        },
    })
}
const handleReset = (id) => {
    if(!confirm('Are you sure you want to reset user password')) return;
    router.patch('/admin/user/' + id + '/password/reset/', {
        preserveScroll: true,
        onError: (errors) => {
            toast.add({
                message: 'something went wrong',
                type: 'error',
                duration: 5000
            })
        },
    })
}
</script>

<template>

    <Head title="Manage Users" />

    <div class="w-full md:flex md:justify-between mb-4">
        <h2 class="mt-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Manage Users
        </h2>
        <div class="md:justify-end flex items-center">
            <Link href="/admin/user/create" >
                <PrimaryButton class="flex justify-between my-6">
                    <span aria-hidden="true" class="mr-2">+</span>
                    <span>Add new</span>
                </PrimaryButton>
            </Link>
        </div>
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
                    <InputLabel for="search" value="search" />
                    <TextInput placeholder="search here..." id="search" v-model="params.search" type="text" class="px-3 py-2 w-full text-sm" />
                </div>

                <div class="space-y-1">
                    <InputLabel for="status" value="trashed" />
                    <SelectInput id="status" v-model="params.trashed" class="px-3 py-2 w-full text-sm" :class="params.status === '' ? 'text-gray-500' : '' ">
                        <option value="without">Without trashed</option>
                        <option value="with">With trashed</option>
                        <option value="only">Only trashed</option>
                    </SelectInput>
                </div>
            </div>
        </div>
    </div>

    <Table>
       <template #thead>
           <TableHead>
               <TableHeadItem field="id" />
               <TableHeadItem class="cursor-pointer" field="name" :params=params @click="sort('name')" />
               <TableHeadItem class="cursor-pointer" field="phone" :params=params @click="sort('phone')" />
               <TableHeadItem class="cursor-pointer" field="email" :params=params @click="sort('email')" />
               <TableHeadItem class="cursor-pointer" field="groups" :params="params" @click="sort('groups')"/>
               <TableHeadItem class="cursor-pointer" field="contacts" :params="params" @click="sort('contacts')" />
               <TableHeadItem class="cursor-pointer" field="balance" :params="params" @click="sort('balance')" />
               <TableHeadItem class="cursor-pointer" field="joined" :params="params" @click="sort('joined')" />
               <TableHeadItem field="action" colspan="3" class="text-center">action</TableHeadItem>
           </TableHead>
       </template>
        <template #tbody>
            <TableBody v-if="users.data.length > 0">
                <TableBodyItem v-for="user in users.data">
                    <TableData class="whitespace-nowrap">{{ user.id }}</TableData>
                    <TableData>
                        <div class="text-sm">
                            <Link :href="`/admin/user/show/${user.id}`" class="underline hover:text-purple-800">{{ user.name }}</Link>
                            <div v-if="user.roles.length > 0" class="">
                                <p v-for="role in user.roles" class="text-xs text-gray-600">{{ role.name ?? '-' }}</p>
                            </div>
                            <p v-else class="text-xs text-gray-600">-</p>
                        </div>
                    </TableData>
                    <TableData class="whitespace-nowrap">{{ user.phone }}</TableData>
                    <TableData>
                        <div class="max-w-md overflow-hidden">
                            <p>{{ user.email }}</p>
                        </div>
                    </TableData>
                    <TableData>{{ user.groups }}</TableData>
                    <TableData>{{ user.contacts }}</TableData>
                    <TableData>{{ user.balance }}</TableData>
                    <TableData>{{ user.joined }}</TableData>
                    <td class="text-center">
                        <IconButton v-if="!user.trashed" @click="handleReset(user.id)"
                                    class="text-purple-500 focus:ring-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </IconButton>
                        <span v-else-if="user.trashed && params.trashed !== 'only' "> - </span>
                    </td>
                    <td class="text-center">
                        <IconLink v-if="!user.trashed" :href="`/admin/user/edit/${user.id}`"
                                  class="text-gray-500 focus:ring-gray-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                <path
                                    d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                            </svg>
                        </IconLink>
                        <span v-else-if="user.trashed && params.trashed !== 'only' "> - </span>
                    </td>
                    <td v-if="!user.trashed" class="text-center">
                        <IconButton @click="handleDelete(user.id)"
                                    class="text-red-500 focus:ring-red-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd"
                                      d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                      fill-rule="evenodd"/>
                            </svg>
                        </IconButton>
                    </td>
                    <td v-if="user.trashed" class="text-center">
                        <IconButton @click="handleRestore(user.id)"
                                    class="text-purple-500 focus:ring-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
                            </svg>
                        </IconButton>
                    </td>
                </TableBodyItem>
            </TableBody>
            <TableBodyItem v-else class="bg-white">
                <TableData colspan="10" class="text-center text-gray-500">
                    There is nothing to show here
                </TableData>
            </TableBodyItem>
        </template>
        <template #pagination>
            <Pagination v-if="users.links" :from="users.from" :to="users.to" :total="users.total" :links="users.links" />
        </template>
    </Table>

</template>
