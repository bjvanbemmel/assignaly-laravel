<template>
    <div
        class="rounded-md p-6 bg-zinc-800 border border-zinc-800 shadow-md shadow-black/30"
    >
        <hero-icon
            v-if="loading"
            name="ArrowPath"
            class="m-auto h-8 text-zinc-500 animate-spin"
        />
        <div
            v-else
        >
            <h1 class="border-b border-zinc-600 mb-2 pb-2 text-lg font-semibold">
                {{ assignment.title }}
                <assignment-status
                    :status="assignment.status"
                    class="my-2"
                />
            </h1>
            <div
                class="mt-4 text-sm bg-zinc-800 border border-zinc-600 p-2"
            >
                {{ assignment.description }}
            </div>

            <div
                class="flex space-x-2 bg-zinc-900/50 border-b border-zinc-600 mt-4 p-2"
            >
                <default-dropdown
                    :options="dropdownOptions"
                    :default="dropdownSelected"
                    name="assignment-status-dropdown"
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
            @close="() => toggleDeletionModal()"
        >
            <template v-slot:title>
                Delete assignment
            </template>

            <template v-slot:desc>
                This action will permanently delete this assignment.
            </template>

            <template v-slot:content>
                <div class="w-full text-center p-2">
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
import AssignmentStatus from './../../components/Assignments/AssignmentStatus.vue'
import Modal from './../../components/Modal.vue'
import axios from 'axios'
import { useDropdownStore, } from '../../stores/dropdown.js'

export default {

    components: {
        HeroIcon,
        DefaultButton,
        DefaultDropdown,
        AssignmentStatus,
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

            loading: false,

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

    watch: {
        '$route.params' () {
            this.fetchData()
            useDropdownStore().setName('')
            console.log(useDropdownStore().getName)
        },
    },

}
</script>
