<template>
    <div
        :class="{
            'bg-green-800 border-green-600': status === 'open',
            'bg-red-800 border-red-600': status === 'closed',
            'bg-yellow-800 border-yellow-600': status === 'in-review',
        }"
        class="rounded-lg border-2 w-max px-2 py-0.5 text-sm"
    >
        {{ label }}
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

    computed: {
        label () {
            if (this.status === 'open') {
                return 'Open'
            }

            if (this.status === 'closed') {
                return 'Closed'
            }

            if (this.status === 'in-review') {
                return 'In review'
            }

        },
    },
}
</script>
