import { defineStore, } from 'pinia'

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
