<template>
    <div
        :class="isActive ? 'h-max w-64 text-full py-2 border opacity-100' : 'h-0 w-0 text-[0%] py-0 opacity-0'"
        class="rounded-md absolute duration-100 px-2 transition-all flex flex-col overflow-hidden top-14 right-0 border-zinc-700 bg-zinc-800 shadow-md shadow-zinc-900"
    >
        <div
            v-if="isActive"
            class="flex items-center space-x-2 pb-2 mb-2 border-b border-b-zinc-700"
        >
            <user-icon
                :user="user"
                size="lg"
            />

            <div
                class="flex flex-col"
            >
                <p
                    class="text-lg"
                >
                    {{ user.name }}
                </p>
                <p
                    class="text-sm text-zinc-400"
                >
                    {{ user.role.public_name }}
                </p>
                <a
                    class="text-sm text-blue-400 underline hover:no-underline hover:cursor-pointer"
                    :href="'mailto:' + user.email"
                >
                    {{ user.email }}
                </a>
            </div>
        </div>
        <div
            v-if="isActive"
            class="flex flex-col"
        >
            <router-link
                class="flex first:rounded-t-md last:rounded-b-md items-center space-x-2 p-2 border hover:text-zinc-300 first:border-b-0 border-zinc-600"
                :to="{ name: 'account.settings', params: { tab: 'profile', }, }"
            >
                <hero-icon
                    class="h-5"
                    variant="mini"
                    name="Cog6Tooth"
                />
                <p>Account settings</p>
            </router-link>

            <button
                class="flex first:rounded-t-md last:rounded-b-md items-center space-x-2 p-2 border hover:text-zinc-300 first:border-b-0 border-zinc-600"
                @click.prevent="logout"
            >
                <hero-icon
                    class="h-5"
                    variant="mini"
                    name="ArrowRightOnRectangle"
                />
                <p>Logout</p>
            </button>
        </div>
    </div>
</template>

<script>
import UserIcon from './../UserIcon.vue'
import HeroIcon from './../HeroIcon.vue'
import { useDropdownStore, } from './../../stores/dropdown.js'
import { useUserStore, } from './../../stores/user.js'
import axios from 'axios'

export default {

    components: {
        UserIcon,
        HeroIcon,
    },

    mounted () {
        const dropdownStore = useDropdownStore()

        dropdownStore.$subscribe((_, state) => this.setIsActive(state))
    },

    data () {
        return {
            isActive: false,
        }
    },

    props: {
        user: {
            type: Object,
            required: true,
        },

        active: {
            type: Boolean,
            default: false,
        },

        name: {
            type: String,
            required: true,
        },
    },

    methods: {
        setIsActive (state) {
            this.isActive = state.name === this.name
        },

        logout () {
            axios.post('/auth/logout')
                .then(() => {
                    useUserStore().setToken(null)
                    this.$router.push('authentication.login')
                })
        },
    },

}
</script>
