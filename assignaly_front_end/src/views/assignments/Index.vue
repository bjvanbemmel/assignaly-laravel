<template>
    <div class="flex flex-col space-y-4">
        <div class="flex justify-between">
            <page-title>
                Your assignments:
            </page-title>

            <section class="flex items-end h-16 gap-6">
                <!-- Filters -->
                <div
                    class="flex gap-6 h-full z-20"
                >
                    <section
                        class="h-10"
                    >
                        <label
                            class="text-sm"
                        >
                            Order by
                        </label>
                        <default-dropdown
                            :options="filters.orderBy.options"
                            :default="filters.orderBy.selected"
                            @update="(option) => filters.orderBy.selected = option"
                            name="assignments-filters-orderby"
                            class="h-10"
                        />
                    </section>

                    <section
                        class="h-10"
                    >
                        <label
                            class="text-sm"
                        >
                            Items per page
                        </label>
                        <default-dropdown
                            :options="filters.perPage.options"
                            :default="filters.perPage.selected"
                            @update="(option) => filters.perPage.selected = option"
                            name="assignments-filters-perpage"
                            class="h-10"
                        />
                    </section>
                </div>

                <!-- Flex layout -->
                <div>
                    <label
                        class="text-sm"
                    >
                        Layout
                    </label>
                    <div class="flex h-10 items-center p-1 space-x-2 text-zinc-400 border border-zinc-600 rounded-md">
                        <button
                            class="h-7 p-0.5 border rounded-md hover:border-zinc-400"
                            :class="grid ? 'bg-zinc-800 border-zinc-700' : 'bg-zinc-700 border-zinc-600'"
                            @click="grid = false"
                        >
                            <hero-icon
                                name="QueueList"
                                class="h-full"
                                variant="outline"
                            />
                        </button>
                        <button
                            class="h-7 p-0.5 border rounded-md hover:border-zinc-400"
                            :class="grid ? 'bg-zinc-700 border-zinc-600' : 'bg-zinc-800 border-zinc-700'"
                            @click="grid = true"
                        >
                            <hero-icon
                                name="Squares2X2"
                                class="h-full"
                                variant="outline"
                            />
                        </button>
                    </div>
                </div>

            </section>
        </div>
        <div
            :class="grid ? 'flex-wrap gap-4' : 'flex-col space-y-4'"
            class="flex"
        >
            <assignment
                v-for="assignment, i in assignments"
                :key="i"
                :assignment="assignment"
                @click.stop="goToAssignment(assignment)"
                class="hover:cursor-pointer"
                :class="grid ? 'lg:w-96 md:w-80 sm:w-64 w-52' : 'w-full'"
            />
        </div>
    </div>
</template>

<script>
import HeroIcon from '../../components/HeroIcon.vue'
import Assignment from '../../components/Assignments/Assignment.vue'
import PageTitle from '../../components/PageTitle.vue'
import DefaultDropdown from '../../components/FormInputs/DefaultDropdown.vue'
import axios from 'axios'
import _ from 'lodash'

export default {

    components: {
        HeroIcon,
        Assignment,
        PageTitle,
        DefaultDropdown,
    },

    mounted () {
        this.fetchAssignments()
    },

    created () {
        this.filters.perPage.options = _.range(5, 51, 5).map((value) => {
            return { label: value, value: value }
        })

        this.filters.perPage.selected = this.filters.perPage.options[3]
        this.filters.orderBy.selected = this.filters.orderBy.options[1]
    },

    data () {
        return {
            assignments: [],
            grid: true,
            filters: {
                perPage: {
                    options: [],
                    selected: {},
                },
                orderBy: {
                    options: [
                        {
                            label: 'Status',
                            value: 'status',
                        },
                        {
                            label: 'Due at',
                            value: 'due_at',
                        },
                        {
                            label: 'Created at',
                            value: 'created_at',
                        },
                    ],
                    selected: {},
                },
            },
        }
    },

    methods: {
        fetchAssignments: _.debounce(function () {
            axios.get(`/assignments?orderBy=${this.filters.orderBy.selected.value}&perPage=${this.filters.perPage.selected.value}`)
                .then((res) => {
                    this.assignments = res.data.data
                })
        }, 100),
        goToAssignment (assignment) {
            this.$router.push({
                name: 'assignments.detail',
                params: {
                    id: assignment.id,
                },
            })
        },
    },

    watch: {
        'filters.orderBy.selected' (to, from) {
            if (to === from) {
                return
            }

            this.fetchAssignments()
        },

        'filters.perPage.selected' (to, from) {
            if (to === from) {
                return
            }

            this.fetchAssignments()
        }
    }
}
</script>
