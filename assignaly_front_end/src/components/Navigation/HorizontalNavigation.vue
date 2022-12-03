<template>
    <div
        class="fixed bg-zinc-900 shadow-lg shadow-zinc-900 text-white flex justify-between p-3 border-b border-zinc-700 w-full h-max z-40"
    >
        <div
            class="flex items-center space-x-8"
        >
            <router-link
                :to="{ name: 'dashboard' }"
                class="mr-12"
            >
                <assignaly-logo
                    class="h-8 fill-zinc-50"
                />
            </router-link>

            <div
                @click.stop=""
            >
                <dropdown-toggler
                    :name="dropdowns.assignments.name"
                    @update="(active) => checkActiveAndFetchAssignmentsIsTrue(active)"
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
                >
                    <div
                        class="pb-2 mb-2 border-b border-b-zinc-700"
                    >
                        <router-link
                            :to="{ name: 'assignments.index' }"
                            class="rounded-md flex items-center space-x-2 p-2 border hover:text-zinc-300 border-zinc-600"
                        >
                            <hero-icon
                                name="PuzzlePiece"
                                class="h-5"
                                variant="mini"
                            />
                            <p>View all assignments</p>
                        </router-link>
                    </div>
                    <div
                        class="flex flex-col"
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

        <div
            @click.stop=""
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
</template>

<script>
import AssignalyLogo from './../AssignalyLogo.vue'
import UserIcon from './../UserIcon.vue'
import DropdownToggler from './../DropdownToggler.vue'
import UserDropdownMenu from './UserDropdownMenu.vue'
import NavigationLinkDropdown from './NavigationLinkDropdown.vue'
import AssignmentStatus from './../Assignments/AssignmentStatus.vue'
import HeroIcon from './../HeroIcon.vue'
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
        checkActiveAndFetchAssignmentsIsTrue (active) {
            if (active) {
                this.fetchLatestAssignments()
            }
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
    },
}
</script>
