/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale';
import lang from 'element-ui/lib/locale/lang/en';
import 'element-ui/lib/theme-chalk/index.css';
import VueDragResize from 'vue-drag-resize';
import VueObserveVisibility from 'vue-observe-visibility';
import VueRouter from 'vue-router';
import VueWaypoint from 'vue-waypoint';
import Vuex from "vuex";
import routes from "./routes";
import store from './store';

Vue.use(VueObserveVisibility)

// configure language
locale.use(lang)


Vue.config.productionTip = false
let vMyDirective = {}
vMyDirective.install = function install(_Vue) {
    let _uid = 'vue-directive-loading' + Date.now().toString('16')
    _Vue.directive('load', {
        inserted: function (el, binding) {
            console.log({ el, binding })
            let spinner = document.createElement('div')
            spinner.id = _uid
            spinner.innerHTML = '<div class="my-spinner"></div>'
            spinner.style.display = binding.value ? 'block' : 'none'
            spinner.style['background-color'] = '#335c5e60';
            // spinner.style.color = 'red'
            // spinner.style.left = "49%";
            spinner.style.zIndex = 149;
            spinner.style['padding-top'] = "20%";
            // spinner.style.top = 0
            spinner.style.width = '100%'
            spinner.style.height = '100%'
            // spinner.style.left = 0
            // spinner.style.right = 0
            // spinner.style.bottom = 0
            // spinner.top = 0
            spinner.style.position = 'absolute'
            // el.childNodes.forEach((item) => {
            //     item.style.display = binding.value ? 'none' : ''
            // })
            el.appendChild(spinner)
        },
        update: function (el, binding, vnode) {
            let spinner = document.getElementById(_uid)
            spinner.style.display = binding.value ? 'block' : 'none'
            // el.childNodes.forEach((item) => {
            //     if (item.id === _uid) return
            //     item.style.display = binding.value ? 'none' : ''
            // })
        }
    })
}

Vue.use(vMyDirective)


Vue.component('vue-drag-resize', VueDragResize)

console.log({ VueDragResize })


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
    store: new Vuex.Store(store),
    // template: '<ExampleComponent/>'
    template: '<App />'
});
