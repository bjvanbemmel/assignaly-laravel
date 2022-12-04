<template>
    <div
        class="flex relative flex-col w-32"
        @click.prevent.stop=""
    >
        <dropdown-toggler
            :name="name"
        >
            <button
                class="rounded-md focus:outline-none ring-zinc-600 focus:ring-2 group h-full w-full text-center flex items-center border border-zinc-600 bg-zinc-800 text-sm hover:bg-zinc-800/30"
            >
                <span
                    class="py-1.5 px-4 w-full"
                >
                    {{ getDefault }}
                </span>
                <span
                    class="rounded-r-md flex items-center justify-center border-l border-zinc-600 h-full"
                >
                    <hero-icons
                        name="ChevronDown"
                        class="h-3 px-1"
                        :class="{ 'rotate-180': this.active }"
                    />
                </span>
            </button>
        </dropdown-toggler>
        <div
            class="absolute top-8 max-h-48 overflow-y-scroll w-full bg-zinc-900 shadow-md shadow-900/50 border-x border-t border-zinc-600 rounded-md"
            v-if="active"
        >
            <button
                v-for="option, i in options"
                :key="i"
                class="w-full hover:bg-zinc-800/30 focus:bg-zinc-800/30 text-center focus:outline-none py-2 px-4 min-w-max text-sm bg-zinc-800 border-b border-zinc-600 rounded-none first:rounded-t-md last:rounded-b-md"
                @click="emitOption(option)"
            >
                {{ option.label }}
            </button>
        </div>
    </div>
</template>

<script>
import HeroIcons from './../HeroIcon.vue'
import DropdownToggler from './../DropdownToggler.vue'
import { useDropdownStore, } from './../../stores/dropdown.js'

export default {

    components: {
        HeroIcons,
        DropdownToggler,
    },

    data () {
        return {
            active: false,
        }
    },

    mounted () {
        const dropdownStore = useDropdownStore()

        dropdownStore.$subscribe((_, state) => this.setActive(state))
    },

    props: {
        options: {
            type: Array,
            required: true,
        },

        default: {
            type: Object, String, Number,
        },

        name: {
            type: String,
            required: true,
        },
    },

    computed: {
        getDefault () {
            return this.default ? this.default.label : this.options[0].label
        },
    },

    methods: {
        setActive (state) {
            this.active = state.name === this.name
        },

        emitOption (option) {
            useDropdownStore().setName('')
            this.$emit('update', option)
        },
    },
}
</script>
