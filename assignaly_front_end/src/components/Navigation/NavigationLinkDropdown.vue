<template>
    <div
        :class="isActive ? 'h-max w-96 text-full border p-2 opacity-100' : 'h-0 w-0 text-[0%] p-0 opacity-0'"
        class="absolute transition-all duration-100 overflow-hidden flex flex-col top-14 bg-zinc-800 border-zinc-700"
    >
        <slot />
    </div>
</template>

<script>
import { useDropdownStore, } from './../../stores/dropdown.js'

export default {

    data () {
        return {
            isActive: false,
        }
    },

    mounted () {
        const dropdownStore = useDropdownStore()

        dropdownStore.$subscribe((_, state) => this.setIsActive(state))
    },

    props: {
        active: {
            type: Boolean,
            default: false,
        },

        name: {
            type: String,
            required: true,
        },
    },

    methods: {
        setIsActive (state) {
            this.isActive = state.name === this.name
        },
    },
}
</script>
