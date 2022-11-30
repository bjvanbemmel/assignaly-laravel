<template>
    <button
        class="text-center border border-gray-300 bg-gray-50 py-1.5 px-4 text-sm hover:bg-gray-100"
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
