import { createRouter, createWebHistory, } from 'vue-router'

// Store
import { useUserStore } from '../stores/user.js'

// Views
import Login from '@/views/authentication/Login.vue'
import Assignments from '@/views/assignments/Index.vue'
import Assignment from '@/views/assignments/Detail.vue'
import Classrooms from '@/views/classrooms/Index.vue'
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
            path: '/assignments',
            name: 'assignments',
            component: Assignments,
            meta: {
                indexed: true,
                label: 'Assignments',
                icon: 'PuzzlePiece',
            },
        },
        {
            path: '/assignment/:id',
            name: 'assignment',
            component: Assignment,
            meta: {
                indexed: false,
            },
        },
        {
            path: '/classrooms',
            name: 'classrooms',
            component: Classrooms,
            meta: {
                indexed: true,
                label: 'Classrooms',
                icon: 'AcademicCap',
            },
        },
        {
            path: '/settings',
            name: 'settings',
            component: Classrooms,
            meta: {
                indexed: true,
                label: 'Account Settings',
                icon: 'Cog6Tooth',
            },
        },
    // {
    //   path: '/about',
    //   name: 'about',
    //   // route level code-splitting
    //   // this generates a separate chunk (About.[hash].js) for this route
    //   // which is lazy-loaded when the route is visited.
    //   component: () => import('../views/AboutView.vue')
    // }
    ],
})

router.beforeResolve(async (to, from, next) => {
    const user = useUserStore()
    user.setToken(user.getToken)

    validateUser()

    if (to.name !== 'authentication.login' && user.getToken === null) {
        router.push({ name: 'authentication.login' })
    }

    if (to.name === 'authentication.login' && user.getToken !== null) {
        router.push({ name: 'assignments' })
    }

    if (to.path === '/') {
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
