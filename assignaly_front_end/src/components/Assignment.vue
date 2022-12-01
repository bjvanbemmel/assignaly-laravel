<template>
    <div class="p-6 bg-gray-50 border">
        <div class="flex justify-between">
            <h1 class="text-lg font-bold"> {{ assignment.title }} </h1>
            <div class="flex space-x-[-0.33rem]">
                <user-icon
                    v-for="user, i in assignment.users"
                    :key="i"
                    size="sm"
                    :user="user"
                    hover
                    class="outline outline-1"
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

export default {

    props: {
        assignment: {},
    },

    components: {
        UserIcon,
    },
}
</script>
