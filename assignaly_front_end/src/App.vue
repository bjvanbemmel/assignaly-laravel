<template>
    <section
        v-if="loggedIn"
        class="flex h-full w-full"
    >
        <horizontal-navigation />
        <div
            class="pt-12"
        >
        </div>
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
import HorizontalNavigation from './components/Navigation/HorizontalNavigation.vue'

export default {

    components: {
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
