<template>
    <div
        @click.stop=""
        :class="getColor + ' ' + getSize"
        class="cursor-default relative group select-none aspect-square rounded-full flex justify-center items-center font-bold text-white"
    >
        {{ initials }}
        <div
            v-if="hover"
            class="absolute hidden group-hover:flex flex-col items-center z-10 bottom-6 px-2 h-24"
        >
            <div
                :class="getBorder"
                class="hidden group-hover:flex flex-col w-max p-2 bg-white border-2 text-gray-800 h-4/5"
            >
                <p> {{ user.name }} </p>
                <p class="text-xs text-gray-600"> {{ role }} </p>
                <a
                    :href="'mailto:' + user.email"
                    class="text-xs underline text-blue-500 hover:no-underline"
                > {{ user.email }} </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    props: {
        user: {},

        size: {
            type: String,
            default: 'md',
        },

        hover: {
            type: Boolean,
            default: false,
        },
    },

    computed: {
        initials () {
            const split = this.user.name.split(' ')

            if (split.length > 1) {
                return `${split[0][0]}${split[split.length - 1][0]}`
            }

            return split[0][0]
        },

        getColor () {
            return `bg-${this.user.settings.profile_icon_color}-500`
        },

        getBorder () {
            return `border-${this.user.settings.profile_icon_color}-500`
        },

        getSize () {
            switch (this.size) {
                case 'sm':
                    return 'text-sm h-8'
                case 'md':
                    return 'text-md h-10'
                default:
                    return 'text-lg h-12'
            }
        },

        role () {
            let role = this.user.role.name

            return role.charAt(0).toUpperCase() + role.slice(1)
        },
    },
}
</script>
