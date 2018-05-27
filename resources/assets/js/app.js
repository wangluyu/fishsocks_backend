/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import App from './App.vue';
import router from './router';
import VueAuth from '@websanova/vue-auth'
import axios from 'axios';
import VueAxios from 'vue-axios';
axios.defaults.baseURL = 'http://template.io/';

Vue.use(VueAxios, axios);
Vue.use(ElementUI);

Vue.router = router;
Vue.use(VueAuth, {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js')
});
App.router = Vue.router;

const app = new Vue({
    el: '#app',
    router: router,
    render: app => app(App)
});
// new Vue({
//     el: '#app',
//     router,
//     components: { App },
//     template: '<App/>'
// })
