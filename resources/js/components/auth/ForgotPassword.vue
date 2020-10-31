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
                        <v-col sm="12" md="12" lg="6">
                           <img :src="'/images/blog3.jpg'" class="illus">
                        </v-col>
                        <v-col sm="12" md="12" lg="6">
                        <form>
                            <center><img :src="'/images/logo300.png'" class="bcp-logo"></center>
                            <v-text-field
                                v-model="email"
                                :error-messages="emailErrors"
                                label="Email"
                                color:p
                                required
                                @input="$v.email.$touch()"
                                @blur="$v.email.$touch()"
                            ></v-text-field>
                            
                            <v-btn @click="submit" color="primary" block>Submit</v-btn>
                             <v-btn to="/login" color="blue-grey" block  class="mt-3 white--text">Back to Login</v-btn>
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
  background-image: url("/images/bg-bcp.jpg");
  background-position: center;
  background-size: cover;
  height: 100vh;
  overflow: hidden !important;
}
.overlay {
  width: 100%;
  height: 100vh;
  background: #000046;
  /* fallback for old browsers */
  /* Chrome 10-25, Safari 5.1-6 */
  background: -webkit-gradient(
    linear,
    left top,
    right top,
    from(#268fac),
    to(#000046)
  );
  background: linear-gradient(to right, #268fac, #000046);
  /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  position: absolute;
  opacity: 0.8;
}
.flex-container {
  display: flex;
  justify-content: center;
  align-items: center;
}
.login-box {
  width: 60%;
}
.illus {
  height: 345px;
}
.bcp-logo {
  height: 80px;
}
</style>

<script>
import { required, minLength, email } from "vuelidate/lib/validators";
import { mapState, mapActions } from "vuex";
export default {
  title: 'BCP CMS | Forgot Password',
  validations: {
    email: { required, email},
  },
  data: () => ({
    showtext: false,
    email: "",
  }),
  computed: {
    emailErrors() {
      const errors = [];
      if (!this.$v.email.$dirty) return errors;
      !this.$v.email.required && errors.push("Email is required.");
      return errors;
    },

  },
  methods: {
    submit() {
      this.$v.$touch();
      axios
        .post("/forgot-password", {
          email: this.email,
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

           }
        })
        .catch(err => {});
    }
  }
};
</script>