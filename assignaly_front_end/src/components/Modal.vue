<template>
    <div
        v-if="active"
        @click.stop="toggleModal"
        class="z-40 cursor-pointer bg-black bg-opacity-25 absolute w-screen h-screen top-0 left-0 flex justify-center items-center"
    >
        <div
            class="cursor-default shadow-lg bg-white p-2 min-w-[24rem] min-h-[10rem] h-max w-max"
        >
            <div
                @click.stop=""
                class="text-lg text-center"
            >
                <slot name="title" />
            </div>

            <div
                @click.stop=""
                class="text-xs text-center text-gray-500 font-medium border-b pb-2 mb-2"
            >
                <slot name="desc" />
            </div>

            <div
                @click.stop=""
            >
                <slot name="content" />
            </div>

            <div
                class="flex justify-end space-x-2 border-t pt-2 mt-2"
                v-if="this.$slots.actions"
                @click.stop=""
            >
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>

<script>

export default {

    props: {
        active: {
            type: Boolean,
            default: false,
        },
    },

    methods: {
        toggleModal () {
            console.log('sadge')
            this.$emit('close')
        },
    },
}
</script>
