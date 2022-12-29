<template>
    <div>
        <page-title>
            Account Settings
        </page-title>
        <section class="grid sm:grid-cols-4 gap-6 text-white">
            <div class="flex flex-col rounded-md bg-zinc-800 border border-zinc-600">
                <button
                    :class="{ 'bg-zinc-900': this.$route.params.tab === 'profile' || this.$route.params.tab[0] === 'profile' }"
                    class="flex items-center space-x-4 p-4 first:border-t-0 last:border-b-0 border-x-none border-b rounded-t-md hover:text-zinc-300 border-zinc-600"
                    @click="this.$router.push( { params: { tab: 'profile', } } )"
                >
                    <hero-icon
                        name="User"
                        class="h-5"
                        variant="outline"
                    />
                    <p>Profile</p>
                </button>
                <button
                    :class="{ 'bg-zinc-900': this.$route.params.tab === 'integrations' || this.$route.params.tab[0] === 'integrations' }"
                    class="flex items-center space-x-4 p-4 first:border-t-0 last:border-b-0 border-x-none border-b rounded-t-md hover:text-zinc-300 border-zinc-600"
                    @click="this.$router.push( { params: { tab: 'integrations', } } )"
                >
                    <hero-icon
                        name="Link"
                        class="h-5"
                        variant="outline"
                    />
                    <p>Integrations</p>
                </button>
                <button
                    :class="{ 'bg-zinc-900': this.$route.params.tab === 'privacy' || this.$route.params.tab[0] === 'privacy' }"
                    class="flex items-center space-x-4 p-4 first:border-t-0 last:border-b-0 border-x-none border-b rounded-t-md hover:text-zinc-300 border-zinc-600"
                    @click="this.$router.push( { params: { tab: 'privacy', } } )"
                >
                    <hero-icon
                        name="EyeSlash"
                        class="h-5"
                        variant="outline"
                    />
                    <p>Privacy</p>
                </button>
            </div>
            <div class="p-2 col-span-3 h-fit flex flex-col rounded-md bg-zinc-800 border border-zinc-600">
                <detail />
            </div>
        </section>
    </div>
</template>

<script>
import PageTitle from '../../components/PageTitle.vue'
import HeroIcon from '../../components/HeroIcon.vue'
import Detail from './Detail.vue'

export default {

    components: {
        PageTitle,
        HeroIcon,
        Detail,
    },
}
</script>
