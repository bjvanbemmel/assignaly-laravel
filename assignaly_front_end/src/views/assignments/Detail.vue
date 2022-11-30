<template>
    <div
        class="m-4 p-6 bg-gray-50 border overflow-y-scroll"
    >
        <hero-icon
            v-if="!assignment"
            name="ArrowPath"
            class="m-auto h-8 text-gray-400 animate-spin"
        />
        <div
            v-else
        >
            <h1 class="border-b border-gray-300 mb-2 pb-2 text-lg font-semibold"> {{ assignment.title }} </h1>
            <div
                class="mt-4 text-sm bg-white border p-2"
            >
                {{ assignment.description }}
            </div>
            {{ assignment.status }}
            <div
                class="flex space-x-2 bg-gray-100 border-b mt-4 p-2"
            >
                <default-dropdown
                    class="hidden"
                    :options="dropdownOptions"
                />
                <default-button
                    v-if="assignment.status !== 'Closed'"
                    text="Close"
                    @click="closeAssignment"
                />
                <default-button
                    v-if="assignment.status !== 'Open'"
                    text="Open"
                    @click="openAssignment"
                />
                <default-button
                    text="Turn in"
                    @click.stop=""
                />
                <default-button
                    text="Delete"
                    @click="deleteAssignment"
/>
            </div>
        </div>
    </div>
</template>

<script>
import HeroIcon from './../../components/HeroIcon.vue'
import DefaultButton from './../../components/FormInputs/DefaultButton.vue'
import DefaultDropdown from './../../components/FormInputs/DefaultDropdown.vue'
import axios from 'axios'

export default {

    components: {
        HeroIcon,
        DefaultButton,
        DefaultDropdown,
    },

    created () {
        this.fetchData()
    },

    data () {
        return {
            assignment: null,

            dropdownOptions: [
                {
                    label: 'Open',
                },
                {
                    label: 'Closed',
                },
                {
                    label: 'In review',
                },
            ],
        }
    },

    methods: {
        fetchData () {
            this.loading = true

            axios.get(`/assignments/${this.$route.params.id}`)
                .then((res) => {
                    this.assignment = res.data.data
                    this.loading = false
                })
        },

        closeAssignment () {
            axios.put(`/assignments/${this.assignment.id}`, {
                status: 'Closed',
            })
                .then(() => this.fetchData())
        },

        openAssignment () {
            axios.put(`/assignments/${this.assignment.id}`, {
                status: 'Open',
            })
                .then(() => this.fetchData())
        },

        deleteAssignment () {
            axios.delete(`/assignments/${this.assignment.id}`)
                .then(() => this.$router.push({ name: 'assignments.index', }))
        },
    },

}
</script>
