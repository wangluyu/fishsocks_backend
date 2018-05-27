import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router);
export default new Router({
    routes: [{
        path: '/',
        redirect: '/login',
        hidden: true
    },{
        path: '/register',
        name: 'register',
        hidden: true,
        component: resolve => require(['./components/Register'], resolve),
        meta: {
            auth: false
        }
    },{
        path: '/login',
        name: 'login',
        component: resolve => require(['./components/Login'], resolve),
        meta: {
            auth: false
        }
    },{
        path: '/',
        name: 'Hello',
        component: resolve => require(['./components/Hello'], resolve),
        meta: {
            auth: true
        }
    }]
});