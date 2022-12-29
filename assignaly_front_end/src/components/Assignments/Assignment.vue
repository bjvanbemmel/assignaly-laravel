<template>
    <button class="rounded-md p-6 bg-zinc-800 text-start border border-zinc-800 shadow-md shadow-black/30">
        <div class="flex justify-between">
            <h1 class="text-lg font-bold w-[32rem] truncate"> {{ assignment.title }} </h1>
            <div
                @click.stop
                class="flex space-x-[-0.33rem]"
            >
                <user-icon
                    v-for="user, i in users.slicedUsers"
                    :key="i"
                    size="sm"
                    :user="user"
                    class="ml-2"
                    hover
                />
                <user-icon-overflow
                    v-if="users.overflowUsers.length > 0"
                    :users="assignment.users"
                    size="sm"
                    hover
                    :amount="users.overflowUsers.length"
                />
            </div>
        </div>
        <div class="border-t border-zinc-600 mt-2 pt-2 text-sm">
            <h3> <span class="font-semibold"> Owner: </span> {{ assignment.owner.name }} </h3>
            <h3> <span class="font-semibold"> Due at: </span> {{ assignment.due_at }} </h3>
        </div>

        <assignment-status
            :status="assignment.status"
            class="mt-2"
        />
    </button>
</template>

<script>
import UserIcon from './../../components/UserIcon.vue'
import UserIconOverflow from './../../components/UserIconOverflow.vue'
import AssignmentStatus from './AssignmentStatus.vue'

export default {

    components: {
        UserIcon,
        UserIconOverflow,
        AssignmentStatus,
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
    },
}
</script>
