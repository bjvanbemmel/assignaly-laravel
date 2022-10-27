<template>
    <h1>Assignments</h1>
</template>

<script>
import axios from 'axios'

export default {

    mounted() {
        axios.get('/assignments')
            .then((res) => this.assignments = res.data)
    },

    data() {
        return {
            assignments: [],
        }
    },
}
</script>
