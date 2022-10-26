import { createApp, } from 'vue'
import { createPinia, } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:84/api/'

import App from './App.vue'
import router from './router'

import './assets/main.css'

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

const app = createApp(App)

app.use(pinia)
app.use(router)

// Global Components
import icon from './components/HeroIcon.vue'
app.component(icon.name, icon)

app.mount('#app')
