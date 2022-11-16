<template>
    
</template>

<script>
import axios from 'axios'

export default {
    
    mounted() {
        axios.get(`/assignments/${this.$route.params.id}`)
            .then((res) => {
                this.assignment = res.data
                this.$route.meta.label = this.assignment.title
            })
    },

    data() {
        return {
            assignment: {},
        }
    },

}
</script>
