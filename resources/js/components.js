

window.Vue = require('vue');

Vue.component('app-template', require('./components/layouts/MainTemplate.vue').default);
Vue.component('reset-password', require('./components/auth/ResetPassword.vue').default);