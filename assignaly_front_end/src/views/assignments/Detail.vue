<template>
    <div
        v-if="assignment.owner"
        class="m-6 p-6 bg-gray-50 border overflow-y-scroll"
    >
        {{ assignment }}
    </div>
    <div
        v-else
        class="w-full h-full flex justify-center items-center"
    >
        <hero-icon
            name="ArrowPath"
            class="h-8 text-gray-400 animate-spin"
        />
    </div>
</template>

<script>
import UserIcon from './../../components/UserIcon.vue'
import HeroIcon from './../../components/HeroIcon.vue'
import axios from 'axios'

export default {

    components: {
        UserIcon,
        HeroIcon,
    },
    
    created () {
        axios.get(`/assignments/${this.$route.params.id}`)
            .then((res) => {
                this.assignment = res.data.data
                this.$route.meta.label = this.assignment.title
            })
    },

    data () {
        return {
            assignment: {
            },
        }
    },

}
</script>
