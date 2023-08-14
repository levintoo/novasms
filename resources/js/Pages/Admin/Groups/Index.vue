<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {ref, watch} from "vue";
import {debounce, omitBy} from "lodash";
import TableHeadItem from "@/Components/TableHeadItem.vue";
import TableHead from "@/Components/TableHead.vue";
import IconLink from "@/Components/IconLink.vue";
import IconButton from "@/Components/IconButton.vue";
import Pagination from "@/Components/Pagination.vue";
import TableBody from "@/Components/TableBody.vue";
import TableBodyItem from "@/Components/TableBodyItem.vue";
import Table from "@/Components/Table.vue";
import TableData from "@/Components/TableData.vue";

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
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
    trashed: props.filters.trashed ?? "without",
    uid: props.filters.uid ?? "",
})

const sort = (field) => {
    params.value.field = field
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc'
}

watch(params.value, debounce((params) => {
    router.get(route('admin.groups'), { ...omitBy(params, v => v === "") }, { preserveScroll: true, replace: true, preserveState: true },)
},150));

const resetFilters = () => {
    params.value.search = ""
    params.value.field = ""
    params.value.direction = ""
    params.value.trashed = "without"
    params.value.uid = ""
}
</script>

<template>
    <Head title="Manage Groups" />

    <div>
        <h2 class="my-6 text-xl sm:text-xl font-bold text-gray-700 dark:text-gray-200">
            Manage Groups
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
                    <InputLabel for="search" value="search" />
                    <TextInput v-model="params.search" placeholder="search here..." id="search" type="text" class="px-3 py-2 w-full text-sm" />
                </div>

                <div class="space-y-1">
                    <InputLabel for="status" value="status" />
                    <SelectInput v-model="params.trashed" id="status" class="px-3 py-2 w-full text-sm" :class="params.trashed === 'without' ? 'text-gray-500' : '' ">
                        <option value="without">without trashed</option>
                        <option value="with">with trashed</option>
                        <option value="only">only trashed</option>
                    </SelectInput>
                </div>

                <div class="space-y-1">
                    <InputLabel for="user_id" value="user id" />
                    <TextInput placeholder="user id" v-model="params.uid" id="user_id" type="number" class="px-3 py-2 w-full text-sm" />
                </div>
            </div>
        </div>
    </div>

    <Table >
        <template #thead>
            <TableHead>
                <TableHeadItem :params="params" @click="sort('name')" class="cursor-pointer" field="name"/>
                <TableHeadItem :params="params" @click="sort('description')" class="cursor-pointer" field="description"/>
                <TableHeadItem field="User"/>
                <TableHeadItem :params="params" @click="sort('contacts')" class="cursor-pointer"  field="contacts" />
                <TableHeadItem :params="params" @click="sort('created')" class="cursor-pointer" field="created"/>
                <TableHeadItem field="Actions" class="text-center" colspan="3"/>
            </TableHead>
        </template>
        <template #tbody>
            <TableBody v-if="groups.data.length > 0">
                <TableBodyItem v-for="group in groups.data">
                    <TableData >
                        {{ group.name ?? '-' }}
                    </TableData>
                    <TableData>
                        {{ group.description ?? '-' }}
                    </TableData>
                    <TableData class="grid grid-cols-1 w-full">
                        <span>{{ group.user.name ?? '-' }}</span>
                        <span class="text-xs">{{ group.user.email }}</span>
                    </TableData>
                    <TableData>
                        {{ group.contacts ?? '-' }}
                    </TableData>
                    <TableData>
                        {{ group.created ?? '-' }}
                    </TableData>
                    <td class="text-center">
                        <IconLink v-if="!group.trashed" :href="route('group.edit', group.id)"
                                  class="text-gray-500 focus:ring-gray-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                <path
                                    d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                            </svg>
                        </IconLink>
                        <span v-else-if="group.trashed && params.trashed !== 'only' ">
                            -
                        </span>
                    </td>
                    <td class="text-center">
                        <IconButton v-if="!group.trashed" @click="handleDelete(group.id)"
                                    class="text-red-500 focus:ring-red-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd"
                                      d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                      fill-rule="evenodd"/>
                            </svg>
                        </IconButton>
                        <IconButton v-if="group.trashed" @click="handleRestore(group.id)"
                                    class="text-purple-500 focus:ring-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 01-9.201 2.466l-.312-.311h2.433a.75.75 0 000-1.5H3.989a.75.75 0 00-.75.75v4.242a.75.75 0 001.5 0v-2.43l.31.31a7 7 0 0011.712-3.138.75.75 0 00-1.449-.39zm1.23-3.723a.75.75 0 00.219-.53V2.929a.75.75 0 00-1.5 0V5.36l-.31-.31A7 7 0 003.239 8.188a.75.75 0 101.448.389A5.5 5.5 0 0113.89 6.11l.311.31h-2.432a.75.75 0 000 1.5h4.243a.75.75 0 00.53-.219z" clip-rule="evenodd" />
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
            <Pagination :to="groups.to" :from="groups.from" :total="groups.total" :links="groups.links" />
        </template>
    </Table>
</template>
