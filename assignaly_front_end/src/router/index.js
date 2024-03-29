import { createRouter, createWebHistory, } from 'vue-router'

// Store
import { useUserStore, } from '../stores/user.js'
import { useDropdownStore, } from '../stores/dropdown.js'

// Views
import Login from '@/views/authentication/Login.vue'
import PageNotFound from '@/views/pagenotfound/Index.vue'
import Dashboard from '@/views/dashboard/Index.vue'
import Assignments from '@/views/assignments/Index.vue'
import Assignment from '@/views/assignments/Detail.vue'
import AssignmentCreate from '@/views/assignments/Create.vue'
import AssignmentUpdate from '@/views/assignments/Update.vue'
import Classrooms from '@/views/classrooms/Index.vue'
import AccountSettings from '@/views/accountsettings/Index.vue'
import axios from 'axios'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/login',
            name: 'authentication.login',
            component: Login,
            meta: {
                indexed: false,
            },
        },
        {
            path: '/page-not-found',
            name: '404',
            component: PageNotFound,
        },
        {
            path: '/:pathMatch(.*)',
            redirect: '/page-not-found',
        },
        {
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: {
            },
        },
        {
            path: '/assignments',
            name: 'assignments.index',
            component: Assignments,
            meta: {
                indexed: true,
                label: 'Assignments',
                icon: 'PuzzlePiece',
            },
        },
        {
            path: '/assignments/:id',
            name: 'assignments.detail',
            component: Assignment,
            meta: {
                indexed: false,
                label: 'Assignments',
            },
        },
        {
            path: '/assignments/create',
            name: 'assignments.create',
            component: AssignmentCreate,
            meta: {
                indexed: false,
                label: 'Assignments',
            },
        },
        {
            path: '/assignments/update/:id',
            name: 'assignments.update',
            component: AssignmentUpdate,
            meta: {
                indexed: false,
                label: 'Assignments',
            },
        },
        {
            path: '/classrooms',
            name: 'classrooms.index',
            component: Classrooms,
            meta: {
                indexed: true,
                label: 'Classrooms',
                icon: 'AcademicCap',
            },
        },
        {
            path: '/account/settings/:tab(.*)*',
            name: 'account.settings',
            component: AccountSettings,
            meta: {
                indexed: true,
                label: 'Account Settings',
                icon: 'Cog6Tooth',
            },
        },
    ],
})

router.beforeResolve(async (to, _, next) => {
    const user = useUserStore()
    user.setToken(user.getToken)

    useDropdownStore().setName('')

    validateUser()

    if (to.name !== 'authentication.login' && user.getToken === null) {
        router.push({ name: 'authentication.login' })
    }

    if (to.name === 'authentication.login' && user.getToken !== null) {
        router.push({ name: 'assignments' })
    }

    next();
})

function validateUser() {
    const user = useUserStore()
    axios.get('/auth/validate')
        .catch(() => {
            user.setToken(null)
            router.push({ name: 'authentication.login' })
        })
}

export default router
