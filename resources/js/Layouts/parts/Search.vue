<script setup>
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import {debounce} from "lodash";
import toast from "@/Stores/Toast.js";
import {usePage} from "@inertiajs/vue3";

const search = ref("")
const open = ref(false)
let results = ref({});
const page = usePage()
const currentUrl = computed(() => page.url )

axios.defaults.withCredentials = true

watch(search, debounce(async (value, oldValue) => {
  open.value = true

  if(value === '') {
    results.value = {}
    return
  }

    if (value !== oldValue) {
        try {
            results.value = await axios.post('/universal-search', {
                search: value,
            })
        } catch (e) {
            toast.add({
                type: 'error',
                message: 'something went wrong',
                duration: 5000
            })
        }
    }
}, 150))

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

watch(currentUrl,(value, oldValue) => {
  if(value !== oldValue) {
    open.value = false
  }
} )
</script>

<template>
    <div class="relative flex items-center text-gray-400 z-50">
                            <span class="absolute left-4 h-6 flex items-center pr-3">
                                <svg
                                    class="w-4 fill-current"
                                    viewBox="0 0 35.997 36.004"
                                    xmlns="http://ww50w3.org/2000/svg"
                                >
                                    <path
                                        id="Icon_awesome-search"
                                        d="M35.508,31.127l-7.01-7.01a1.686,1.686,0,0,0-1.2-.492H26.156a14.618,14.618,0,1,0-2.531,2.531V27.3a1.686,1.686,0,0,0,.492,1.2l7.01,7.01a1.681,1.681,0,0,0,2.384,0l1.99-1.99a1.7,1.7,0,0,0,.007-2.391Zm-20.883-7.5a9,9,0,1,1,9-9A8.995,8.995,0,0,1,14.625,23.625Z"
                                        data-name="search"
                                    >

                                    </path>
                                </svg>
                            </span>
        <input
            v-model="search"
            id="leadingIcon"
            class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm text-gray-600 outline-none border border-gray-300 focus:border-blue-600 transition"
            name="leadingIcon"
            placeholder="Search here"
            type="search"
            @focus="open = true"
        >
    </div>

    <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

    <div
        v-if="open"
        class="absolute z-50 mt-2 rounded-md shadow-lg w-72"
    >
        <div v-if="results.data" class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white divide-y-[1px]">

          <!--  contacts  -->
          <div v-if="results.data.contacts  && Object.keys(results.data.contacts.data).length > 0 ">
            <h4 class="block w-full px-4 py-2 text-start font-extrabold text-sm leading-5 text-gray-800">
              Contacts
            </h4>

            <template
                v-for="contact in results.data.contacts.data"
            >
              <p class="grid space-y-1 w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <span>{{ contact.name }}</span>
                <span>{{ contact.phone }}</span>
                <span class="font-light">created: {{ contact.created }}</span>
              </p>
            </template>

          </div>

          <!--  groups  -->
          <div v-if="results.data.groups && Object.keys(results.data.groups.data).length > 0 ">
            <h4 class="block w-full px-4 py-2 text-start font-extrabold text-sm leading-5 text-gray-800">
              Groups
            </h4>

            <template
                v-for="group in results.data.groups.data"
            >
              <p class="grid space-y-1 w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <span>{{ group.name }}</span>
                <span class="font-light text-xs">{{ group.description }}</span>
                <span class="font-light">created: {{ group.created }}</span>
              </p>
            </template>

          </div>

          <!--  messages  -->
          <div v-if="results.data.messages && Object.keys(results.data.messages.data).length > 0 ">

            <h4 class="block w-full px-4 py-2 text-start font-extrabold text-sm leading-5 text-gray-800">
              Messages
            </h4>

            <template
                v-for="message in results.data.messages.data"
            >
              <p class="grid space-y-1 w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                <span>{{ message.body }}</span>
                <span class="font-light">recipient: {{ message.recipient }}</span>
                <span class="font-light">sent: {{ message.sent }}</span>
              </p>
            </template>

          </div>

        </div>
    </div>
</template>
