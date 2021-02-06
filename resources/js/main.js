import 'core-js/stable'
import Vue from 'vue'
import App from './app/App'
import router from './router'
import CoreuiVue from '@coreui/vue'
import * as icons from '@coreui/icons'
import VueResource from 'vue-resource';
import store from './store'

Vue.config.performance = true
Vue.use(CoreuiVue)
Vue.use(VueResource);

Vue.http.options.root = '/api/';
Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem("api_token");

new Vue({
    el: '#app',
    router,
    store,
    icons,
    template: '<App/>',
    components: {
        App
    },
})
