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
        dropdown: {
            type: Object,
            required: true,
        },
    },

    created () {
        this.dropdown.name = this.dropdown.name ?? 'dropdown-' + Math.floor(Math.random() * 100000)
    },

    methods: {
        toggleDropdown () {
            if (useDropdownStore().getName === this.dropdown.name) {
                useDropdownStore().setName('')
            } else {
                useDropdownStore().setName(this.dropdown.name)
            }

            this.$emit('update', useDropdownStore().getName === this.dropdown.name)

            if (useDropdownStore().getName === this.dropdown.name) {
                document.addEventListener('click', this.disableDropdownFromOutsideClick)
            } else {
                document.removeEventListener('click', this.disableDropdownFromOutsideClick)
            }
        },

        disableDropdownFromOutsideClick () {
            useDropdownStore().setName('')
            this.$emit('update', false)

            document.removeEventListener('click', this.disableDropdownFromOutsideClick)
        },
    },
}
</script>
