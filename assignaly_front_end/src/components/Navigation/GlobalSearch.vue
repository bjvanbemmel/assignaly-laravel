<template>
    <div
        class="relative flex flex-col"
    >
        <dropdown-toggler
            :name="name"
        >
            <input
                v-model="query"
                ref="globalSearch"
                maxlength="255"
                type="text"
                placeholder="Click me or press '/' to search..."
                class="rounded-md placeholder:text-zinc-500 border border-zinc-600 bg-zinc-800 px-2 py-1.5 w-96 focus:outline-none  ring-zinc-600 focus:ring-2"
                @keypress.stop
                @keyup.esc="closeOnEscape"
            />
            <div
                class="absolute w-96 top-12 rounded-md p-4 bg-zinc-800 border-zinc-600 border"
                v-if="active && query !== ''"
                @click.stop
            >
                <hero-icon
                    v-if="!results"
                    name="ArrowPath"
                    class="h-8 w-8 mx-auto opacity-40 animate-spin"
                />
                <div
                    v-else
                >
                    <div
                        v-if="results.users.length === 0 && results.assignments.length === 0"
                    >
                        We couldn't find anything...
                    </div>
                    <div
                        v-else
                    >
                        <router-link
                            v-for="user, key in results.users"
                            @click.stop="closeOnEscape"
                            @keypress.enter="closeOnEscape"
                            :key="key"
                            :to="{ name: '404' }"
                            class="flex w-full gap-4 items-center first:rounded-t-md last:rounded-b-md p-4 border border-b-0 last:border-b border-zinc-600 hover:text-zinc-300"
                        >
                            <user-icon
                                :user="user"
                                class="hover:cursor-pointer"
                                size="sm"
                            />
                            <p
                                v-html="getHighlightedText(user.name)"
                                class="truncate w-64"
                            ></p>
                        </router-link>
                        <router-link
                            v-for="assignment, key in results.assignments"
                            @click.stop="closeOnEscape"
                            :key="key"
                            :to="{ name: 'assignments.detail', params: { id: assignment.id } }"
                            class="flex w-full gap-4 items-center first:rounded-t-md last:rounded-b-md p-4 border border-b-0 last:border-b border-zinc-600 hover:text-zinc-300"
                        >
                            <p
                                v-html="getHighlightedText(assignment.title)"
                                class="truncate w-64"
                            ></p>
                        </router-link>
                    </div>
                </div>
            </div>
        </dropdown-toggler>
    </div>
</template>

<script>
import HeroIcon from '../HeroIcon.vue'
import UserIcon from '../UserIcon.vue'
import DropdownToggler from '../DropdownToggler.vue'
import { debounce, } from 'lodash'
import axios from 'axios'
import { useDropdownStore, } from '../../stores/dropdown.js'

export default {

    components: {
        HeroIcon,
        UserIcon,
        DropdownToggler,
    },

    mounted () {
        document.addEventListener('keypress', this.openOnSlash)

        const dropdownStore = useDropdownStore()

        dropdownStore.$subscribe((_, state) => this.setActive(state))
    },

    data () {
        return {
            active: false,
            name: 'global-search',
            query: '',
            results: null,
            tabIndex: 1,
        }
    },

    methods: {
        setName: debounce(function (name) {
            if (useDropdownStore().getName === this.name) {
                return
            }

            useDropdownStore().setName(name)
        }, 100),

        setActive (state) {
            this.active = state.name === this.name
        },

        getHighlightedText (text) {
            return text.replace(new RegExp(this.query, 'gi'), (match) => {
                return '<span class="font-bold">' + match + '</span>'
            })
        },

        openOnSlash (event) {
            if (event.key === '/') {
                event.preventDefault()
                this.$refs.globalSearch.focus()
            }
        },

        closeOnEscape () {
            this.$refs.globalSearch.blur()
            useDropdownStore().setName('')
        },

        submitQuery: debounce(function () {
            this.results = null

            if (!/[a-z0-9]/gi.test(this.query)) {
                return
            }

            if (this.query.length > 255) {
                return
            }

            axios.get(`/search?query=${this.query}`)
                .then((res) => {
                    this.results = res.data
                })
        }, 300),
    },

    watch: {
        query (to) {
            if (this.query !== '') {
                this.submitQuery()
                this.setName(this.name)
            }
        },
    },
}
</script>
