import { defineStore, } from 'pinia'

export const useDropdownStore = defineStore('dropdown', {
    state: () => {
        return {
            name: '',
        }
    },

    persist: false,

    actions: {
        setName (name) {
            this.name = name
        },
    },

    getters: {
        getName: (state) => state.name,
    },
})
