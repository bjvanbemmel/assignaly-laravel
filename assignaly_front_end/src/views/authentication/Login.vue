<template>
    <div class="w-full h-screen flex flex-col space-y-4 justify-center items-center">
        <assignaly-logo-full-width
            class="h-16 fill-zinc-200"
        />
        <form
            class="w-96 bg-zinc-800/50 gap-4 rounded-md border-zinc-600 border shadow-md shadow-zinc-900 flex flex-col p-8"
            @submit.prevent="login"
        >
            <div
                v-if="error"
                class="text-red-100 font-semibold bg-red-800 border-red-600 border-2 rounded-md px-2 py-1"
            >
                {{ error }}
            </div>
            <default-text-input
                placeholder="E-mail"
                name="email"
                type="email"
                autocomplete="email"
                @update="(email) => this.email = email"
            />

            <default-text-input
                placeholder="Password"
                name="password"
                type="password"
                autocomplete="current-password"
                @update="(password) => this.password = password"
            />

            <default-button
                text="Login"
                class="mt-4"
            />
        </form>
    </div>
</template>

<script>
import AssignalyLogoFullWidth from './../../components/AssignalyLogoFullWidth.vue'
import DefaultButton from './../../components/FormInputs/DefaultButton.vue'
import DefaultTextInput from './../../components/FormInputs/DefaultTextInput.vue'
import axios from 'axios'
import { useUserStore, } from '../../stores/user.js'

export default {

    components: {
        AssignalyLogoFullWidth,
        DefaultButton,
        DefaultTextInput,
    },

    data () {
        return {
            email: '',
            password: '',
            error: null,
        }
    },

    methods: {
        login () {
            axios.post('/auth/login', {
                'email': this.email,
                'password': this.password,
            })
                .then((res) => {
                    const user = useUserStore()

                    user.setToken(res.data.token)
                    user.setData(res.data.user)
                    this.$router.push({ name: 'dashboard', })
                })
                .catch(() => {
                    this.error = 'Invalid credentials.'
                })
        },
    },
}
</script>
