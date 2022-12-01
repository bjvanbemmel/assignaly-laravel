<template>
    <div class="p-6 bg-gray-50 border">
        <div class="flex justify-between">
            <h1 class="text-lg font-bold"> {{ assignment.title }} </h1>
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
                    :users="assignment.users"
                    size="sm"
                    hover
                    :amount="users.overflowUsers.length"
                    class="z-10"
                />
            </div>
        </div>
        <div class="border-t border-gray-300 mt-2 pt-2 text-sm">
            <h3> <span class="font-semibold"> Owner: </span> {{ assignment.owner.name }} </h3>
            <h3> <span class="font-semibold"> Due at: </span> {{ assignment.due_at }} </h3>
        </div>
    </div>
</template>

<script>
import UserIcon from './../components/UserIcon.vue'
import UserIconOverflow from './../components/UserIconOverflow.vue'

export default {

    components: {
        UserIcon,
        UserIconOverflow,
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
}
</script>
