<template>
    <h1
        class="text-2xl"
    >
        Welcome back, {{ user.name }}
    </h1>
</template>

<script>
import { useUserStore, } from './../../stores/user.js'

export default {

    computed: {
        user () {
            return useUserStore().getData
        },
    },
}
</script>
