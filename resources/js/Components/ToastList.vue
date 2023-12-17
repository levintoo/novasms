<script setup>
import ToastListItem from "@/Components/ToastListItem.vue";
import {computed, watch} from "vue";
import {usePage} from "@inertiajs/vue3";
import toast from "@/Stores/Toast.js";
const page = usePage()

const remove = (index) => {
  toast.remove(index)
};

const toastObject = computed(() => page.props.toast)

watch(toastObject,(updated) => {
    if(Object.keys(updated).length > 0)
        toast.add({
            message: updated.message,
            type: updated.type,
            duration: 5000
        })
});
</script>

<template>
    <TransitionGroup enter-active-class="duration-500"
                     enter-from-class="translate-x-full opacity-0"
                     leave-active-class="duration-500"
                     leave-to-class="translate-x-full opacity-0"
                     tag="div"

    class="fixed top-4 right-0 z-50 space-y-4 text-gray-500 w-full max-w-xs px-3">
            <ToastListItem v-for="(item, index) in toast.items" :key="item.key"  :duration="item.duration" :message="item.message" :type="item.type" @remove="remove(index)"/>
        </TransitionGroup>
</template>
