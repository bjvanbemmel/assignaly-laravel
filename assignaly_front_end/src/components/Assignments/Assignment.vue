<template>
    <div class="p-6 bg-gray-50 border">
        <div class="flex justify-between">
            <h1 class="text-lg font-bold w-[32rem] truncate"> {{ assignment.title }} </h1>
            <div class="flex space-x-[-0.33rem]">
                <user-icon
                    v-for="user, i in users.slicedUsers"
                    :key="i"
                    size="sm"
                    :user="user"
                    hover
                    class="outline outline-1"
                />
                <user-icon-overflow
                    v-if="users.overflowUsers.length > 0"
                    :users="assignment.users"
                    size="sm"
                    hover
                    :amount="users.overflowUsers.length"
                    class="z-10"
                    @click="toggleModal"
                />
            </div>
        </div>
        <div class="border-t border-gray-300 mt-2 pt-2 text-sm">
            <h3> <span class="font-semibold"> Owner: </span> {{ assignment.owner.name }} </h3>
            <h3> <span class="font-semibold"> Due at: </span> {{ assignment.due_at }} </h3>
        </div>

        <modal
            :active="modal.active"
            @close="() => toggleModal()"
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
                        v-for="user, key in assignment.users"
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
                            class="absolute underline hover:no-underline right-1.5 bottom-1.5 h-fit text-blue-500 text-xs"
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
    </div>
</template>

<script>
import UserIcon from './../../components/UserIcon.vue'
import UserIconOverflow from './../../components/UserIconOverflow.vue'
import DefaultButton from './../../components/FormInputs/DefaultButton.vue'
import Modal from './../../components/Modal.vue'

export default {

    components: {
        UserIcon,
        UserIconOverflow,
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
        assignment: {},
    },

    computed: {
        users () {
            const slicedUsers = this.assignment.users.slice(0, 5)
            const overflowUsers = this.assignment.users.slice(5, this.assignment.users.length - 1)

            return {
                slicedUsers: slicedUsers,
                overflowUsers: overflowUsers,
            }
        },
    },

    methods: {
        role (user) {
            let role = user.role.name

            return role.charAt(0).toUpperCase() + role.slice(1)
        },

        toggleModal () {
            this.modal.active = !this.modal.active

            if (this.modal.active) {
                document.querySelector('main').style.overflowY = 'hidden'
            } else {
                document.querySelector('main').style.overflowY = 'scroll'
            }
        },
    },
}
</script>
