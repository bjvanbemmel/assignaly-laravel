import { defineStore, } from 'pinia'

export const useDropdownStore = defineStore('dropdown', {
    state: () => {
        return {
            active: false,
            name: '',
        }
    },

    persist: false,

    actions: {
        setActive (active) {
            this.active = active
        },

        setName (name) {
            this.name = name
        },
    },

    getters: {
        getActive: (state) => state.active,
        getName: (state) => state.name,
    },
})
