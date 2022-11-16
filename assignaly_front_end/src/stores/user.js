import { defineStore, } from 'pinia'
import axios from 'axios'

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            token: null,
            data: null,
        }
    },

    persist: true,

    actions: {
        setToken (token) {
            this.token = token
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        },

        setData (data) {
            this.data = data
        },
    },

    getters: {
        getToken: (state) => state.token,
        getData: (state) => state.data,
    },
})
