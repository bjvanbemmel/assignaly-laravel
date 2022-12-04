<template>
    <input
        :type="type"
        :name="validatedName"
        :id="validatedName"
        class="focus:outline-none ring-zinc-600 focus:ring-2 bg-zinc-800 text-zinc-200 hover:bg-zinc-800/30 focus:bg-zinc-800/30 border border-zinc-700 rounded-md px-2 py-1 placeholder-zinc-300/700"
        @change="emitText"
    />
</template>

<script>
export default {

    mounted () {
        this.validatedName = this.name ?? 'input-' + Math.floor(Math.random() * 100000)
    },

    props: {
        type: {
            type: String,
            default: 'text',
        },

        name: {
            type: String,
            required: false,
        },
    },

    data () {
        return {
            validatedName: null,
        }
    },

    methods: {
        emitText (event) {
            this.$emit('update', event.target.value)
        },
    },
}
</script>
