<template>
    <button
        class="text-center border border-zinc-600 rounded-md bg-zinc-800 py-1.5 px-4 text-sm hover:bg-zinc-800/30"
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
