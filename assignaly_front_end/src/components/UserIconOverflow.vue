<template>
    <button
        @click.stop="modal.active = true"
        :class="getSize"
        class="select-none bg-zinc-800 border border-zinc-500 text-zinc-400 aspect-square rounded-full flex justify-center items-center font-bold"
        title="View all users"
    >
        +{{ amount ?? users.length }}
    </button>
</template>

<script>
import UserIcon from './UserIcon.vue'
import DefaultButton from './FormInputs/DefaultButton.vue'
import Modal from './Modal.vue'

export default {

    components: {
        UserIcon,
        DefaultButton,
        Modal,
    },

    data () {
        return {
            modal: {
                active: false,
            },
        }
    },

    props: {
        users: {
            type: Array, Object,
            required: true,
        },

        size: {
            type: String,
            default: 'md',
        },

        dark: {
            type: Boolean,
            default: false,
        },

        hover: {
            type: Boolean,
            default: false,
        },

        amount: {
            type: Number,
        },
    },

    computed: {
        getSize () {
            switch (this.size) {
                case 'sm':
                    return 'text-sm h-8'
                case 'md':
                    return 'text-md h-10'
                default:
                    return 'text-lg h-12'
            }
        },

        getTextSize () {
            return this.users.length >= 10 ? 'text-xs truncate' : 'text-sm'
        },
    },

    methods: {
        role (user) {
            let role = user.role.name

            return role.charAt(0).toUpperCase() + role.slice(1)
        },
    },
}
</script>
