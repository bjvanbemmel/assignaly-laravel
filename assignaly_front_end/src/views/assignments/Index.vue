<template>
    <assignment
        v-for="assignment, i in assignments"
        :key="i"
        :assignment="assignment"
        @click="goToAssignment(assignment)"
    />
</template>

<script>
import axios from 'axios'
import Assignment from '../../components/Assignment.vue'

export default {

    components: {
        Assignment,
    },

    mounted() {
        axios.get('/assignments')
            .then((res) => {
                console.log(res.data)
                this.assignments = res.data.data
            })
    },

    data() {
        return {
            assignments: [],
        }
    },

    methods: {
        goToAssignment(assignment) {
            this.$router.push({
                name: 'assignment',
                params: {
                    id: assignment.id,
                },
            })
        },
    }
}
</script>
