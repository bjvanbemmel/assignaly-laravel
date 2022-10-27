<template>
    <section class="flex justify-center h-full">
        <section
            v-if="loggedIn"
            class="flex h-full"
        >
            <div class="w-1/4 min-w-[24rem]">
                <vertical-navigation />
            </div>
            <div class="lg:w-2/4 w-3/4 min-w-[50rem] max-w-[50rem] bg-white border-2 border-gray-200 flex flex-col">
                <horizontal-navigation />
                <router-view />
            </div>
        </section>

        <section
            v-else
            class="h-full"
        >
            <router-view />
        </section>
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
