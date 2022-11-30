<template>
    <div class="w-full h-full flex justify-center items-center">
        <form
            class="w-96 bg-gray-500 flex flex-col p-8"
            @submit.prevent="login"
        >
            <input
                class="my-4"
                type="email"
                name="email"
                v-model="email"
            >
            <input
                class="my-4"
                type="password"
                name="password"
                v-model="password"
            >

            <input
                type="submit"
                value="Login"
            >
        </form>
    </div>
</template>

<script>
import axios from 'axios'
import { useUserStore, } from '../../stores/user.js'

export default {

    data () {
        return {
            email: '',
            password: '',
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
                    this.$router.push({ name: 'assignments.index', })
                })
                .catch((res) => {
                    console.log(res)
                })
        },
    },
}
</script>
