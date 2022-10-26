<template>
    <div class="flex float-right w-96 flex-col items-end px-12 justify-end h-full">
        <section class="w-full">
            <ul class="my-8 w-full flex-col ">
                <li
                    v-for="route in routes"
                    :key="route"
                >
                    <router-link
                        :to="route"
                        class="flex w-fit my-4 text-lg hover:underline"
                    >
                        <hero-icon
                            v-if="route.meta.icon !== undefined"
                            :name="route.meta.icon"
                            class="mr-2 h-8"
                        />
                        {{ route.meta.label }}
                    </router-link>
                </li>
            </ul>
        </section>

        <section class="border-t w-full border-gray-400 py-4 flex items-center">
            <div class="w-12 aspect-square bg-blue-500 rounded-full flex justify-center items-center font-bold text-white text-lg">
                {{ initials }}
            </div>
            <div class="flex flex-col">
                <span class="ml-4 text-xl truncate">
                    {{ user.name }}
                </span>
                <span class="ml-4 text-sm text-gray-500 truncate">
                    {{ role }}
                </span>
            </div>
        </section>
    </div>
</template>

<script>
import { useUserStore, } from '../../stores/user.js'
import HeroIcon from '../../components/HeroIcon.vue'

export default {

    components: {
        HeroIcon,
    },

    data () {
        return {
            user: {},
        }
    },

    created () {
        const user = useUserStore()

        this.user = user.getData
    },

    computed: {
        routes () {
            return this.$router.getRoutes().filter((route) => route.meta.indexed === true)
        },

        initials () {
            const split = this.user.name.split(' ')

            if (split.length > 1) {
                return `${split[0][0]}${split[split.length - 1][0]}`
            }

            return split[0][0]
        },

        role () {
            const roles = {
                1: 'Student',
                2: 'Teacher',
                3: 'Administrator',
            }

            return roles[this.user.role_id]
        },
    },
}
</script>
