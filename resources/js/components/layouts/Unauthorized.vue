<template>
  <v-app>
      <v-container>
        <v-card  class="mt-16 mx-auto" max-width="500" outlined>
            <v-container>
                <center>
                    <img src="/images/error.png" height="90" width="100">
                    <v-card-text>
                        <h3>Unauthorized</h3>
                        <p>You are not allowed to access this page</p>
                        <router-link to="/posts" text-color="white">Go Back</router-link>
                    </v-card-text>
                </center>
            </v-container>
        </v-card>
      </v-container>
  </v-app>
</template>