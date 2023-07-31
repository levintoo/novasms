<script setup>
import {Head, router, usePage} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import {defineOptions} from "vue";
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
defineOptions({
    layout: AppLayout,
})

defineProps({
    users: {
        type: Object,
        required: true,
    }
})

const page = usePage()

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
</script>

<template>
    <Head title="Manage Users" />

    <div>
        <div class="grid grid-cols-2">
            <h2 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
                Manage Users
            </h2>

            <span class="flex align-center justify-end">
                <PrimaryLink :href="route('admin.user.create')" class="flex justify-between my-6">
                    <span aria-hidden="true" class="mr-2">+</span>
                    <span>Add new</span>
                </PrimaryLink>
            </span>
        </div>
    </div>


    <Table>
       <template #thead>
           <TableHead>
               <TableHeadItem>Name</TableHeadItem>
               <TableHeadItem>Phone</TableHeadItem>
               <TableHeadItem>Email</TableHeadItem>
               <TableHeadItem>Groups</TableHeadItem>
               <TableHeadItem>Contacts</TableHeadItem>
               <TableHeadItem>Balance</TableHeadItem>
               <TableHeadItem>Joined</TableHeadItem>
               <TableHeadItem>Roles</TableHeadItem>
               <TableHeadItem colspan="3" class="text-center">action</TableHeadItem>
           </TableHead>
       </template>
        <template #tbody>
            <TableBody v-if="users.data.length > 0">
                <TableBodyItem v-for="user in users.data">
                    <TableData>{{ user.name }}</TableData>
                    <TableData class="whitespace-nowrap">{{ user.phone }}</TableData>
                    <TableData>{{ user.email }}</TableData>
                    <TableData>{{ user.groups }}</TableData>
                    <TableData>{{ user.contacts }}</TableData>
                    <TableData>{{ user.balance }}</TableData>
                    <TableData>{{ user.joined }}</TableData>
                    <TableData class="flex flex-col" v-if="user.roles.length > 0">
                        <span class="whitespace-nowrap" v-for="role in user.roles">{{ role }}</span>
                    </TableData>
                    <TableData v-else>
                        -
                    </TableData>
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
                    <td class="text-center">
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
                </TableBodyItem>
            </TableBody>
            <TableBodyItem v-else class="bg-white">
                <TableData colspan="6" class="text-center text-gray-500">
                    There is nothing to show here
                </TableData>
            </TableBodyItem>
        </template>
        <template #pagination>
            <Pagination :links="users.links" />
        </template>
    </Table>

</template>
