<script setup>
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import toast from "@/Stores/Toast.js";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import {computed, defineOptions} from "vue";
import IconButton from "@/Components/IconButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const page = usePage()

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    users: {
        type: Object,
        default: {},
    }
})

const user = computed(() => props.users[0] ?? {})

const handleDelete = (id) => {
    if(!confirm('Are you sure you want to continue, this is a destructive action')) return;
    router.delete(route('admin.user.delete',id), {
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
    router.patch(route('admin.user.restore',id), {
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
    router.patch(route('admin.user.password.reset',id), {
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
    <Head :title="user.name ?? 'User'" />

        <div>
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                {{ user.name ?? 'User' }}
            </h2>
        </div>

    <!-- component -->
    <!-- This is an example component -->

        <div class="bg-white w-full rounded-lg shadow-xl">
            <div class="p-4 border-b">
                <h2 class="text-2xl ">
                    User Information
                </h2>
                <p class="text-sm text-gray-500">
                    Personal details.
                </p>
            </div>
            <div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Name
                    </p>
                    <p>
                        {{ user.name ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Roles
                    </p>
                    <p v-if="user.roles.length > 0">
                        <span v-for="role in user.roles">{{ role }}</span>
                    </p>
                    <p v-else> - </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Email Address
                    </p>
                    <p>
                        {{ user.email ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Phone
                    </p>
                    <p>
                        {{ user.phone ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Balance
                    </p>
                    <p>
                        {{ user.balance ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Contacts
                    </p>
                    <p>
                        {{ user.contacts_count ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Trashed Contacts
                    </p>
                    <p>
                        {{ user.trashed_contacts_count ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Groups
                    </p>
                    <p>
                        {{ user.groups_count ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Trashed Groups
                    </p>
                    <p>
                        {{ user.trashed_groups_count ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Joined
                    </p>
                    <p>
                        {{ user.created_at ?? '-'}}
                    </p>
                </div>
                <div v-if="user.deleted_at" class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Deactivated at
                    </p>
                    <p>
                        {{ user.deleted_at ?? '-'}}
                    </p>
                </div>
                <div class="md:grid md:grid-cols-2 hover:bg-gray-50 md:space-y-0 space-y-1 p-4 border-b">
                    <p class="text-gray-600">
                        Actions
                    </p>
                    <p>
                    <span v-if="!user.deleted_at" class="text-center">
                        <DangerButton @click="handleDelete(user.id)"
                                    class="text-red-500 focus:ring-red-300">
                            Delete
                        </DangerButton>
                    </span>
                    <span v-if="user.deleted_at" class="text-center">
                        <PrimaryButton @click="handleRestore(user.id)"
                                    class="text-purple-500 focus:ring-purple-300">
                            Restore
                        </PrimaryButton>
                    </span>
                    </p>
                </div>
            </div>
        </div>
</template>
