<template>
    <div
        :class="{
            'bg-green-800 border-green-600': status === 'Open',
            'bg-red-800 border-red-600': status === 'Closed',
            'bg-yellow-800 border-yellow-600': status === 'In review',
        }"
        class="rounded-lg border-2 w-max px-2 py-0.5 text-sm"
    >
        {{ status }}
    </div>
</template>

<script>
export default {

    props: {
        status: {
            type: String,
            required: true,
        },
    },
}
</script>
