<script setup>

const props = defineProps({
    message: String,
    type: String,
    duration: {
        type: Number,
        default: 3000,
    }
})

const emit = defineEmits(['remove'])

setTimeout(() => {
    emit('remove')
}, props.duration)
</script>

<template>

    <div class="shadow-lg rounded-lg bg-white mx-auto m-8 p-4 shadow-3xl"
         role="alert">

        <div class="text-sm pb-2">
            <span class="font-extrabold">{{ type ?? 'info' }}</span>
            <span class="float-right hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100" @click="emit('remove')">
            <svg
                class="fill-current text-gray-600"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                width="22"
                height="22"
            >
              <path
                  class="heroicon-ui"
                  d="M16.24 14.83a1 1 0 0 1-1.41 1.41L12 13.41l-2.83 2.83a1 1 0 0 1-1.41-1.41L10.59 12 7.76 9.17a1 1 0 0 1 1.41-1.41L12 10.59l2.83-2.83a1 1 0 0 1 1.41 1.41L13.41 12l2.83 2.83z"
              />
            </svg>
          </span>
        </div>
        <div class="text-sm text-gray-600  tracking-tight ">
           {{ message ?? '-'}}
        </div>
    </div>

</template>
