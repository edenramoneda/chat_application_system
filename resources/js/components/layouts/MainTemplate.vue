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
                                {{ user.fullname }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </router-link>
                </v-list-item>
                <v-list-item class="green darken-1 text-center">Offline</v-list-item>
                    <v-list-item
                    v-for="off_user in offline_users"
                    :key="off_user.id"
                    >
                    
                    <v-list-item-icon>
                        <v-icon color="blue-grey darken-1">mdi-circle-medium</v-icon>
                    </v-list-item-icon>

                    <router-link :to="`/message/${off_user.username}`" class="white--text">
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ off_user.fullname }}
                            </v-list-item-title>
                        </v-list-item-content>
                    </router-link>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-navigation-drawer
        v-model="left"
        fixed
        temporary
        ></v-navigation-drawer>  
        <v-main>
            <router-view  :key="$route.fullPath" :online_indicator="online_indicator"></router-view>
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
    offline_users: [], 
    }),

    computed: {
        isLoggedIn: function() {
            return !_.isEmpty(this.$store.getters.getUser);
        }
    },

    methods: {
        logout() {
            this.$store.commit("setser", {});
            localStorage.removeItem('token_');
            this.$router.go({ path: '/' }); 
            axios.post("api/logout").then(response => {
                console.log(response);
            }).catch(err => {
                console.log(err);
            });
            
        },

        allUsers(){
            axios.get("/api/all-users").then(response => {
                response.data.forEach(u => {
                    this.offline_users.push({
                        fullname: u.fullname, username: u.username
                    });
                });
               // console.log(response);
            }).catch(errors => {
               // console.log(errors);
            });
        }
    },
    created() {
        this.allUsers();

        Echo.join('users').here((users) => {
            users.forEach(u => {
                this.users.push({
                    fullname: u.fullname, username: u.username, link: '/message/' + u.username
                })  
            });
        }).joining((user) => {
            this.users.push(user)
            axios.put('/api/user/'+ user.id + '/online')
        }).
        leaving((user)=> {
            this.users.splice(this.users.indexOf(user),1)
            axios.put('/api/user/'+ user.id + '/offline')
            
        }).listen('LoginEvent', (e) => {
           // this.online_indicator = "green"
            console.log(e.user.username);

            while ( this.offline_users.findIndex(l => l.username === e.user.username ) >= 0 )
            
            this.offline_users.splice( this.offline_users.findIndex(f => f.username ===  e.user. username),1);


        }).listen('LogoutEvent', (e) => {
          //  this.online_indicator = "blue-grey darken-1"
            this.offline_users.push({
                fullname: e.fullname, username: e.username, link: '/message/' + e.username
            })  
        })
    },
    // mounted() {
        
    //     Echo.private('log-activity')
    //     .listen('LoginandOutEvent', (e) => {
    //        console.log("LoginandOutEvent" + e);
    //     }) ;       
    // }
}
</script>