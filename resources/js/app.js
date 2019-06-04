require('./bootstrap');

window.Vue = require('vue');

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import Router from "./components/routes.js"
import Auth from "./components/Auth/Auth.js";
import swal from 'vue-sweetalert2'
import {library} from "@fortawesome/fontawesome-svg-core";
import {
    faPrint,
    faDownload,
    faEdit,
    faCalendarTimes,
    faPlus,
    faPlusCircle,
    faSave,
    faEye,
    faCheckCircle,
    faFileExcel,
    faFileDownload,
    faTrash,
    faCheckSquare,
    faDollyFlatbed,
    faHandHoldingUsd,
    faShoppingCart,
    faMoneyCheck,
    faDice
} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import VueCurrencyFilter from 'vue-currency-filter'

library.add({
    faPrint,
    faMoneyCheck,
    faShoppingCart,
    faHandHoldingUsd,
    faDollyFlatbed,
    faCheckSquare,
    faTrash,
    faDownload,
    faEdit,
    faCalendarTimes,
    faPlus,
    faPlusCircle,
    faSave,
    faEye,
    faCheckCircle,
    faFileExcel,
    faFileDownload,
    faDice
})
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('form_produk', require('./components/Administrator/form_produk.vue').default)
Vue.component('form_penambahan_titipan', require('./components/Kasir/FormPenambahanTitipan.vue').default)
Vue.component('app', require('./components/App.vue').default)
Vue.config.productionTip = false
Vue.use(BootstrapVue)
Vue.use(Auth)
Vue.use(swal)
Vue.use(VueCurrencyFilter, {
    symbol: 'Rp',
    thousandsSeparator: '.',
    fractionCount: 2,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
})
Router.beforeEach(
    (to, from, next) => {
        if (to.matched.some(record => record.meta.profile)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else next()
        } else if (to.matched.some(record => record.meta.forAuth && record.meta.isAdmin)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else if (!Vue.auth.isAdmin() && to.path !== '/kasir') {
                next({
                    path: '/kasir'
                })
            } else next()
        } else if (to.matched.some(record => record.meta.forAuth && !record.meta.isAdmin)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else if (Vue.auth.isAdmin() && to.path !== '/dashboard') {
                next({
                    path: '/dashboard'
                })
            } else next()
        } else if (to.matched.some(record => record.meta.forVisitors)) {
            if (Vue.auth.isAuthenticeted() && Vue.auth.isAdmin()) {
                next({
                    path: '/dashboard'
                })
            } else if (Vue.auth.isAuthenticeted() && !Vue.auth.isAdmin()) {
                next({
                    path: '/kasir'
                })
            } else next()
        } else if (to.matched.some(record => record.meta.forAuth)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else if (!Vue.auth.isAdmin() && to.path !== '/kasir') {
                next({
                    path: '/kasir'
                })
            } else if (Vue.auth.isAdmin() && to.path !== '/dashboard') {
                next({
                    path: '/dashboard'
                })
            } else next()
        } else next()
    }
);

const app = new Vue({
    el: '#app',
    router: Router,
    mode: 'history'
});
