<template>
    <section
        class="pb-6"
    >
        <div
            v-if="assignment && assignment.review"
            class="w-96 p-2 bg-zinc-800 border-4 rounded-md flex flex-col mb-6"
            :class="assignment.review >= 6 ? 'border-green-600' : 'border-red-600'"
        >
            <h1>Score: <span class="text-lg font-bold">{{ assignment.review }}</span></h1>
            <h2 class="border-t border-zinc-600 pt-2">"{{ assignment.feedback }}"</h2>
        </div>
        <div
            v-if="assignment && loggedInUser.id === assignment.owner.id && assignment.status === 'in-review'"
            class="flex flex-wrap gap-2"
        >
            <default-button
                text="Give feedback"
                @click="toggleGiveFeedbackModal"
                class="bg-green-700 border-green-800 hover:bg-green-800 font-bold"
            >
                <hero-icon
                    name="Star"
                    class="h-4 mr-1"
                />
                Give feedback
            </default-button>

            <default-dropdown
                :options="dropdowns.status.options"
                :default="dropdowns.status.selected"
                name="assignment-status-dropdown"
                @update="(option) => dropdownStatusUpdate(option)"
            />

            <default-button
                text="Delete"
                @click="toggleDeletionModal"
            />
        </div>
        <div
            v-else-if="assignment && loggedInUser.id === assignment.owner.id"
            class="flex flex-wrap gap-2"
        >
            <default-button
                :to="{ name: 'assignments.update', params: {id: assignment.id} }"
            >
                <hero-icon
                    name="PencilSquare"
                    class="h-4 mr-1"
                />
                Edit assignment
            </default-button>

            <default-dropdown
                :options="dropdowns.status.options"
                :default="dropdowns.status.selected"
                name="assignment-status-dropdown"
                @update="(option) => dropdownStatusUpdate(option)"
            />

            <default-button
                text="Delete"
                @click="toggleDeletionModal"
            />
        </div>
        <div
            v-else-if="assignment && assignment.remote_repository"
        >
            <div
                v-if="assignment.status === 'in-review'"
            >
                <default-button
                    @click.stop="toggleCancelSubmissionModal()"
                >
                    <hero-icon
                        name="XMark"
                        class="h-4 mr-1"
                    />
                    Revert submission
                </default-button>
            </div>
            <div
                v-else
            >
                <default-button
                    @click.stop="toggleTurnInAssignmentModal()"
                >
                    <hero-icon
                        name="Flag"
                        class="h-4 mr-1"
                    />
                    Turn in
                </default-button>
                <p class="text-sm text-zinc-300 mt-1">Done with your assignment? <u>Turn it in</u>.</p>
            </div>
        </div>
    </section>
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
                                v-if="loggedInUser.isCollaboratorToRepository && assignment.users.filter((u) => u.id === loggedInUser.id).length > 0 && assignment.remote_repository && user.id !== loggedInUser.id && user.isCollaboratorToRepository === false"
                                @click="githubAddUserAsCollaborator(user.id)"
                                class="ml-auto mr-4"
                                title="Add as collaborator to repository"
                                :disabled="collabInvitesSent.includes(user.id)"
                            >
                                <p
                                    v-if="collabInvitesSent.includes(user.id)"
                                >
                                    Invite sent
                                </p>

                                <hero-icon
                                    v-else
                                    name="UserPlus"
                                    class="h-4"
                                />

                            </default-button>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm">
                            Remote repository:
                        </label>

                        <div class="p-2 bg-zinc-900/50 rounded-md border border-zinc-700">
                            <div
                                v-if="assignment.remote_repository !== null && loggedInUser.isCollaboratorToRepository === true"
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
                                    v-if="hasGithubIntegration(loggedInUser)"
                                    text="Edit repository"
                                    @click="toggleUpdateRepositoryModal()"
                                />

                                <default-button
                                    v-if="hasGithubIntegration(loggedInUser)"
                                    text="Delete repository"
                                    @click="toggleDeleteRepositoryModal()"
                                />
                            </div>
                            <div
                                v-else-if="assignment.remote_repository !== null && assignment.remote_repository.private === false"
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
                                <p v-if="assignment.remote_repository.private" class="text-xs text-zinc-400"> This repository is private </p>
                            </div>
                            <div 
                                v-else-if="assignment.remote_repository !== null && ((assignment.users.filter((user) => user.id === loggedInUser.id).length === 0 && loggedInUser.role.level >= 2) || assignment.owner.id === loggedInUser.id)"
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
                                <p v-if="assignment.remote_repository.private" class="text-xs text-zinc-400"> This repository is private </p>
                            </div>
                            <div v-else-if="assignment.remote_repository !== null && loggedInUser.isCollaboratorToRepository === false">
                                <default-button
                                    text="Repository already exists"
                                    @click="toggleNewRepositoryModal()"
                                    disabled
                                />
                            </div>
                            <div v-else>
                                <default-button
                                    :text="hasGithubIntegration(loggedInUser) ? 'Create new repository' : 'Enable Git integration for your account'"
                                    @click="toggleNewRepositoryModal()"
                                    :disabled="!hasGithubIntegration(loggedInUser)"
                                />
                            </div>
                        </div>
                    </div>
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
                    <p>The associated repository will not be deleted.</p>
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
                    v-if="modals.newRepository.error"
                    class="mx-2"
                >
                    {{ modals.newRepository.error }}
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
                    v-if="modals.updateRepository.error"
                    class="mx-2"
                >
                    {{ modals.updateRepository.error }}
                </default-alert>

                <div
                    v-else-if="modals.updateRepository.loading"
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
                                name="assignment-update-repository-title"
                                placeholder="New title"
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
                                for="assignment-update-repository-desc"
                                class="text-sm"
                            >
                                Repository description:
                            </label>

                            <default-text-input
                                name="assignment-update-repository-desc"
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
                    v-if="!modals.updateRepository.error"
                    text="Update repository"
                    :disabled="modals.updateRepository.loading"
                    class="enabled:bg-green-800 enabled:border-green-700 enabled:hover:bg-green-900"
                    @click.stop="githubUpdateRepository()"
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
                    <div 
                        v-if="modals.deleteRepository.error"
                        class="w-full text-center p-2"
                    >
                        <default-alert>{{ modals.deleteRepository.error }}</default-alert>
                    </div>
                    <div 
                        v-else
                        class="w-full text-center p-2"
                    >
                        <p>Are you sure you wish to permanently delete the associated remote repository?</p>
                        <p>Assigned users will be notified.</p>
                        <p class="text-red-600 font-bold">This action is irreversible.</p>
                    </div>
                </div>
            </template>

            <template v-slot:actions>
                <default-button
                    v-if="modals.deleteRepository.error === null"
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

        <!-- Turn in assignment modal -->
        
        <modal
            :active="modals.turnInAssignment.active"
            @click.stop="() => toggleTurnInAssignmentModal()"
            @close="() => toggleTurnInAssignmentModal()"
        >
            <template v-slot:title>
                Turn in assignment
            </template>

            <template v-slot:desc>
                Submit the assignment as-is to the owner.
            </template>

            <template v-slot:content>
                <div
                    v-if="modals.turnInAssignment.loading"
                    class="flex justify-center items-center"
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
                        v-if="modals.turnInAssignment.error"
                        class="w-full text-center p-2"
                    >
                        <default-alert>{{ modals.turnInAssignment.error }}</default-alert>
                    </div>
                    <div 
                        v-else
                        class="w-full text-center p-2"
                    >
                        <p>Are you sure you wish to turn in this assignment?</p>
                        <p class="text-green-600 font-bold">This action is reversible.</p>
                    </div>
                    <div
                        v-if="assignment.remote_repository"
                        class="w-full text-center p-2 space-y-2"
                    >
                        <h3 class="">Commit history:</h3>
                        <div class="bg-zinc-800 text-left border-zinc-700 border rounded-md p-2">
                            <div
                                class="max-h-64 overflow-y-scroll"
                            >
                                <a
                                    v-for="commit, key of modals.turnInAssignment.data.commits"
                                    :key="key"
                                    :href="commit.html_url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="border-b w-96 pb-1 mb-1 border-zinc-600 flex flex-col hover:underline"
                                >
                                    <span class="font-semibold truncate"> {{ commit.commit.message }} </span>
                                    <span class="text-sm text-zinc-400 truncate">{{ commit.commit.author.name }}</span>
                                    <span class="text-sm text-zinc-400 truncate">{{ getProperDate(commit.commit.committer.date) }}</span>
                                </a>
                                <p class="text-xs text-zinc-400">Only shows the most recent 5</p>
                            </div>
                        </div>
                    </div>
                </div>
                </template>

            <template v-slot:actions>
                <default-button
                    v-if="modals.turnInAssignment.error === null"
                    text="Submit"
                    :disabled="modals.turnInAssignment.loading"
                    class="enabled:bg-yellow-800 enabled:border-yellow-700 enabled:hover:bg-yellow-900"
                    @click.stop="() => turnInAssignment()"
                />

                <default-button
                    text="Cancel"
                    :disabled="modals.turnInAssignment.loading"
                    @click.stop="() => toggleTurnInAssignmentModal()"
                />
            </template>

        </modal>

        <!-- Cancel assignment submission -->
        
        <modal
            :active="modals.revertSubmission.active"
            @click.stop="() => toggleCancelSubmissionModal()"
            @close="() => toggleCancelSubmissionModal()"
        >
            <template v-slot:title>
                Revert submission
            </template>

            <template v-slot:desc>
                Cancel the submission of this assignment
            </template>

            <template v-slot:content>
                <div
                    v-if="modals.revertSubmission.loading"
                    class="flex justify-center items-center"
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
                        v-if="modals.revertSubmission.error"
                        class="w-full text-center p-2"
                    >
                        <default-alert>{{ modals.revertSubmission.error }}</default-alert>
                    </div>
                    <div 
                        v-else
                        class="w-full text-center p-2"
                    >
                        <p>Are you sure you wish to revert the submission?</p>
                    </div>
                </div>
                </template>

            <template v-slot:actions>
                <default-button
                    v-if="modals.revertSubmission.error === null"
                    text="Submit"
                    :disabled="modals.revertSubmission.loading"
                    class="enabled:bg-yellow-800 enabled:border-yellow-700 enabled:hover:bg-yellow-900"
                    @click.stop="() => revertSubmission()"
                />

                <default-button
                    text="Cancel"
                    :disabled="modals.revertSubmission.loading"
                    @click.stop="() => toggleCancelSubmissionModal()"
                />
            </template>

        </modal>

        <!-- Feedback modal -->

        <modal
            :active="modals.giveFeedback.active"
            @click.stop="() => toggleGiveFeedbackModal()"
            @close="() => toggleGiveFeedbackModal()"
        >
            <template v-slot:title>
                Give feedback
            </template>

            <template v-slot:desc>
                Assign a numeric score and additional feedback to this assignment
            </template>

            <template v-slot:content>
                <default-alert
                    v-if="modals.giveFeedback.error"
                    class="mx-2"
                >
                    {{ modals.giveFeedback.error }}
                </default-alert>
                <div
                    v-if="modals.giveFeedback.loading"
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
                            for="assignment-feedback-review-score"
                        >
                            Numeric review score:
                        </label>
                        <default-dropdown
                            :options="dropdowns.reviewScore.options"
                            :default="dropdowns.reviewScore.selected ?? dropdowns.reviewScore.options[0]"
                            name="assignment-feedback-review-score"
                            @update="(option) => dropdowns.reviewScore.selected = option"
                        />
                    </div>
                    <div
                        class="flex flex-col space-y-0.5"
                    >
                        <label
                            for="assignment-feedback-review-score"
                        >
                            Additional feedback:
                        </label>
                        <default-text-input
                            @update="(input) => modals.giveFeedback.data.feedback = input"
                            type="textarea"
                            name="assignment-feedback-additional-feedback"
                            rows="6"
                        />
                    </div>
                </div>
            </template>

            <template v-slot:actions>
                <default-button
                    text="Apply feedback"
                    :disabled="modals.giveFeedback.loading"
                    class="enabled:bg-green-800 enabled:border-green-700 enabled:hover:bg-green-900"
                    @click.stop="giveFeedback()"
                />

                <default-button
                    text="Cancel"
                    :disabled="modals.giveFeedback.loading"
                    @click.stop="() => toggleGiveFeedbackModal()"
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
import { useUserStore, } from '../../stores/user.js'

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

    async created () {
        this.loggedInUser = useUserStore().getData
        this.fetchData().then(async () => {
            this.checkCollaborators()
        })
    },

    data () {
        return {
            loggedInUser: {},
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
                    },
                    error: null,
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
                                name: 'assignment-update-repository-private',
                                enabled: true,
                                value: false,
                            },
                        ],
                    },
                    error: null,
                },

                deleteRepository: {
                    active: false,
                    loading: false,
                    error: null,
                },

                turnInAssignment: {
                    active: false,
                    loading: false,
                    data: {
                        commits: [],
                    },
                    error: null,
                },

                revertSubmission: {
                    active: false,
                    loading: false,
                    error: null,
                },

                giveFeedback: {
                    active: false,
                    loading: false,
                    data: {
                        review: 0,
                        feedback: '',
                    },
                    error: false,
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

                reviewScore: {
                    selected: null,
                    options: [
                        {
                            label: '1',
                            value: 1,
                        },
                        {
                            label: '2',
                            value: 2,
                        },
                        {
                            label: '3',
                            value: 3,
                        },
                        {
                            label: '4',
                            value: 4,
                        },
                        {
                            label: '5',
                            value: 5,
                        },
                        {
                            label: '6',
                            value: 6,
                        },
                        {
                            label: '7',
                            value: 7,
                        },
                        {
                            label: '8',
                            value: 8,
                        },
                        {
                            label: '9',
                            value: 9,
                        },
                        {
                            label: '10',
                            value: 10,
                        },
                    ],
                },
            },

            collabInvitesSent: [],
        }
    },

    methods: {
        toSlug (string) {
            return string.toLowerCase().replace(/ /g, '-').replace(/[^A-Za-z0-9-_]/g, '')
        },

        async fetchData () {
            this.loading = true

            return axios.get(`/assignments/${this.$route.params.id}`)
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
                .catch(() => {
                    this.$router.push({name: 'assignments.index'})
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
            this.modals.newRepository.error = null
        },

        toggleUpdateRepositoryModal () {
            if (this.modals.updateRepository.loading) {
                return
            }

            this.modals.updateRepository.active = !this.modals.updateRepository.active
            this.modals.updateRepository.error = null
        },

        toggleDeleteRepositoryModal () {
            if (this.modals.deleteRepository.loading) {
                return
            }

            this.modals.deleteRepository.active = !this.modals.deleteRepository.active
            this.modals.deleteRepository.error = null
        },

        toggleTurnInAssignmentModal () {
            if (this.modals.turnInAssignment.loading) {
                return
            }

            this.allCommits()
            this.modals.turnInAssignment.active = !this.modals.turnInAssignment.active
            this.modals.turnInAssignment.error = null
        },

        toggleCancelSubmissionModal () {
            if (this.modals.revertSubmission.loading) {
                return
            }

            this.allCommits()
            this.modals.revertSubmission.active = !this.modals.revertSubmission.active
            this.modals.revertSubmission.error = null
        },

        toggleGiveFeedbackModal () {
            if (this.modals.giveFeedback.loading) {
                return
            }

            this.modals.giveFeedback.active = !this.modals.giveFeedback.active
            this.modals.giveFeedback.error = null
        },

        approveDeletionModal () {
            this.toggleDeletionModal()
            this.deleteAssignment()
        },

        turnInAssignment () {
            this.modals.turnInAssignment.loading = true

            axios.post(`/assignments/${this.assignment.id}/submit`)
                .then(() => {
                    this.modals.turnInAssignment.loading = false
                    this.modals.turnInAssignment.active = false
                    this.fetchData()
                })
                .catch((res) => {
                    this.modals.turnInAssignment.loading = false
                    this.modals.turnInAssignment.error = res.response.data.message ?? "Something went wrong"
                })
        },

        revertSubmission () {
            this.modals.revertSubmission.loading = true

            axios.delete(`/assignments/${this.assignment.id}/submit`)
                .then(() => {
                    this.modals.revertSubmission.loading = false
                    this.modals.revertSubmission.active = false
                    this.fetchData()
                })
                .catch((res) => {
                    this.modals.revertSubmission.loading = false
                    this.modals.revertSubmission.error = res.response.data.message ?? "Something went wrong"
                })
        },

        giveFeedback () {
            this.modals.giveFeedback.loading = true

            axios.post(`/assignments/${this.assignment.id}/feedback`, {
                score: this.dropdowns.reviewScore.selected.value,
                feedback: this.modals.giveFeedback.data.feedback,
            })
                .then(() => {
                    this.modals.giveFeedback.loading = false
                    this.modals.giveFeedback.active = false
                    this.fetchData()
                })
                .catch((res) => {
                    this.modals.giveFeedback.loading = false
                    this.modals.giveFeedback.error = res.message ?? "Something went wrong"
                })
        },

        validateGiveFeedback () {
            const input = this.modals.giveFeedback.data
            input.error = null

            if (input.review < 1 || input.review > 10) {
                return 'Score must be between 1 and 10'
            }

            if (input.feedback.length > 255) {
                return 'Feedback may not be longer than 255 characters'
            }

            return true
        },

        async githubNewRepository () {
            let validationErrors = this.githubValidateNewRepositoryInput()
            if (validationErrors !== true) {
                this.modals.newRepository.error = validationErrors
                return
            }

            this.modals.newRepository.loading = true

            axios.post('/integrations/github/repo', {
                assignment_id: this.assignment.id,
                integration_type: this.dropdowns.integrationType.selected.value,
                name: this.modals.newRepository.data.title,
                description: this.modals.newRepository.data.desc,
                private: this.modals.newRepository.data.options.find(option => option.name === 'assignment-new-repository-private').value,
            })
                .then(async () => {
                    this.modals.newRepository.loading = false
                    this.fetchData().then(() => {
                        this.checkCollaborators()
                    })

                    if (this.modals.newRepository.data.options.find(option => option.name === 'assignment-new-repository-invite-assignees').value) {
                        for (let user of this.assignment.users.filter(user => user.id !== this.loggedInUser.id && this.hasGithubIntegration(user) && !user.isCollaboratorToRepository)) {
                            console.log(user)
                            this.githubAddUserAsCollaborator(user.id)
                        }
                    }
                    this.toggleNewRepositoryModal()
                })
        },

        githubUpdateRepository () {
            let validationErrors = this.githubValidateUpdateRepositoryInput()
            if (validationErrors !== true) {
                this.modals.updateRepository.error = validationErrors
                return
            }

            this.modals.updateRepository.loading = true

            axios.put(`/integrations/github/repo/${this.assignment.id}`, {
                name: this.modals.updateRepository.data.title,
                description: this.modals.updateRepository.data.desc,
                private: this.modals.updateRepository.data.options.find(option => option.name === 'assignment-update-repository-private').value,
            })
                .then(() => {
                    this.modals.updateRepository.loading = false
                    this.fetchData()
                    this.toggleUpdateRepositoryModal()
                })
                .catch((res) => {
                    this.modals.updateRepository.loading = false
                    this.modals.updateRepository.error = res.response.data.message ?? "Something went wrong"
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

        githubValidateUpdateRepositoryInput () {
            const input = this.modals.updateRepository.data
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

            axios.delete(`/integrations/github/repo/${this.assignment.id}`)
                .then((res) => {
                    console.log(res)
                    this.modals.deleteRepository.loading = false
                    this.fetchData()
                    this.toggleDeleteRepositoryModal()
                })
                .catch((res) => {
                    this.modals.deleteRepository.loading = false
                    this.modals.deleteRepository.error = res.response.data.message ?? "Something went wrong"
                })
        },

        githubAddUserAsCollaborator (user) {
            axios.post(`/integrations/github/repo/${this.assignment.id}/collaborators/${user}`)
                .then((res) => {
                    console.log(res)
                    this.collabInvitesSent.push(user)
                })
        },

        hasGithubIntegration (user) {
            return user.integrations.filter((integration) => {
                return integration.network_name == 'Github'
            }).length > 0
        },

        async isCollaboratorToRepository (user) {
            return axios.get(`/integrations/github/repo/${this.assignment.id}/collaborators/${user.id}`)
                .then(() => {
                    return true
                })
                .catch(() => {
                    return false
                })
        },

        async checkCollaborators() {
            let users = [...this.assignment.users]

            if (!users.filter((user) => user.id === this.assignment.owner.id)) {
                users.push(this.assignment.owner.id)
            }

            if (!this.assignment.remote_repository) {
                return
            }

            for (let user in users) {
                user = users[user]

                if (!this.hasGithubIntegration(user)) {
                    continue
                }

                let checkCollab = async () => {
                    user.isCollaboratorToRepository = await this.isCollaboratorToRepository(user)

                    if (user.id === this.loggedInUser.id) {
                        this.loggedInUser.isCollaboratorToRepository = user.isCollaboratorToRepository
                    }
                }

                checkCollab()
            }

            console.log(users)
        },

        allCommits () {
            if (!this.assignment.remote_repository) {
                return
            }

            this.modals.turnInAssignment.loading = true
            axios.get(`/integrations/github/repo/${this.assignment.id}/commits`, {
                params: {
                    per_page: 5,
                },
            })
                .then((res) => {
                    this.modals.turnInAssignment.data.commits = res.data
                    this.modals.turnInAssignment.loading = false
                })
                .catch((res) => {
                    if (res.response === undefined) {
                        this.modals.turnInAssignment.error = "Something went wrong"
                        this.modals.turnInAssignment.loading = false

                        return
                    }

                    this.modals.turnInAssignment.error = res.response.data.message ?? "Something went wrong"                
                    this.modals.turnInAssignment.loading = false
                })
        },

        getProperDate (date) {
            let proper = new Date(date)
            return proper.toLocaleString('nl-NL')
        },
    },

    watch: {
        '$route.params' () {
            this.fetchData()
            this.collabInvitesSent = []
            useDropdownStore().setName('')
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
