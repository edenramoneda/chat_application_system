import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        user: {},
        token: ""
    },
    getters: {
        getUser: state => state.user,
        getAccessToken: state => state.user
    },
    mutations: {
        setUser(state, data) {
            state.user = data;
        },
        setAccessToken(state, token) {
            state.token = token
        }
    },
    actions: {
        //dispatch
    }
})


export default store