<template>
    <div class="flex items-center px-4 h-16 bg-slate-700">
        <ul class="flex justify-start w-1/3 text-white">
            <li class="mx-4 hover:underline underline-offset-2">
                <a href="./">Overzicht</a>
            </li>
            <li class="mx-4 hover:underline underline-offset-2">
                <a href="./">Opdrachten</a>
            </li>
        </ul>

        <div class="w-1/3 flex justify-center">
            <header-bar-search />
        </div>

        <!-- placeholder -->
        <div class="w-1/3 flex justify-end">
            aaaa
        </div>
    </div>
</template>

<script>
import { ChevronDownIcon } from '@heroicons/vue/24/solid'
import HeaderBarSearch from './HeaderBarSearch.vue'

export default {
    name: 'HeaderBar',

    components: {
        ChevronDownIcon,
        HeaderBarSearch,
    },
}
</script>
