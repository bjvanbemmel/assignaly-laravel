import { createRouter, createWebHistory, } from 'vue-router'

// Store
import { useUserStore } from '../stores/user.js'

// Views
import Login from '@/views/authentication/Login.vue'
import Assignments from '@/views/assignments/Index.vue'
import Classrooms from '@/views/classrooms/Index.vue'

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
            path: '/',
            name: 'assignments',
            component: Assignments,
            meta: {
                indexed: true,
                label: 'Assignments',
                icon: 'PuzzlePiece',
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

    if (to.name !== 'authentication.login' && user.getToken === null) {
        router.push({ name: 'authentication.login' })
    }

    if (to.name === 'authentication.login' && user.getToken !== null) {
        router.push({ name: 'assignments' })
    }

    next();
})

export default router
