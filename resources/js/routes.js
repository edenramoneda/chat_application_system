
import Vue from 'vue';
import VueRouter from 'vue-router';
import _ from 'lodash';
import store from './store';
import titleMixin from './titlemixin'
import CKEditor from 'ckeditor4-vue';

Vue.use(VueRouter);
Vue.mixin(titleMixin);
Vue.use(CKEditor);

let routes = [
    {
        path: '/',
        component: require('./components/auth/Login.vue').default,
        meta: {
            requiresAuth: false
        }
    },
    {
        path: '/login',
        component: require('./components/auth/Login.vue').default,
    },
    {
        path: '/reset-password',
        component: require('./components/auth/ResetPassword.vue').default,
    },
    {
        path: '/forgot-password',
        component: require('./components/auth/ForgotPassword.vue').default,
    },
    {
        path: '/unauthorized',
        component: require('./components/layouts/Unauthorized.vue').default,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/posts',
        component: require('./components/views/chat/Index.vue').default,
        name: 'posts',
        meta: {
            requiresAuth: true,
        }
    
    },
    {
        path: '/:user_id',
        component: require('./components/views/chat/Index.vue').default,
        name: 'user_id',
        meta: {
            requiresAuth: true,
        }
    
    },
    { path: '*', redirect: '/404' },

]


const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    
    if (to.matched.some((record) => record.meta.requiresAuth)) { //to check for meta fields in route records.

        if (localStorage.getItem('token_')) {
            axios.get('/api/user/user').catch(err => {
                next('/login') //401 
            }).then(response => {
                
            store.commit("setUser", response.data);
            next();
                // console.log(response.data);

                // const protectedRoutes = ['/posts/create','/posts/update/:post_id']
                // if (protectedRoutes.includes(to.path)) {
                // //redirect to route having unauhorised message page
                //     if(response.data.user_roles.role_id == 2){
                //         next();
                //     }else{
                //         next('/unauthorized');
                //     }
                   
                // }
            });
        } else {
            next('/login') // if not, redirect to login page.
        }
    } 
    else {
        // axios.get('/api/user/user').catch(err => {
        //     next() //401 
        // }).then(response => {
        //     store.commit("setUser", response.data);
        //     next('/posts');
        // });
        next();
    }
})

export default router;