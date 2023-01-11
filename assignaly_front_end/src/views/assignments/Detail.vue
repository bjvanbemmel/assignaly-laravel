<template>
    <section class="text-white grid sm:grid-cols-3 gap-4">
        <section class="flex gap-4 flex-col col-span-2">
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
                    Repository
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
                    Comments
                </div>
            </div>
        </section>

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
                            v-for="user, i in assignment.users"
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
                            <default-button
                                v-if="assignment.remote_repository"
                                @click="githubAddUserAsCollaborator(user.id)"
                                class="ml-auto mr-4 aspect-square"
                                title="Add as collaborator"
                            >
                                <hero-icon
                                    name="UserPlus"
                                    class="h-4"
                                />
                                
                            </default-button>
                        </div>
                    </div>
                </div>

                <label class="text-sm">
                    Remote repository:
                </label>

                <div class="p-2 bg-zinc-900/50 rounded-md border border-zinc-700">
                    <div
                        v-if="assignment.remote_repository !== null"
                        class="flex gap-2"
                    >
                        <a
                            target="_blank"
                            ref="noreferrer noopener"
                            :href="assignment.remote_repository.public_url"
                        >
                            <default-button>
                                View on Github
                                <hero-icon
                                    name="ArrowTopRightOnSquare"
                                    class="h-3 mb-auto"
                                />
                            </default-button>
                        </a>

                        <default-button
                            text="Edit repository"
                            @click="toggleUpdateRepositoryModal()"
                        />

                        <default-button
                            text="Delete repository"
                            @click="toggleDeleteRepositoryModal()"
                        />
                    </div>

                    <div v-else>
                        <default-button
                            text="Create new repository"
                            @click="toggleNewRepositoryModal()"
                        />
                    </div>
                </div>

                <label class="text-sm">
                    Actions:
                </label>

                <div
                    class="flex gap-2 p-2 bg-zinc-900/50 rounded-md border border-zinc-700"
                >
                    <default-dropdown
                        :options="dropdowns.status.options"
                        :default="dropdowns.status.selected"
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
        </div>
    </section>

    <!-- Modals -->
    <div>
        <!-- Deletion modal -->

        <modal
            :active="modals.deleteAssignment.active"
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
                    class="enabled:bg-red-800 enabled:border-red-700 enabled:hover:bg-red-900"
                    @click.stop="() => approveDeletionModal()"
                />

                <default-button
                    text="Cancel"
                    @click.stop="() => toggleDeletionModal()"
                />
            </template>
        </modal>

        <!-- New repository modal -->

        <modal
            :active="modals.newRepository.active"
            @click.stop="() => toggleNewRepositoryModal()"
            @close="() => toggleNewRepositoryModal()"
        >
            <template v-slot:title>
                Create new repository
            </template>

            <template v-slot:desc>
                Create a new remote Git repository using one of your authorized integrations.
            </template>

            <template v-slot:content>
                <default-alert
                    v-if="modals.newRepository.data.error"
                    class="mx-2"
                >
                    {{ modals.newRepository.data.error }}
                </default-alert>
                <div
                    v-if="modals.newRepository.loading"
                    class="h-32 flex justify-center items-center"
                >
                    <hero-icon
                        name="ArrowPath"
                        class="h-8 text-zinc-500 animate-spin"
                    />
                </div>

                <div
                    v-else
                    class="p-2 flex flex-col gap-6"
                >
                    <div
                        class="flex flex-col space-y-0.5"
                    >
                        <label
                            for="assignment-new-repository-integration-dropdown"
                            class="text-sm"
                        >
                            Git integration:
                        </label>

                        <default-dropdown
                            :options="dropdowns.integrationType.options"
                            :default="dropdowns.integrationType.selected"
                            name="assignment-new-repository-integration-dropdown"
                            @update="(option) => dropdowns.integrationType.selected = option"
                        />
                    </div>

                    <section
                        class="flex flex-col gap-6"
                        :class="{ 'text-zinc-600': dropdowns.integrationType.selected.value === null }"
                    >
                        <div
                            class="flex flex-col space-y-0.5"
                        >
                            <label
                                for="assignment-new-repository-title"
                                class="text-sm"
                            >
                                Repository title:
                            </label>

                            <default-text-input
                                name="assignment-new-repository-title"
                                :placeholder="toSlug(assignment.title)"
                                :value="modals.newRepository.data.title"
                                @update="(text) => modals.newRepository.data.title = toSlug(text)"
                                :disabled="dropdowns.integrationType.selected.value === null"
                            />

                            <p class="text-xs text-zinc-500 italic">
                                Special characters and spaces are not allowed. Max length of 100 characters.
                            </p>
                        </div>

                        <div
                            class="flex flex-col space-y-0.5"
                        >
                            <label
                                for="assignment-new-repository-desc"
                                class="text-sm"
                            >
                                Repository description:
                            </label>

                            <default-text-input
                                name="assignment-new-repository-desc"
                                placeholder="My blazingly fast <insert language here> framework."
                                @update="(text) => modals.newRepository.data.desc = text"
                                :disabled="dropdowns.integrationType.selected.value === null"
                            />

                            <p class="text-xs text-zinc-500 italic">
                                Max length of 255 characters.
                            </p>
                        </div>

                        <section class="flex flex-col">
                            <button
                                v-for="option, key in modals.newRepository.data.options.filter(option => option.enabled)"
                                :key="key"
                                class="flex disabled:cursor-not-allowed disabled:bg-zinc-900 disabled:text-zinc-600 gap-2 bg-zinc-800 first-of-type:rounded-t-md last-of-type:rounded-b-md last-of-type:border-b border border-b-0 border-zinc-700 p-2"
                                :name="option.name"
                                @click="option.value = !option.value"
                                :disabled="dropdowns.integrationType.selected.value === null"
                            >
                                <hero-icon
                                    v-if="option.value"
                                    name="CheckCircle"
                                    variant="mini"
                                    class="h-6 my-auto"
                                />

                                <hero-icon
                                    v-else
                                    name="XCircle"
                                    variant="mini"
                                    class="h-6 my-auto text-zinc-600"
                                />
                                {{ option.label }}
                            </button>
                        </section>
                    </section>
                </div>
            </template>

            <template v-slot:actions>
                <default-button
                    text="Create new repository"
                    :disabled="dropdowns.integrationType.selected.value === null || modals.newRepository.loading"
                    class="enabled:bg-green-800 enabled:border-green-700 enabled:hover:bg-green-900"
                    @click.stop="githubNewRepository()"
                />

                <default-button
                    text="Cancel"
                    :disabled="modals.newRepository.loading"
                    @click.stop="() => toggleNewRepositoryModal()"
                />
            </template>

        </modal>

        <!-- Update repository modal -->

        <modal
            :active="modals.updateRepository.active"
            @click.stop="() => toggleUpdateRepositoryModal()"
            @close="() => toggleUpdateRepositoryModal()"
        >
            <template v-slot:title>
                Update repository
            </template>

            <template v-slot:desc>
                Update the associated remote repository
            </template>

            <template v-slot:content>
                <default-alert
                    v-if="modals.updateRepository.data.error"
                    class="mx-2"
                >
                    {{ modals.updateRepository.data.error }}
                </default-alert>
                <div
                    v-if="modals.updateRepository.loading"
                    class="h-32 flex justify-center items-center"
                >
                    <hero-icon
                        name="ArrowPath"
                        class="h-8 text-zinc-500 animate-spin"
                    />
                </div>

                <div
                    v-else
                    class="p-2 flex flex-col gap-6"
                >
                    <section
                        class="flex flex-col gap-6"
                        :class="{ 'text-zinc-600': dropdowns.integrationType.selected.value === null }"
                    >
                        <div
                            class="flex flex-col space-y-0.5"
                        >
                            <label
                                for="assignment-new-repository-title"
                                class="text-sm"
                            >
                                Repository title:
                            </label>

                            <default-text-input
                                name="assignment-new-repository-title"
                                :placeholder="toSlug(assignment.title)"
                                :value="modals.updateRepository.data.title"
                                @update="(text) => modals.updateRepository.data.title = toSlug(text)"
                            />

                            <p class="text-xs text-zinc-500 italic">
                                Special characters and spaces are not allowed. Max length of 100 characters.
                            </p>
                        </div>

                        <div
                            class="flex flex-col space-y-0.5"
                        >
                            <label
                                for="assignment-new-repository-desc"
                                class="text-sm"
                            >
                                Repository description:
                            </label>

                            <default-text-input
                                name="assignment-new-repository-desc"
                                placeholder="My blazingly fast <insert language here> framework."
                                @update="(text) => modals.updateRepository.data.desc = text"
                            />

                            <p class="text-xs text-zinc-500 italic">
                                Max length of 255 characters.
                            </p>
                        </div>

                        <section class="flex flex-col">
                            <button
                                v-for="option, key in modals.updateRepository.data.options.filter(option => option.enabled)"
                                :key="key"
                                class="flex disabled:cursor-not-allowed disabled:bg-zinc-900 disabled:text-zinc-600 gap-2 bg-zinc-800 first-of-type:rounded-t-md last-of-type:rounded-b-md last-of-type:border-b border border-b-0 border-zinc-700 p-2"
                                :name="option.name"
                                @click="option.value = !option.value"
                            >
                                <hero-icon
                                    v-if="option.value"
                                    name="CheckCircle"
                                    variant="mini"
                                    class="h-6 my-auto"
                                />

                                <hero-icon
                                    v-else
                                    name="XCircle"
                                    variant="mini"
                                    class="h-6 my-auto text-zinc-600"
                                />
                                {{ option.label }}
                            </button>
                        </section>
                    </section>
                </div>
            </template>

            <template v-slot:actions>
                <default-button
                    text="Update repository"
                    :disabled="modals.updateRepository.loading"
                    class="enabled:bg-green-800 enabled:border-green-700 enabled:hover:bg-green-900"
                    @click.stop="githubNewRepository()"
                />

                <default-button
                    text="Cancel"
                    :disabled="modals.updateRepository.loading"
                    @click.stop="() => toggleUpdateRepositoryModal()"
                />
            </template>

        </modal>


        <!-- Delete repository modal -->
        
        <modal
            :active="modals.deleteRepository.active"
            @click.stop="() => toggleDeleteRepositoryModal()"
            @close="() => toggleDeleteRepositoryModal()"
        >
            <template v-slot:title>
                Delete repository
            </template>

            <template v-slot:desc>
                Delete the repository associated with this assignment.
            </template>

            <template v-slot:content>
                <div
                    v-if="modals.deleteRepository.loading"
                    class="h-32 flex justify-center items-center"
                >
                    <hero-icon
                        name="ArrowPath"
                        class="h-8 text-zinc-500 animate-spin"
                    />
                </div>

                <div
                    v-else
                    class="p-2 flex flex-col gap-6"
                >
                    <div class="w-full text-center p-2">
                        <p>Are you sure you wish to permanently delete the associated remote repository?</p>
                        <p>Assigned users will be notified.</p>
                        <p class="text-red-600 font-bold">This action is irreversible.</p>
                    </div>
                </div>
            </template>

            <template v-slot:actions>
                <default-button
                    text="Delete"
                    :disabled="modals.deleteRepository.loading"
                    class="enabled:bg-red-800 enabled:border-red-700 enabled:hover:bg-red-900"
                    @click.stop="() => githubDeleteRepository()"
                />

                <default-button
                    text="Cancel"
                    :disabled="modals.deleteRepository.loading"
                    @click.stop="() => toggleDeleteRepositoryModal()"
                />
            </template>

        </modal>
    </div>
