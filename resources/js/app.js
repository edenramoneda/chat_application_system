require('./bootstrap');

window.Vue = require('vue');
import vuetify from './plugins/vuetify';
import components from './components';
import axios from 'axios'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { Vuelidate  } from 'vuelidate'

import store from './store';
import router from './routes'

Vue.use(VueSweetalert2);
Vue.use(Vuelidate);

const app = new Vue({
    el: '#app',
    router,
    store,
    vuetify,
});
