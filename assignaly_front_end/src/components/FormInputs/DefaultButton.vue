<template>
    <button
        class="focus:outline-none ring-zinc-600 focus:ring-2 bg-zinc-800 text-zinc-200 hover:bg-zinc-800/30 focus:bg-zinc-800/30 border border-zinc-700 rounded-md px-2 py-1 placeholder-zinc-300/700"
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
    },
}
</script>
