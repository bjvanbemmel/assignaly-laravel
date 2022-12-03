<template>
    <div class="flex flex-col space-y-4">
        <div class="flex justify-between">
            <page-title>
                Your assignments:
            </page-title>

            <div class="flex items-center space-x-2 px-1 text-zinc-400 border border-zinc-700 rounded-md">
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
import axios from 'axios'
import HeroIcon from '../../components/HeroIcon.vue'
import Assignment from '../../components/Assignments/Assignment.vue'
import PageTitle from '../../components/PageTitle.vue'

export default {

    components: {
        HeroIcon,
        Assignment,
        PageTitle,
    },

    mounted () {
        axios.get('/assignments')
            .then((res) => {
                this.assignments = res.data.data
            })
    },

    data () {
        return {
            assignments: [],
            grid: true,
        }
    },

    methods: {
        goToAssignment (assignment) {
            this.$router.push({
                name: 'assignments.detail',
                params: {
                    id: assignment.id,
                },
            })
        },
    },
}
</script>
