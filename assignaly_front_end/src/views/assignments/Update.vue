<template>
    <div class="flex flex-col space-y-4">
        <page-title>
            Update assignment:
        </page-title>

        <div class="grid gap-4 sm:grid-cols-2">
            <form
                @submit.prevent="updateAssignment"
                name="create-form"
                class="flex flex-col gap-2"
            >
                <default-text-input
                    @update="(input) => this.title = input"
                    :value="assignment.title"
                    name="create-title"
                    placeholder="Title"
                    required
                />
                <default-text-input
                    @update="(input) => this.description = input"
                    :value="assignment.description"
                    name="create-description"
                    rows="6"
                    type="textarea"
                    placeholder="Description"
                    required
                />
                <default-text-input
                    @update="(input) => this.due_at = input"
                    :value="dueDate"
                    name="create-due"
                    placeholder="Due at"
                    type="date"
                    required
                />

                <section class="relative mt-4 flex flex-col gap-2">
                    <dropdown-toggler
                        name="create-user-search"
                        @update="(active) => users.active = active"
                    >
                        <default-text-input
                            @keypress.stop
                            @input="(input) => this.users.query = input.target.value"
                            @focus.stop="users.active = true"
                            name="create-user-search"
                            class="w-full"
                            placeholder="Users"
                            autocomplete="off"
                        />
                        <div
                            v-if="users.active && users.results.length !== 0 && users.query !== ''"
                            class="absolute top-10 rounded-md bg-zinc-800 z-20"
                        >
                            <button
                                v-for="user, key in users.results"
                                @click.prevent="addUserToUsers(user)"
                                :key="key"
                                class="flex w-full gap-4 items-center first:rounded-t-md last:rounded-b-md p-2 border border-b-0 last:border-b border-zinc-600 hover:text-zinc-400"
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
                            </button>
                        </div>
                    </dropdown-toggler>
                    <div
                        class="flex flex-col bg-zinc-800 rounded-md border border-zinc-600 p-2"
                    >
                        <button
                            v-for="user, key in users.selected"
                            @click.prevent="removeUserFromUsers(user)"
                            :key="key"
                            class="flex w-full gap-4 items-center first:rounded-t-md last:rounded-b-md p-2 border border-b-0 last:border-b border-zinc-600 hover:text-zinc-400"
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
                        </button>
                    </div>
                </section>

                <default-button
                    class="w-32 mt-4 ml-auto"
                    text="Update"
                />
            </form>
        </div>
    </div>
</template>

<script>
import HeroIcon from '../../components/HeroIcon.vue'
import UserIcon from '../../components/UserIcon.vue'
import PageTitle from '../../components/PageTitle.vue'
import DefaultDropdown from '../../components/FormInputs/DefaultDropdown.vue'
import DefaultButton from '../../components/FormInputs/DefaultButton.vue'
import DefaultTextInput from '../../components/FormInputs/DefaultTextInput.vue'
import DropdownToggler from '../../components/DropdownToggler.vue'
import axios from 'axios'
import { useUserStore, } from '../../stores/user.js'
import { useDropdownStore, } from '../../stores/dropdown.js'
import { debounce, } from 'lodash'

export default {

    components: {
        HeroIcon,
        UserIcon,
        PageTitle,
        DefaultDropdown,
        DefaultButton,
        DefaultTextInput,
        DropdownToggler,
    },

    created () {
        if (useUserStore().getData.id !== this.assignment.owner_id) {
            this.$router.push({ name: 'assignments.index' })
        }

        this.fetchData()
    },

    data () {
        return {
            title: '',
            description: '',
            assignment: {},
            users: {
                active: false,
                query: '',
                results: [],
                selected: [],
            },

            due_at: null,
        }
    },

    methods: {
        fetchData () {
            axios.get(`/assignments/${this.$route.params.id}`)
                .then((res) => {
                    this.assignment = res.data.data
                    this.title = this.assignment.title
                    this.description = this.assignment.description
                    this.due_at = this.assignment.due_at

                    this.users.selected = res.data.data.users
                })
                .catch((res) => {
                    console.log(res)
                })
        },

        updateAssignment () {
            axios.put(`/assignments/${this.$route.params.id}`, {
                owner_id: useUserStore().getData.id,
                title: this.title,
                description: this.description,
                users: this.users.selected.map((user) => user.id),
                due_at: this.due_at,
            })
                .then((res) => this.$router.push({ name: 'assignments.detail', params: {id: res.data.data.id}, }))
        },

        queryUsers:  debounce (function (query) {
            if (query === '') {
                this.users.results = []
                return
            }

            axios.get(`/users?query=${query}`)
                .then((res) => {
                    this.users.results = res.data.data
                })
        }),

        addUserToUsers (user) {
            if (this.users.selected.includes(user)) {
                return
            }

            this.users.selected.push(user)
        },

        removeUserFromUsers (user) {
            this.users.selected = this.users.selected.filter((u) => {
                return u.id !== user.id
            })
        },

        getHighlightedText (text) {
            return text.replace(new RegExp(this.users.query, 'gi'), (match) => {
                return '<span class="font-bold">' + match + '</span>'
            })
        },

        setName: debounce(function (name) {
            if (useDropdownStore().getName === name) {
                return
            }

            useDropdownStore().setName(name)
        }, 100),
    },

    computed: {
        dueDate () {
            let date = new Date(this.assignment.due_at)
            return date.toLocaleDateString('en-CA')
        },
    },

    watch: {
        '$route.params' () {
            this.fetchData()
            useDropdownStore().setName('')
        },

        'users.query' (to, from) {
            if (to === from) {
                return
            }

            if (to === '') {
                return
            }

            this.queryUsers(to)
            this.setName('create-user-search')
        },
    },
}
</script>
