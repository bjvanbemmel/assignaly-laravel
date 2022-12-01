<template>
    <div
        class="flex relative flex-col w-32"
        @click.prevent.stop=""
    >
        <button
            class="group h-full w-full text-center flex items-center border border-gray-300 bg-gray-50 text-sm hover:bg-gray-100"
            @click="toggleMenu"
        >
            <span
                class="py-1.5 px-4 w-full"
            >
                {{ getDefault }}
            </span>
            <span
                class="bg-gray-100 flex items-center justify-center border-l h-full group-hover:bg-gray-200"
            >
                <hero-icons
                    name="ChevronDown"
                    class="h-3 px-1"
                    :class="{ 'rotate-180': this.active }"
                />
            </span>
        </button>
        <div
            class="absolute top-8 max-h-48 overflow-y-scroll w-full bg-white border-x border-t"
            v-if="active"
        >
            <button
                v-for="option, i in options"
                :key="i"
                class="w-full hover:bg-gray-50 text-center py-2 px-4 min-w-max text-sm bg-white border-b"
                @click="emitOption(option)"
            >
                {{ option.label }}
            </button>
        </div>
    </div>
</template>

<script>
import HeroIcons from './../HeroIcon.vue'

export default {

    components: {
        HeroIcons,
    },

    data () {
        return {
            active: false,
        }
    },

    props: {
        options: {
            type: Array,
            required: true,
        },

        default: {
            type: Object, String, Number,
        },
    },

    computed: {
        getDefault () {
            return this.default ? this.default.label : this.options[0].label
        },
    },

    methods: {
        emitOption (option) {
            this.toggleMenu()
            this.$emit('update', option)
        },

        toggleMenu () {
            this.active = !this.active

            if (this.active) {
                document.addEventListener('click', this.toggleMenu)
            } else {
                document.removeEventListener('click', this.toggleMenu)
            }
        },
    },
}
</script>
