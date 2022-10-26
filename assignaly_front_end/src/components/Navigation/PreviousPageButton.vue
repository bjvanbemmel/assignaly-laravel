<template>
    <button>
        <hero-icon
            name="ArrowSmallLeft"
            variant="mini"
            :class="previousAvailable ? 'h-10 text-gray-800' : 'h-10 text-gray-300 hover:cursor-not-allowed'"
        />
    </button>
</template>

<script>
import HeroIcon from '../../components/HeroIcon.vue'

export default {

    components: {
        HeroIcon,
    },

    computed: {
        previousAvailable () {
            return true
        },
    },

    methods: {
        previous () {
            // todo: Create navigation tree
        },
    },

}
</script>
