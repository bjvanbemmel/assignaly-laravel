<template>
    <component :is="icon" />
</template>

<script>
import * as outline from '@heroicons/vue/24/outline'
import * as solid from '@heroicons/vue/24/solid'
import * as mini from '@heroicons/vue/20/solid'

export default {

    name: 'HeroIcon',

    components: {
        outline,
        solid,
        mini,
    },

    props: {
        name: {
            type: String,
            required: true,
        },

        variant: {
            type: String,
            default: 'solid',
        },
    },

    computed: {
        icons () {
            return { outline, solid, mini, }
        },

        icon () {
            return this.icons[this.variant][this.name + 'Icon']
        },
    },
}
</script>
