<template>
    <section
        v-if="loggedIn"
        class="flex h-full w-full"
    >
        <vertical-navigation />
        <main class="ml-96 w-full">
            <horizontal-navigation />
            <div
                class="mt-14 p-4"
            >
                <router-view 
                    class="min-w-[32rem] max-w-[64rem]"
                />
            </div>
        </main>
    </section>

    <section
        v-else
        class="h-full overflow-y-scroll"
    >
        <router-view />
    </section>
</template>

<script>
import { useUserStore, } from './stores/user.js'
import VerticalNavigation from './components/Navigation/VerticalNavigation.vue'
import HorizontalNavigation from './components/Navigation/HorizontalNavigation.vue'

export default {

    components: {
        VerticalNavigation,
        HorizontalNavigation,
    },

    computed: {
        loggedIn () {
            const user = useUserStore()

            return user.getToken !== null
        },
    },
}
</script>
