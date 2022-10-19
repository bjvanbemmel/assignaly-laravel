<template>
    <ul class="flex flex-col w-48">
        <li
            v-for="link, i in routes"
            :key="i"
            class="p-2 m-2 bg-gray-200 rounded-lg"
        >
            <router-link
                :to="{ name: link.route }"
                v-html="link.label"
            />
        </li>
    </ul>
</template>

<script>
export default {

    name: 'VeticalNavigation',

    data () {
        return {
            routes: [
                {
                    route: 'home',
                    label: 'Home',
                },
                {
                    route: 'assignments',
                    label: 'Assignments',
                },
                {
                    route: 'classrooms',
                    label: 'Classrooms',
                },
                {
                    route: 'profile',
                    label: 'Profile',
                },
            ],
        }
    },
}
</script>
