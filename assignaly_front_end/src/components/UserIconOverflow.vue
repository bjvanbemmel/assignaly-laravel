<template>
    <button
        @click.stop="modal.active = true"
        :class="getSize + ' ' + getColorTheme"
        class="select-none aspect-square rounded-full flex justify-center items-center font-bold border"
        title="View all users"
    >
        +{{ amount ?? users.length }}
        <modal
            :active="modal.active"
            title=""
            @close="() => modal.active = false"
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
                        class="flex relative space-x-2 bg-gray-50 border p-1.5"
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
                            <p class="text-xs text-gray-500"> {{ this.role(user) }} </p>
                        </div>
                        <a
                            :href="`mailto: ${user.email}`"
                            class="absolute right-1.5 bottom-1.5 h-fit text-blue-500 text-xs"
                        >
                            {{ user.email }}
                        </a>
                    </div>
                </div>
            </template>

            <template v-slot:actions>
                <default-button
                    text="Close"
                />
            </template>

        </modal>
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

        getColorTheme () {
            if (this.dark) {
                return 'bg-gray-600 text-white border-gray-700'
            }

            return 'bg-white text-gray-700 border-gray-300'
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
