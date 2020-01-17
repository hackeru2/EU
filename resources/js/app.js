/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import VueRouter from 'vue-router';
import VueWaypoint from 'vue-waypoint';
import Vuex from "vuex";
import routes from './routes';
import store from './store';

// Waypoint plugin
Vue.use(VueWaypoint)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
// Vue.filter('secondInArray', function (value) {
//     console.log({ value })
//     return value[1]
// })
//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.use(ElementUI);
Vue.use(VueRouter);
Vue.use(Vuex);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',

    router: new VueRouter(routes),
    store: new Vuex.Store(store)
    // template: '<ExampleComponent/>'
});
