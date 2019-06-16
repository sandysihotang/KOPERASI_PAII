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
    faPiggyBank,
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
    faDice,
    faChartBar
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
    faDice,
    faPiggyBank,
    faChartBar
})

import 'chart.js'
import 'hchs-vue-charts'

Vue.use(window.VueCharts)
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('form_produk', require('./components/Administrator/form_produk.vue').default)
Vue.component('form_penambahan_titipan', require('./components/Kasir/FormPenambahanTitipan.vue').default)
Vue.component('form_suplier', require('./components/Kasir/Suplier.vue').default)
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
        } else if (to.matched.some(record => record.meta.forAuth && record.meta.forAdmin)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else if ((Vue.auth.isAdmin() == 0 || Vue.auth.isAdmin() == 1) && (to.path !== '/dashboard' || to.path !== '/kasir')) {
                if (Vue.auth.isAdmin() == 0) {
                    next({
                        path: '/kasir'
                    })
                } else {
                    next({
                        path: '/dashboard'
                    })
                }
            } else next()

        } else if (to.matched.some(record => record.meta.forAuth && record.meta.isAdmin)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else if ((Vue.auth.isAdmin() == 0 || Vue.auth.isAdmin() == 2) && (to.path !== '/kasir' || to.path !== '/dashboardAdministrator')) {
                if (Vue.auth.isAdmin() == 0) {
                    next({
                        path: '/kasir'
                    })
                } else if(Vue.auth.isAdmin() == 2 && to.path == '/dashboard'){
                    next({
                        path: '/dashboardAdministrator'
                    })
                }else next()
            } else next()
        } else if (to.matched.some(record => record.meta.forAuth && !record.meta.isAdmin)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else if ((Vue.auth.isAdmin() == 1 || Vue.auth.isAdmin() == 2) && (to.path !== '/dashboard' || to.path !== '/dashboardAdministrator')) {
                if (Vue.auth.isAdmin() == 1) {
                    next({
                        path: '/dashboard'
                    })
                } else if(Vue.auth.isAdmin() == 2 && to.path == '/kasir') {
                    next({
                        path: '/dashboardAdministrator'
                    })
                }else next()
            } else next()
        } else if (to.matched.some(record => record.meta.forVisitors)) {
            if (Vue.auth.isAuthenticeted() && (Vue.auth.isAdmin() == 1 || Vue.auth.isAdmin() == 2 || Vue.auth.isAdmin() == 0)) {
                if (Vue.auth.isAdmin() == 1) {
                    next({
                        path: '/dashboard'
                    })
                } else if (Vue.auth.isAdmin() == 0) {
                    next({
                        path: '/kasir'
                    })
                } else {
                    next({
                        path: '/dashboardAdministrator'
                    })
                }
            } else next()
        } else if (to.matched.some(record => record.meta.forAuth)) {
            if (!Vue.auth.isAuthenticeted()) {
                next({
                    path: '/'
                })
            } else if ((Vue.auth.isAdmin() == 0 || Vue.auth.isAdmin() === 2) && to.path !== '/kasir') {
                next({
                    path: '/kasir'
                })
            } else if ((Vue.auth.isAdmin() == 1 || Vue.auth.isAdmin() === 2) && to.path !== '/dashboard') {
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
