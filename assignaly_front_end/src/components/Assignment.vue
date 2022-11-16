<template>
    <div class="flex flex-col m-2 p-2 border rounded-md">
        <h3 class="font-bold">
            {{ assignment.title }}
        </h3>
        <p class="italic truncate">
            {{ assignment.description }}
        </p>

        <p>
            Due at: <span class="italic text-gray-600"> {{ assignment.due_at }} </span>
        </p>

        <p
            v-if="isFinished.status"
        >
            Finished at: <span class="italic text-gray-600"> {{ assignment.finished_at }} </span>
        </p>

        <p
            class="pb-2 border-b border-black"
            :class="isFinished.classes"
        >
            {{ isFinished.message }}
        </p>

    </div>
</template>

<script>
export default {

    props: {
        assignment: {
            type: Object,
            required: true,
        },
    },

    computed: {
        isFinished() {
            return {
                status: this.assignment.finished_at !== null,
                message: this.assignment.finished_at !== null ? 'Finished' : 'Unfinished',
                classes: this.assignment.finished_at !== null ? 'text-green-500' : 'text-red-500',
            }
        },
    },

}
</script>
