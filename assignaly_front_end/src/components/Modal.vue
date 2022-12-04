<template>
    <div
        v-if="active"
        @click.stop="toggleModal"
        class="z-40 fixed cursor-pointer bg-black/60 w-screen h-screen top-0 left-0 flex justify-center items-center"
    >
        <div
            class="cursor-default shadow-md shadow-black/40 rounded-md bg-zinc-900 border border-zinc-700 p-2 min-w-[24rem] min-h-[10rem] h-max w-max"
        >
            <div
                @click.stop=""
                class="text-lg text-center select-none"
            >
                <slot name="title" />
            </div>

            <div
                @click.stop=""
                class="text-xs text-center text-zinc-400 font-medium border-b border-zinc-700 pb-2 mb-2 select-none"
            >
                <slot name="desc" />
            </div>

            <div
                @click.stop=""
            >
                <slot name="content" />
            </div>

            <div
                class="flex justify-end space-x-2 border-t border-zinc-700 pt-2 mt-2"
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
        closeModal () {
            this.$emit('close')
        },

        closeModalOnKeyUp (event) {
            if (event.key === 'Escape') {
                this.closeModal()
            }
        },
    },

    watch: {
        active (to) {
            if (to) {
                document.body.style.overflowY = 'hidden'
                document.addEventListener('keyup', this.closeModalOnKeyUp)
            } else {
                document.body.style.overflowY = 'scroll'
                document.removeEventListener('keyup', this.closeModalOnKeyUp)
            }
        },
    },
}
</script>
