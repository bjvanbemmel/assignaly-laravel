<template>
    <nav
        class="fixed items-center bg-zinc-900 shadow-lg shadow-zinc-900 text-white flex justify-between p-2 border-b border-zinc-700 w-full h-max z-40"
    >
        <div
            class="flex items-center space-x-8 w-96"
        >
            <router-link
                :to="{ name: 'dashboard' }"
                class="mr-12"
            >
                <assignaly-logo
                    class="h-8 fill-zinc-50"
                />
            </router-link>

            <button
                class="absolute opacity-0 -z-50 focus:z-40 focus:static focus:opacity-100 border-2 border-zinc-300 rounded-md px-2"
                @click.stop="skipNavigation"
            >
                Skip navigation
            </button>

            <div
                @click.stop=""
            >
                <dropdown-toggler
                    :name="dropdowns.assignments.name"
                >
                    <button
                        class="flex items-center space-x-1 hover:underline"
                    >
                        <p>Assignments</p>
                        <hero-icon
                            name="ChevronDown"
                            class="h-4"
                            variant="mini"
                        />
                    </button>
                </dropdown-toggler>
                <navigation-link-dropdown
                    :name="dropdowns.assignments.name"
                    @update:active="(active) => checkActiveAndFetchAssignmentsIfTrue(active)"
                >
                    <div
                        class="pb-2 mb-2 border-b border-b-zinc-700"
                        v-if="dropdowns.assignments.active"
                    >
                        <router-link
                            :to="{ name: 'assignments.index' }"
                            class="flex items-center space-x-2 p-2 border rounded-t-md hover:text-zinc-300 border-zinc-600"
                            :class="user.role.level >= 2 ? 'rounded-t-md' : 'rounded-md'"
                        >
                            <hero-icon
                                name="PuzzlePiece"
                                class="h-5"
                                variant="mini"
                            />
                            <p>View all assignments</p>
                        </router-link>
                        <router-link
                            v-if="user.role.level >= 2"
                            :to="{ name: 'assignments.create' }"
                            class="flex items-center space-x-2 p-2 border rounded-b-md border-t-0 hover:text-zinc-300 border-zinc-600"
                        >
                            <hero-icon
                                name="Plus"
                                class="h-5"
                                variant="mini"
                            />
                            <p>Create new assignment</p>
                        </router-link>
                    </div>
                    <div
                        class="flex flex-col"
                        v-if="dropdowns.assignments.active"
                    >
                        <hero-icon
                            v-if="dropdowns.assignments.loading"
                            name="ArrowPath"
                            class="h-8 w-8 mx-auto opacity-40 animate-spin"
                        />
                        <router-link
                            v-else
                            v-for="assignment, key in latestAssignments"
                            :to="{ name: 'assignments.detail', params: { id: assignment.id } }"
                            :key="key"
                            class="flex w-full justify-between first:rounded-t-md last:rounded-b-md p-4 border border-b-0 last:border-b border-zinc-600 hover:text-zinc-300"
                        >
                            <p class="truncate w-56">{{ assignment.title }}</p>
                            <assignment-status
                                :status="assignment.status"
                            />
                        </router-link>
                    </div>
                </navigation-link-dropdown>
            </div>
        </div>

        <div>
            <global-search />
        </div>

        <div
            class="w-96 flex justify-end"
        >
            <div
                @click.stop
            >
                <dropdown-toggler
                    :name="dropdowns.user.name"
                >
                    <button>
                        <user-icon
                            :user="user"
                            size="sm"
                            class="hover:cursor-pointer"
                        />
                    </button>
                </dropdown-toggler>
                <user-dropdown-menu
                    :user="user"
                    :name="dropdowns.user.name"
                />
            </div>
        </div>
    </nav>
</template>

<script>
import AssignalyLogo from './../AssignalyLogo.vue'
import UserIcon from './../UserIcon.vue'
import DropdownToggler from './../DropdownToggler.vue'
import UserDropdownMenu from './UserDropdownMenu.vue'
import NavigationLinkDropdown from './NavigationLinkDropdown.vue'
import AssignmentStatus from './../Assignments/AssignmentStatus.vue'
import HeroIcon from './../HeroIcon.vue'
import GlobalSearch from './GlobalSearch.vue'
import { useUserStore, } from './../../stores/user.js'
import { useDropdownStore, } from './../../stores/dropdown.js'
import axios from 'axios'

export default {

    components: {
        AssignalyLogo,
        UserIcon,
        DropdownToggler,
        UserDropdownMenu,
        NavigationLinkDropdown,
        AssignmentStatus,
        HeroIcon,
        GlobalSearch,
    },

    data () {
        return {
            dropdowns: {
                user: {
                    active: false,
                    name: 'nav-user-dropdown',
                },

                assignments: {
                    loading: false,
                    active: false,
                    name: 'nav-assignments-dropdown',
                },

                classrooms: {
                    active: false,
                    name: 'nav-classrooms-dropdown',
                },
            },

            routes: [
                {
                    name: 'assignments.index',
                    label: 'Assignments',
                },
                {
                    name: 'classrooms.index',
                    label: 'Classrooms',
                },
            ],

            latestAssignments: {},
        }
    },

    computed: {
        user () {
            return useUserStore().getData
        },
    },

    methods: {
        setAssignmentDropdownActiveOnFocus () {
            useDropdownStore().setName(this.dropdowns.assignments.name)
        },

        checkActiveAndFetchAssignmentsIfTrue (active) {
            if (active) {
                this.fetchLatestAssignments()
            }

            this.dropdowns.assignments.active = active
        },

        fetchLatestAssignments () {
            this.dropdowns.assignments.loading = true

            axios.get('/assignments/latest')
                .then((res) => {
                    this.latestAssignments = res.data.data
                    this.dropdowns.assignments.loading = false
                })
        },

        closeAllDropdownsOnClick () {
            useDropdownStore().setName('')
        },

        skipNavigation () {
            useDropdownStore().setName('')
            this.$root.$refs.mainWrapperRouterView.focus()
        },
    },
}
</script>