</template>

<script>
import HeroIcon from './../../components/HeroIcon.vue'
import DefaultButton from './../../components/FormInputs/DefaultButton.vue'
import DefaultDropdown from './../../components/FormInputs/DefaultDropdown.vue'
import DefaultTextInput from './../../components/FormInputs/DefaultTextInput.vue'
import DefaultAlert from './../../components/FormInputs/DefaultAlert.vue'
import AssignmentStatus from './../../components/Assignments/AssignmentStatus.vue'
import Modal from './../../components/Modal.vue'
import UserIcon from './../../components/UserIcon.vue'
import axios from 'axios'
import { useDropdownStore, } from '../../stores/dropdown.js'

export default {

    components: {
        HeroIcon,
        DefaultButton,
        DefaultDropdown,
        DefaultTextInput,
        DefaultAlert,
        AssignmentStatus,
        Modal,
        UserIcon,
    },

    created () {
        this.fetchData()
    },

    data () {
        return {
            assignment: null,
            modals: {
                deleteAssignment: {
                    active: false,
                },

                newRepository: {
                    active: false,
                    loading: false,
                    data: {
                        title: '',
                        desc: '',
                        private: false,
                        options: [
                            {
                                label: 'Make repository private.',
                                name: 'assignment-new-repository-private',
                                enabled: true,
                                value: false,
                            },
                            {
                                label: 'Invite all assignees.',
                                name: 'assignment-new-repository-invite-assignees',
                                enabled: false,
                                value: false,
                            },
                        ],
                        error: null,
                    },
                },

                updateRepository: {
                    active: false,
                    loading: false,
                    data: {
                        title: '',
                        desc: '',
                        private: false,
                        options: [
                            {
                                label: 'Make repository private.',
                                name: 'assignment-new-repository-private',
                                enabled: true,
                                value: false,
                            },
                        ],
                        error: null,
                    },
                },

                deleteRepository: {
                    active: false,
                    loading: false,
                },
            },

            loading: false,

            dropdowns: {
                status: {
                    selected: null,
                    options: [
                        {
                            label: 'Open',
                            value: 'open',
                        },
                        {
                            label: 'Closed',
                            value: 'closed',
                        },
                        {
                            label: 'In review',
                            value: 'in-review',
                        },
                    ],
                },

                integrationType: {
                    selected: null,
                    options: [
                        {
                            label: 'Github',
                            value: 'github',
                        },
                        {
                            label: 'None',
                            value: null,
                        },
                    ],
                },
            },
        }
    },

    methods: {
        toSlug (string) {
            return string.toLowerCase().replace(/ /g, '-').replace(/[^A-Za-z0-9-_]/g, '')
        },

        fetchData () {
            this.loading = true

            axios.get(`/assignments/${this.$route.params.id}`)
                .then((res) => {
                    this.assignment = res.data.data
                    this.modals.newRepository.data.title = this.toSlug(this.assignment.title)
                    this.modals.updateRepository.data.title = this.toSlug(this.assignment.title)

                    this.dropdowns.status.selected = {
                        label: this.dropdowns.status.options.find(status => status.value === res.data.data.status).label,
                    }

                    this.dropdowns.integrationType.selected = {
                        label: this.dropdowns.integrationType.options.find(type => type.value === res.data.data.integration_type).label,
                        value: res.data.data.integration_type,
                    }

                    this.loading = false
                })
        },

        dropdownStatusUpdate (status) {
            this.dropdowns.status.selected = status
            this.updateAssignmentStatus(status)
        },

        updateAssignmentStatus (status) {
            axios.put(`/assignments/${this.assignment.id}`, {
                status: status.value,
            })
                .then(() => this.fetchData())
        },

        deleteAssignment () {
            axios.delete(`/assignments/${this.assignment.id}`)
                .then(() => this.$router.push({ name: 'assignments.index', }))
        },

        toggleDeletionModal () {
            this.modals.deleteAssignment.active = !this.modals.deleteAssignment.active
        },

        toggleNewRepositoryModal () {
            if (this.modals.newRepository.loading) {
                return
            }

            this.modals.newRepository.active = !this.modals.newRepository.active
        },

        toggleUpdateRepositoryModal () {
            if (this.modals.updateRepository.loading) {
                return
            }

            this.modals.updateRepository.active = !this.modals.updateRepository.active
        },

        toggleDeleteRepositoryModal () {
            if (this.modals.deleteRepository.loading) {
                return
            }

            this.modals.deleteRepository.active = !this.modals.deleteRepository.active
        },

        approveDeletionModal () {
            this.toggleDeletionModal()
            this.deleteAssignment()
        },

        githubNewRepository () {
            let validationErrors = this.githubValidateNewRepositoryInput()
            if (validationErrors !== true) {
                this.modals.newRepository.data.error = validationErrors
                return
            }

            this.modals.newRepository.loading = true

            axios.post('/integrations/github/repo/new', {
                assignment_id: this.assignment.id,
                integration_type: this.dropdowns.integrationType.selected.value,
                name: this.modals.newRepository.data.title,
                description: this.modals.newRepository.data.desc,
                private: this.modals.newRepository.data.options.find(option => option.name === 'assignment-new-repository-private').value,
            })
                .then(() => {
                    this.modals.newRepository.loading = false
                    this.fetchData()
                    this.toggleNewRepositoryModal()
                })
        },

        githubValidateNewRepositoryInput () {
            const input = this.modals.newRepository.data
            input.error = null

            if (input.title.length === 0) {
                return 'Title may not be empty.'
            }

            if (input.title.length > 100) {
                return 'Title may not be longer than 100 characters.'
            }

            if (/[`!@#$%^&*()+\=\[\]{};':"\\|,.<>\/?~]/.test(input.title)) {
                return 'Title may not contain special characters'
            }

            if (input.desc.length > 255) {
                return 'Description may not be longer than 255 characters.'
            }

            return true
        },

        githubDeleteRepository () {
            this.modals.deleteRepository.loading = true

            axios.delete(`/integrations/github/repo/${this.assignment.id}/delete`)
                .then((res) => {
                    console.log(res)
                    this.modals.deleteRepository.loading = false
                    this.fetchData()
                    this.toggleDeleteRepositoryModal()
                })
        },

        githubAddUserAsCollaborator(user) {
            axios.post(`/integrations/github/repo/${this.assignment.id}/collaborators/add/${user}`)
                .then((res) => {
                    console.log(res)
                })
        },
    },

    watch: {
        '$route.params' () {
            this.fetchData()
            useDropdownStore().setName('')
            console.log(useDropdownStore().getName)
        },

        'modals.newRepository.data.options': {
            handler (to) {
                const privateOption = to.find(option => option.name === 'assignment-new-repository-private')
                const inviteAssigneesOption = to.find(option => option.name === 'assignment-new-repository-invite-assignees')

                if (privateOption.value) {
                    inviteAssigneesOption.enabled = true
                } else {
                    inviteAssigneesOption.enabled = false
                    inviteAssigneesOption.value = false
                }
            },

            deep: true,
        },
    },

}
</script>
