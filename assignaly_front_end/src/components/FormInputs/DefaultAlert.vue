<template>
    <div
        :class="classes"
        class="rounded-md font-semibold text-sm border-2 p-2 h-max"
    >
        <slot />
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            default: 'error',
        },
    },

    computed: {
        classes() {
            if (this.type === 'error') {
                return 'bg-red-800 text-red-200 border-red-700'
            }

            if (this.type === 'warning') {
                return 'bg-yellow-800 text-yellow-200 border-yellow-700'
            }

            if (this.type === 'ok') {
                return 'bg-green-800 text-green-200 border-green-700'
            }
        },
    },
}
</script>
