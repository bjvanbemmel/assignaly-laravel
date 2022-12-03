<template>
    <div
        class="py-32 text-clip select-none overflow-hidden whitespace-nowrap flex flex-col justify-center items-center text-zinc-800"
    >
        <hero-icon
            name="QuestionMarkCircle"
            class="h-32"
        />
        <h1
            class="text-4xl font-bold"
        >
            Whoops, looks like you're lost.
        </h1>
        <h2
            class="text-2xl font-bold"
        >
            The page you're looking for does not exist.
        </h2>
    </div>
</template>

<script>
import HeroIcon from './../../components/HeroIcon.vue'

export default {

    components: {
        HeroIcon,
    },
}
</script>
