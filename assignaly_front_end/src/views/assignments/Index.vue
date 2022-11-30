<template>
    <div class="p-4 flex flex-col space-y-4">
        <assignment
            v-for="assignment, i in assignments"
            :key="i"
            :assignment="assignment"
            @click.stop="goToAssignment(assignment)"
            class="hover:cursor-pointer"
        />
    </div>
</template>

<script>
import axios from 'axios'
import Assignment from '../../components/Assignment.vue'

export default {

    components: {
        Assignment,
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
