<template>
    <section
        v-if="loggedIn"
        class="relative min-h-screen flex flex-col"
    >
        <horizontal-navigation />

        <main
            class="focus:outline-none pt-20 pb-6 px-6 w-full"
            tabindex="-1"
            ref="mainWrapperRouterView"
        >
            <router-view
                class="text-zinc-200"
            />
        </main>

        <footer
            class="text-zinc-600 mt-auto bg-zinc-900 border-t border-zinc-700 w-full p-6"
        >
            <p>
                &copy; Beau Jean van Bemmel, {{ getYear }}
            </p>
            <ul
                class="flex space-x-4 mt-4"
            >
                <li
                    v-for="route, key in footer.routes"
                    :key="key"
                >
                    <router-link
                        class="text-zinc-500 underline hover:no-underline"
                        :to="{ name: route.name }"
                    >
                        {{ route.label }}
                    </router-link>
                </li>
            </ul>
        </footer>
    </section>

    <section
        v-else
        class="text-zinc-200"
    >
        <router-view />
    </section>
</template>

<script>
import { useUserStore, } from './stores/user.js'
import HorizontalNavigation from './components/Navigation/HorizontalNavigation.vue'

export default {

    components: {
        HorizontalNavigation,
    },

    data () {
        return {
            footer: {
                routes: [
                    {
                        name: 'dashboard',
                        label: 'Dashboard',
                    },
                    {
                        name: 'assignments.index',
                        label: 'Assignments',
                    },
                    {
                        name: 'classrooms.index',
                        label: 'Classrooms',
                    },
                    {
                        name: '404',
                        label: 'Privacy policy',
                    },
                ],
            },
        }
    },

    computed: {
        loggedIn () {
            const user = useUserStore()

            return user.getToken !== null
        },

        getYear () {
            const DATE = new Date()
            const CURRENT_YEAR = DATE.getFullYear()
            const ORIGINAL_YEAR_MADE = 2022

            return CURRENT_YEAR === ORIGINAL_YEAR_MADE ? CURRENT_YEAR : ORIGINAL_YEAR_MADE + ' - ' + CURRENT_YEAR
        },
    },
}
</script>
