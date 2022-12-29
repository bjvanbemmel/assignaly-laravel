<template>
    <section class="grid sm:grid-cols-3 gap-4">
        <div
            class="h-fit col-span-2 rounded-md p-6 bg-zinc-800 border border-zinc-800 shadow-md shadow-black/30"
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
            </div>
        </div>

        <div
            class="h-fit rounded-md p-6 bg-zinc-800 border border-zinc-800 shadow-md shadow-black/30"
        >
            <hero-icon
                v-if="loading"
                name="ArrowPath"
                class="m-auto h-8 text-zinc-500 animate-spin"
            />
            <div
                v-else
            >
                <div class="flex flex-col space-y-2">
                    <div>
                        <label class="text-sm">
                            Owner:
                        </label>
                        <div
                            class="flex w-full gap-4 items-center first-of-type:rounded-t-md last:rounded-b-md p-2 border border-b-0 last:border-b border-zinc-600"
                        >
                            <user-icon
                                size="md"
                                :user="assignment.owner"
                                class="first:ml-0 ml-2"
                            />

                            <div class="flex flex-col">
                                <p>
                                    {{ assignment.owner.name }}
                                </p>

                                <a
                                    class="text-sm underline hover:no-underline text-blue-400"
                                    :href="`mailto:${assignment.owner.email}`"
                                >
                                    {{ assignment.owner.email }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm">
                            Assignees:
                        </label>
                        <div
                            class="flex w-full gap-4 items-center first-of-type:rounded-t-md last:rounded-b-md p-2 border border-b-0 last:border-b border-zinc-600"
                            v-for="user, i in users.slicedUsers"
                            :key="i"
                        >
                            <user-icon
                                size="md"
                                :user="user"
                                class="first:ml-0 ml-2"
                            />

                            <div class="flex flex-col">
                                <p>
                                    {{ user.name }}
                                </p>

                                <a
                                    class="text-sm underline hover:no-underline text-blue-400"
                                    :href="`mailto:${user.email}`"
                                >
                                    {{ user.email }}
                                </a>
                            </div>
                        </div>
                    </div>
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
        </div>
    </section>
</template>

<script>
import HeroIcon from './../../components/HeroIcon.vue'
import DefaultButton from './../../components/FormInputs/DefaultButton.vue'
import DefaultDropdown from './../../components/FormInputs/DefaultDropdown.vue'
import AssignmentStatus from './../../components/Assignments/AssignmentStatus.vue'
import Modal from './../../components/Modal.vue'
import UserIcon from './../../components/UserIcon.vue'
import UserIconOverflow from './../../components/UserIconOverflow.vue'
import axios from 'axios'
import { useDropdownStore, } from '../../stores/dropdown.js'

export default {

    components: {
        HeroIcon,
        DefaultButton,
        DefaultDropdown,
        AssignmentStatus,
        Modal,
        UserIcon,
        UserIconOverflow,
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

    computed: {
        users () {
            const slicedUsers = this.assignment.users.slice(0, 15)
            const overflowUsers = this.assignment.users.slice(15, this.assignment.users.length - 1)

            return {
                slicedUsers: slicedUsers,
                overflowUsers: overflowUsers,
            }
        },
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
