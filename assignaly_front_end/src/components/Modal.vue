<template>
    <div
        v-if="active"
        @click.stop="toggleModal"
        class="z-40 cursor-pointer bg-black bg-opacity-25 absolute w-screen h-screen top-0 left-0 flex justify-center items-center"
    >
        <div
            class="cursor-default bg-white p-2 min-w-[24rem] min-h-[10rem] h-max w-max"
        >
            <div
                class="text-lg w-full"
            >
                <slot
                    @click.stop=""
                    name="title"
                />
            </div>

            <div
                class="text-xs text-gray-500 font-medium border-b pb-2 mb-2"
            >
                <slot
                    @click.stop=""
                    name="desc"
                />
            </div>

            <div>
                <slot
                    @click.stop=""
                    name="content"
                />
            </div>

            <div
                class="flex justify-end space-x-2 border-t pt-2 mt-2"
                v-if="this.$slots.actions"
            >
                <slot
                    @click.stop=""
                    name="actions"
                />
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
            this.$emit('close')
        },
    },
}
</script>
