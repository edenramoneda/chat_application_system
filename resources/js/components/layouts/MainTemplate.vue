<template>
    <v-app id="inspire">
        <v-app-bar
        app
        clipped-right
        v-if="isLoggedIn"
        color="primary"
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" dark></v-app-bar-nav-icon>
            <v-spacer></v-spacer>
        </v-app-bar>

        <v-navigation-drawer
        v-model="drawer"
        app
        dark
        v-if="isLoggedIn"
        >
            <v-list-item class="px-2"  color="primary">
                <v-list-item-avatar>
                    <v-img src="/uploads/user.png"></v-img>
                    </v-list-item-avatar>
                <v-list-item-title>{{ this.$store.getters.getUser.username }}</v-list-item-title>
                <v-btn
                icon
                @click.stop="mini = !mini"
                >
                </v-btn>
            </v-list-item>

            <v-divider></v-divider>
            <v-list dense>
                <v-list-item class="grey darken-1 text-center">Active Users</v-list-item>
                <v-list-item
                    v-for="user in users"
                    :key="user.id"
                    :to="`${user.username}`"
                    >
                    <v-list-item-icon>
                        <v-icon color="green">mdi-circle-medium</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ user.username }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>


                <v-list-item
                    @click="logout"
                    link
                    >
                    <v-list-item-icon>
                        <v-icon>mdi-logout</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

            </v-list>
        </v-navigation-drawer>

        <v-navigation-drawer
        v-model="left"
        fixed
        temporary
        ></v-navigation-drawer>  
        <v-main>
            <router-view></router-view>
        </v-main>
     </v-app>
</template>

<style scoped>
#sidebar{
    height:100vh;
}
a {  text-decoration: none;}
</style>
<script>
  export default {
    props: {
      source: String,
    },
    
    data: () => ({
     drawer: null,
      drawerRight: null,
      right: false,
      left: false,
        // items: [
        //     { title: 'Posts', icon: 'mdi-pin',  link: '/posts', },
        //     { title: 'Categories', icon: 'mdi-view-grid', link: '/categories' },
        //    // { title: 'My Account', icon: 'mdi-account', link: '/my-account'},
        //     { title: 'Users', icon: 'mdi-account-group-outline', link: '/users' },
        //     { title: 'Tags', icon: 'mdi-tag', link: '/tags' },
        // ],
        users: null,
        mini: true,
        typing: false
    }),

    computed: {
        isLoggedIn: function() {
            return !_.isEmpty(this.$store.getters.getUser);
        }
    },

    methods: {
        logout() {
            this.$store.commit("setUser", {});
            localStorage.removeItem('token_');
            this.$router.go({ path: '/login' });
        }
    },
    created() {
        Echo.join('users').here((users) => {
            this.users = users
        }).joining((user) => {
            this.users.push(user)
        }).
        leaving((user)=> {
            this.users.splice(this.users.indexOf(user),1)
        });
    },
  }
</script>