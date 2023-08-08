<script setup>
import {Head, router, usePage} from '@inertiajs/vue3';
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
import PrimaryLink from "@/Components/PrimaryLink.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import {Link} from "@inertiajs/vue3";
import {debounce} from "lodash";

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
    field: props.filters.field ?? "joined",
    direction: props.filters.direction ?? "desc",
    trashed: props.filters.trashed ?? "without",
})

const sort = (field) => {
    params.value.field = field
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc'
}

watch(params.value, debounce((params) => {
    router.get(route('admin.users'), { trashed: params.trashed, search: params.search, field: params.field, direction: params.direction }, { preserveScroll: true, replace: true, preserveState: true },)
},150));

const handleDelete = (id) => {
    if(!confirm('Are you sure you want to continue, this is a destructive action')) return;
    router.delete(route('admin.user.delete',id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                message: page.props.toast,
                duration: 5000
            })
        },
        onError: (errors) => {
            toast.add({
                message: 'something went wrong',
                duration: 5000
            })
        },
    })
}
const handleRestore = (id) => {
    if(!confirm('Are you sure you want to restore user')) return;
    router.patch(route('admin.user.restore',id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                message: page.props.toast,
                duration: 5000
            })
        },
        onError: (errors) => {
            toast.add({
                message: 'something went wrong',
                duration: 5000
            })
        },
    })
}
</script>

<template>
    <Head title="Manage Users" />

    <div>
        <h2 class="mt-6 mb-4 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Manage Users
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 justify-center">
        <div class="md:space-x-2 grid grid-cols-1 md:grid-cols-2  space-y-2 md:space-y-0 md:my-auto">
            <SelectInput class="h-10 text-sm" v-model="params.trashed">
                <option value="without">Without trashed</option>
                <option value="with">With trashed</option>
                <option value="only">Only trashed</option>
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
                       class="block pl-8 pr-6 py-2 text-sm placeholder-gray-400" />
            </div>
        </div>
        <div class="md:justify-end flex items-center">
            <PrimaryLink :href="route('admin.user.create')" class="flex justify-between my-6">
                <span aria-hidden="true" class="mr-2">+</span>
                <span>Add new</span>
            </PrimaryLink>
        </div>
    </div>

    <Table>
       <template #thead>
           <TableHead>
               <TableHeadItem field="name" :params=params @click="sort('name')" />
               <TableHeadItem field="phone" :params=params @click="sort('phone')" />
               <TableHeadItem field="email" :params=params @click="sort('email')" />
               <TableHeadItem field="groups" :params="params" @click="sort('groups')"/>
               <TableHeadItem field="contacts" :params="params" @click="sort('contacts')" />
               <TableHeadItem field="balance" :params="params" @click="sort('balance')" />
               <TableHeadItem field="joined" :params="params" @click="sort('joined')" />
               <TableHeadItem field="action" colspan="3" class="text-center">action</TableHeadItem>
           </TableHead>
       </template>
        <template #tbody>
            <TableBody v-if="users.data.length > 0">
                <TableBodyItem v-for="user in users.data">
                    <TableData>
                        <div class="text-sm">
                            <Link :href="route('admin.user.show',user.id)" class="underline hover:text-purple-800">{{ user.name }}</Link>
                            <div v-if="user.roles.length > 0">
                                <p v-for="role in user.roles" class="text-xs text-gray-600">{{ role ?? '-' }}</p>
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
                        <IconLink :href="route('admin.user.edit',user.id)"
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
            <Pagination :links="users.links" />
        </template>
    </Table>

</template>
