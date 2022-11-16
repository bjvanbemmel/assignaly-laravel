<template>
    <div class="p-2 border-b-2 border-gray-200 bg-gray-100 w-full flex items-center">
        <previous-page-button 
            @click="this.$router.back()"
        />
        <h1 class="text-gray-800 font-semibold">{{ route }}</h1>
    </div>
</template>

<script>
import PreviousPageButton from '../../components/Navigation/PreviousPageButton.vue'

export default {

    components: {
        PreviousPageButton,
    },

    computed: {
        route () {
            return this.$route.meta.label
        },

        previous () {
            // todo: Create navigation tree
        },
    },

}
</script>
