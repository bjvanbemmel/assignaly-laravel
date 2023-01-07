<template>
    <router-link
        v-if="to"
        :to="to"
        class="flex items-center justify-center disabled:cursor-not-allowed disabled:bg-zinc-900 disabled:text-zinc-600 focus-visible:outline-none ring-zinc-600 focus-visible:ring-2 bg-zinc-800 text-zinc-200 hover:bg-zinc-800/30 focus-visible:bg-zinc-800/30 border border-zinc-600 rounded-md px-2 py-1 placeholder-zinc-300/700"
    >
        <slot>{{ text }}</slot>
    </router-link>

    <button
        v-else
        class="flex items-center justify-center disabled:cursor-not-allowed disabled:bg-zinc-900 disabled:text-zinc-600 focus-visible:outline-none ring-zinc-600 focus-visible:ring-2 bg-zinc-800 text-zinc-200 hover:bg-zinc-800/30 focus-visible:bg-zinc-800/30 border border-zinc-600 rounded-md px-2 py-1 placeholder-zinc-300/700"
    >
        <slot>{{ text }}</slot>
    </button>
</template>

<script>
export default {

    props: {
        text: {
            type: String,
            required: false,
        },

        to: {
            type: Object,
            required: false,
        },
    },
}
</script>
