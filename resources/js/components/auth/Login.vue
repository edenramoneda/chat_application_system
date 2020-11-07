<template>
  <v-app>
    <div class="login-page flex-container">
        <div class="overlay"></div>
        <v-container>
            <v-card
                class="mx-auto login-box"
                outlined
            >
                <v-container>
                    <v-row>
                        <v-col sm="12" md="12" lg="12">
                        <form>
                            <v-text-field
                                v-model="username"
                                :error-messages="usernameErrors"
                                label="Username"
                                color:p
                                required
                                @input="$v.username.$touch()"
                                @blur="$v.username.$touch()"
                            ></v-text-field>
                            <v-text-field
                                v-model="password"
                                :error-messages="passwordErrors"
                                :append-icon="showtext ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="showtext ? 'text' : 'password'"
                                label="Password"
                                @click:append="showtext = !showtext"
                                required
                            ></v-text-field><br>
                            
                            <v-btn @click="submit" color="primary" block>Login</v-btn>
                               <v-btn
                                color="blue-grey"
                                class="mt-3 white--text"
                                block
                                to="/forgot-password"
                                >
                                Forgot Password
                                </v-btn>
                        </form>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card>
        </v-container>
    </div>
  </v-app>
</template>

<style scoped>
.login-page {
  height: 100vh;
  overflow: hidden !important;
}
.overlay {
  width: 100%;
  height: 100vh;
  background: #0F2027;  /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #2C5364, #203A43, #0F2027);  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #2C5364, #203A43, #0F2027); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  position: absolute;
  opacity: 0.8;
}
.flex-container {
  display: flex;
  justify-content: center;
  align-items: center;
}
.login-box {
  width: 50%;
}
.illus {
  height: 345px;
}
.bcp-logo {
  height: 80px;
}
</style>

<script>
import { required, minLength } from "vuelidate/lib/validators";
import { mapState, mapActions } from "vuex";
export default {
  title: 'BCP CMS | Login',
  validations: {
    username: { required },
    password: { required, minLength: minLength(8) }
  },
  data: () => ({
    showtext: false,
    username: "",
    password: ""
  }),
  computed: {
    usernameErrors() {
      const errors = [];
      if (!this.$v.username.$dirty) return errors;
      !this.$v.username.required && errors.push("Username is required.");
      return errors;
    },
    passwordErrors() {
      const errors = [];
      if (!this.$v.password.$dirty) return errors;
      !this.$v.password.minLength &&
        errors.push("Password must be at least 8 characters");
      !this.$v.password.required && errors.push("Password is required.");
      return errors;
    }
  },
  methods: {
    submit() {
      this.$v.$touch();
      axios
        .post("api/user/login", {
          username: this.username,
          password: this.password
        })
        .then(response => {
          if (response.data.error) {
            Vue.swal({
              icon: "error",
              title: "Oops...",
              text: "Incorrect Username or Password!"
            }).then(() => {
              this.username = "";
              this.password = "";
            });
          } else {
              if(response.data.data.is_deleted == 1){
                  Vue.swal({
                  icon: "error",
                  title: "Oops...",
                  text: "You have no longer access to this system"
                }).then(() => {
                  this.username = "";
                  this.password = "";
                });
              }else{
                  Vue.swal({
                    icon: "success",
                    text: "Logged in Successfully"
                  });

                  this.$store.commit("setUser", response.data.data);
                  this.$store.commit(
                    "setAccessToken",
                    `Bearer ${response.data.access_token}`
                  );

                  localStorage.setItem(
                    "token_",
                    `Bearer ${response.data.access_token}`
                  );

                  axios.defaults.headers.common[
                    "Authorization"
                  ] = localStorage.getItem("token_");
                  // this.$router.push('');

                  window.location.href = '/welcome';
              }
           }
        })
        .catch(err => {});
    }
  }
};
</script>