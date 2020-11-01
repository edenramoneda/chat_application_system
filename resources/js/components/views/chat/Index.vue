<template>
  <v-app>
    <v-container
      fluid
    >
      <v-card
        elevation="2"
      >
        <v-card-title> Name of Active User </v-card-title>
        <v-card-text>
          <div id="chatroom">
              <!--x is typing goes here-->
              <div id="feedback"></div>
          </div>
          <div id="input_zone">
            <v-form>
              <v-container fluid>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="message"
                      :append-outer-icon="'mdi-send'"
                      label="Message"
                      filled
                      type="text"
                      @click:append-outer="sendMessage"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-container>
            </v-form>
          </div>
        </v-card-text>
      </v-card>
    </v-container>
  </v-app>
</template>

<script>

export default {
  data () {
    return {
      message: "",
    }
  },
  methods: {
    sendMessage () {
      this.clearMessage()
    },
    clearMessage () {
      this.message = '';
    },
  }
}

</script>