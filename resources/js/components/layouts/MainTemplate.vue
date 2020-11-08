<template>
    <v-app id="inspire">
        <v-app-bar
        app
        clipped-right
        v-if="isLoggedIn"
        flat
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-spacer></v-spacer>           
            <v-menu
                bottom
                left
            >
                <template v-slot:activator="{ on, attrs }">
                <v-btn
                    icon
                    v-bind="attrs"
                    v-on="on"
                    @click="logout"
                >
                    <v-icon>mdi-logout</v-icon>
                </v-btn>
                </template>
          </v-menu>
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
                <v-list-item class="green darken-1 text-center">Active Users</v-list-item>
                <v-list-item
                    v-for="user in users"
                    :key="user.id"
                    >
                    
                    <v-list-item-icon>
                        <v-icon color="green">mdi-circle-medium</v-icon>
                    </v-list-item-icon>

                    <router-link :to="`/message/${user.username}`" class="white--text">
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ user.username }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </router-link>
                </v-list-item>
                <v-list-item class="green darken-1 text-center">Offline</v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-navigation-drawer
        v-model="left"
        fixed
        temporary
        ></v-navigation-drawer>  
        <v-main>
            <router-view :online_indicator="online_indicator"  :key="$route.fullPath"></router-view>
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
    users: [],
    mini: true,
    typing: false,
    online_indicator: "",
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
            Echo.leaveChannel('users', (e) => {
                console.log("logout")
            });
            this.$router.go({ path: '/' }); 
        }
    },
    created() {
        Echo.join('users').here((users) => {
            users.forEach(u => {
                this.users.push({
                    username: u.username, link: '/message/' + u.username
                })
            });
            this.online_indicator = "green"
         //   this.users = users
        }).joining((user) => {
             this.users.push(user)
             this.online_indicator = "green"
        }).
        leaving((user)=> {
            this.users.splice(this.users.indexOf(user),1)
            this.online_indicator = ""
        })
    },
}
</script>