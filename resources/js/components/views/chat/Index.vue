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
          <div id="messages">
            <v-list-item
              v-for="message in messages"
              :key="message.body"
            >
              <v-list-item-content
                :class="{ 'blue darken-1 message-out': message.user === 'You', 'blue-grey lighten-1 message-in': message.user !== 'You' }"
                class="message white--text pa-sm-2" 
              >
                <v-list-item-title 
                  v-text="message.body"
                ></v-list-item-title>
              </v-list-item-content>
            </v-list-item> 
          </div>
          <div id="input_zone">
            <v-form>
              <v-container fluid>
                <v-row>
                  <v-col cols="12">
                    <v-textarea
                      v-model="message"
                      :append-outer-icon="'mdi-send'"
                      rows="2"
                      outlined
                      placeholder="Type a message..."
                      type="text"
                      @click:append-outer="sendMessage"
                    ></v-textarea>
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

<style scoped>
  .message{
    max-width: 50%; 
    border-radius: 5px;
    
  }
  .message-in{
  
  }
  .message-out{
    margin-left:67%;
  }
</style>
<script>

export default {
  data () {
    return {
      message: "",
      otherUserMessage: '',
      messages: [
        {
          body: "I'm eden",
          user: "You",
        },
         {
          body: "I'm lei ddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd3",
          user: "lei",
        }
      ]
    }
  },
  methods: {
    sendMessage () {
      this.messages.push({
        body: this.message,
        user: "You"
      })
      axios.post()
      this.clearMessage()
    },
    clearMessage () {
      this.message = '';
    },
    created() {
        // Echo.join('users').here((users) => {
        //     this.users = users
        // }).joining((user) => {
        //     this.users.push(user)
        // }).leaving((user)=> {
        //     this.users.splice(this.users.indexOf(user),1)
        // })
    },
  }
}

</script>