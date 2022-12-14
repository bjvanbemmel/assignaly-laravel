<template>
    <router-link
        v-if="to"
        :to="to"
        class="flex items-center justify-center focus-visible:outline-none ring-zinc-600 focus-visible:ring-2 bg-zinc-800 text-zinc-200 hover:bg-zinc-800/30 focus-visible:bg-zinc-800/30 border border-zinc-600 rounded-md px-2 py-1 placeholder-zinc-300/700"
    >
        {{ text }}
    </router-link>

    <button
        v-else
        class="focus-visible:outline-none ring-zinc-600 focus-visible:ring-2 bg-zinc-800 text-zinc-200 hover:bg-zinc-800/30 focus-visible:bg-zinc-800/30 border border-zinc-600 rounded-md px-2 py-1 placeholder-zinc-300/700"
    >
        {{ text }}
    </button>
</template>

<script>
export default {

    props: {
        text: {
            type: String,
            required: true,
        },
        to: {
            type: Object,
            required: false,
        },
    },
}
</script>
