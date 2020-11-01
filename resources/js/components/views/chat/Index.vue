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
      messages: [],
      active_user: "",
      user_id: "",
    }
  },
  methods: {
    sendMessage () {
      let username = window.location.href.split('/').pop() // get the username from url

      //get the user id through username in the url
       
       let send_message = (message) => {

        axios.get('/api/user_id/' + username).then(response => {
          this.user_id = response.data.id;
          //send message
          axios.post('/api/send/' + this.user_id ,{
            message: message,
            sent_to: this.user_id
          });

        });
       }
      
      send_message(this.message);
      this.clearMessage()
    },
    clearMessage () {
      this.message = '';
    },
    initialize () {
      axios.get('/api/messages/' + this.user_id)
      .then((response) => {
        for(var p = 0; p < response.data.length; p++){
            var user_n = "";
            if(response.data[p].user_id == this.$store.getters.getUser.id){
                user_n = "You";
            }else{
              user_n = this.$store.getters.getUser.id;
            }
            this.messages.push({
              body: response.data[p].message,
              user: user_n
            });

        }
        
      })
      .catch(function (error) {
        console.log(error);
      })
    },
    getUserId(){
      
    }
  },
  created(){
        this.initialize();
        // Echo.join('users').here((users) => {
        //     this.users = users
        // }).joining((user) => {
        //     this.users.push(user)
        // }).leaving((user)=> {
        //     this.users.splice(this.users.indexOf(user),1)
        // })
  }
}

</script>