
require('./bootstrap')

import { createApp } from 'vue'

import Sports from './components/sports.vue'

const app = createApp({})

app.component('sports', Sports)

app.mount("#app");
