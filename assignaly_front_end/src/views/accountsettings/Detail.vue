<template>
    <div v-if="tab === 'profile'">
        <!-- Settings -->
    </div>

    <div
        v-if="tab === 'integrations'"
        class="flex flex-col gap-4"
    >
        <!-- Settings -->
        <div class="flex flex-col w-fit">
            <label class="hover:cursor-text">
                Github integration:
            </label>
            <default-button
                :disabled="integrations.github.authenticated"
                text="Sign into Github"
                class="w-fit"
                @click="githubAuth()"
            />
            <p
                class="text-sm text-zinc-400"
                v-if="integrations.github.authenticated"
            >
                Logged in as {{ integrations.github.data.login }}. <a
                    class="text-white underline hover:no-underline" 
                    @click.prevent="githubAuthRevokeToken()"
                    href=""
                >
                    Logout.
                </a>
            </p>
        </div>
    </div>

    <div v-if="tab === 'privacy'">
        <!-- Settings -->
    </div>

</template>

<script>
import DefaultButton from '../../components/FormInputs/DefaultButton.vue'
import { debounce } from 'lodash'
import axios from 'axios'

export default {

    components: {
        DefaultButton,
    },

    data () {
        return {
            integrations: {
                github: {
                    authenticated: false,
                    data: {},
                },
            },
        }
    },

    watch: {
        '$route.params': {
            handler (to, from) {
                if (this.tab === 'integrations') {
                    this.githubCheckAuth()
                }

                if (to.tab.length === 3 && to.tab.includes('integrations', 'activate', 'github')) {
                    this.githubAuthGetToken(this.$route.query['code'])
                }

                console.log(import.meta.env.VITE_REMOTE_BASE_URL)
            },

            immediate: true,
        },
    },

    computed: {
        tab () {
            if (typeof(this.$route.params.tab) == 'object') {
                return this.$route.params.tab[0]
            }

            return this.$route.params.tab
        },
    },

    methods: {
        githubCheckAuth () {
            axios.get('/integrations/github/user')
                .then((res) => {
                    this.integrations.github.authenticated = true
                    this.integrations.github.data = res.data
                })
                .catch((res) => {
                    this.integrations.github.authenticated = false
                    console.log(res)
                })
        },

        githubAuth () {
            window.location = import.meta.env.VITE_REMOTE_BASE_URL + '/oauth/github'
        },

        githubAuthGetToken (code) {
            console.log(code)
            axios.post('/integrations/github/token/new', {
                code: code,
            })
                .then((res) => {
                    this.$router.push({ name: 'account.settings', params: { tab: 'integrations', }, })
                })
                .catch((res) => {
                    console.log(res)
                })
        },

        githubAuthRevokeToken () {
            axios.delete('/integrations/github/token/revoke')
                .then((res) => {
                    this.githubCheckAuth()
                    console.log(res)
                })
        },
    },
}
</script>
