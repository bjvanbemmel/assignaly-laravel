<template>
    <div
        class="m-4 p-6 bg-gray-50 border"
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
                    :options="dropdownOptions"
                    :default="dropdownSelected"
                    @update="(option) => dropdownStatusUpdate(option)"
                />
                <default-button
                    text="Turn in"
                    @click.stop=""
                />
                <default-button
                    text="Delete"
                    @click="toggleDeletionModal"
                />
            </div>
        </div>
        <modal
            :active="modal.active"
            @click.stop="() => toggleDeletionModal()"
        >
            <template v-slot:title>
                Delete assignment
            </template>

            <template v-slot:desc>
                This action will permanently delete this assignment.
            </template>

            <template v-slot:content>
                <div class="w-full text-center">
                    <p>Are you sure you wish to permanently delete this assignment?</p>
                    <p>Assigned users will be notified.</p>
                    <p class="text-red-600 font-bold">This action is irreversible.</p>
                </div>
            </template>

            <template v-slot:actions>
                <default-button
                    text="Delete"
                    @click.stop="() => approveDeletionModal()"
                />

                <default-button
                    text="Cancel"
                    @click.stop="() => toggleDeletionModal()"
                />
            </template>
        </modal>
    </div>
</template>

<script>
import HeroIcon from './../../components/HeroIcon.vue'
import DefaultButton from './../../components/FormInputs/DefaultButton.vue'
import DefaultDropdown from './../../components/FormInputs/DefaultDropdown.vue'
import Modal from './../../components/Modal.vue'
import axios from 'axios'

export default {

    components: {
        HeroIcon,
        DefaultButton,
        DefaultDropdown,
        Modal,
    },

    created () {
        this.fetchData()
    },

    data () {
        return {
            assignment: null,
            modal: {
                active: false,
            },

            dropdownSelected: null,
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
                    this.dropdownSelected = {
                        label: res.data.data.status,
                    }

                    this.loading = false
                })
        },

        dropdownStatusUpdate (status) {
            this.dropdownSelected = status
            this.updateAssignmentStatus(status)
        },

        updateAssignmentStatus (status) {
            axios.put(`/assignments/${this.assignment.id}`, {
                status: status.label,
            })
                .then(() => this.fetchData())
        },

        deleteAssignment () {
            axios.delete(`/assignments/${this.assignment.id}`)
                .then(() => this.$router.push({ name: 'assignments.index', }))
        },

        toggleDeletionModal () {
            this.modal.active = !this.modal.active
        },

        approveDeletionModal () {
            this.toggleDeletionModal()

            this.deleteAssignment()
        },
    },

}
</script>
