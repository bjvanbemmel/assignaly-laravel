<template>
    <div
        @click.stop="toggleDropdown()"
    >
        <slot/>
    </div>
</template>

<script>
import { useDropdownStore, } from './../stores/dropdown.js'

export default {

    props: {
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

    created () {
        this.validatedName = this.name ?? 'dropdown-' + Math.floor(Math.random() * 100000)
    },

    methods: {
        toggleDropdown () {
            if (useDropdownStore().getName === this.validatedName) {
                useDropdownStore().setName('')
            } else {
                useDropdownStore().setName(this.validatedName)
            }

            this.$emit('update', useDropdownStore().getName === this.validatedName)

            if (useDropdownStore().getName === this.validatedName) {
                document.addEventListener('click', this.disableDropdownFromOutsideClick)
            } else {
                document.removeEventListener('click', this.disableDropdownFromOutsideClick)
            }
        },

        disableDropdownFromOutsideClick () {
            console.log('toggler')
            useDropdownStore().setName('')
            this.$emit('update', false)

            document.removeEventListener('click', this.disableDropdownFromOutsideClick)
        },
    },
}
</script>
