<template>
    <button
        @click.stop="modal.active = true"
        :class="getSize"
        class="z-10 select-none bg-zinc-800 border border-zinc-500 text-zinc-400 aspect-square rounded-full flex justify-center items-center font-bold"
        title="View all users"
    >
        +{{ amount ?? users.length }}
    </button>

    <modal
        :active="modal.active"
        @close="() => toggleModal()"
        @click.stop="() => toggleModal()"
    >
        <template v-slot:title>
            Users
        </template>

        <template v-slot:desc>
            All of the users this assignment is assigned to.
        </template>

        <template v-slot:content>
            <div
                class="flex flex-col origin-top space-y-2 h-80 overflow-y-scroll"
            >
                <div
                    v-for="user, key in users"
                    :key="key"
                    class="flex relative space-x-2 bg-zinc-800 border border-zinc-600 p-1.5"
                >
                    <user-icon
                        :user="user"
                        size="sm"
                        class="w-8"
                    />
                    <div
                        class="flex-col text-left"
                    >
                        <p class="text-sm"> {{ user.name }} </p>
                        <p class="text-xs text-zinc-400"> {{ this.role(user) }} </p>
                    </div>
                    <a
                        :href="`mailto: ${user.email}`"
                        class="absolute underline hover:no-underline right-1.5 bottom-1.5 h-fit text-blue-400 text-xs"
                    >
                        {{ user.email }}
                    </a>
                </div>
            </div>
        </template>

        <template v-slot:actions>
            <default-button
                text="Close"
                @click="() => toggleModal()"
            />
        </template>

    </modal>
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

        toggleModal () {
            this.modal.active = !this.modal.active
        },
    },
}
</script>
