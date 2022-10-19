<template>
    <section class="h-full flex justify-center items-center">
        <div class="p-4 rounded-lg bg-white shadow-sm shadow-gray-200">
            <form class="flex flex-col">
                <login-alert
                    v-if="error"
                    v-text="error"
                />
                <input
                    v-model="user.email"
                    type="email"
                    class="w-80 my-2 px-2 py-1 rounded-md border placeholder:text-gray-300"
                    placeholder="Email"
                />
                <input
                    v-model="user.password"
                    type="password"
                    class="w-80 my-2 px-2 py-1 rounded-md border placeholder:text-gray-300"
                    placeholder="Password"
                />

                <button
                    @click.prevent="validate"
                    type="submit"
                    class="mt-2 px-2 py-1 rounded-md bg-blue-400 w-32 text-white font-semibold"
                >
                    Login
                </button>
            </form>
        </div>
    </section>
</template>

<script>
import axios from 'axios'
import LoginAlert from './LoginAlert.vue'
import { useUserStore, } from './../../stores/user.js'

export default {

    components: {
        LoginAlert,
    },

    setup () {
        const userStore = useUserStore()
    },

    data () {
        return {
            user: {
                email: '',
                password: '',
            },

            error: null,
        }
    },

    methods: {
        validate () {
            if (this.user.email === '') {
                this.error = 'Email can not be empty.'
                return
            }

            if (!this.user.email.includes('@')) {
                this.error = 'Invalid email.'
                return
            }

            if (this.user.password === '') {
                this.error = 'Password can not be empty.'
                return
            }

            this.postLogin()
        },

        postLogin () {
            if (!this.validate) {
                return this.validate
            }

            axios.post('auth/login', { 'email': this.user.email,  'password': this.user.password, })
                .then((res) => {
                    this.error = null

                    this.$router.push({ name: 'home', })
                })
                .catch((res) => {
                    this.error = res.response.data.message
                })

            this.user.password = ''
        },
    },

    computed: {
    },
}
</script>
