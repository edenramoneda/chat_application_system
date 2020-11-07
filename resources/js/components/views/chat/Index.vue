<template>
  <v-app>

      <v-card
        outlined
      >
        <v-card-title>
          <v-icon :color="online_indicator">mdi-circle-medium</v-icon>
          {{ active_user}} 
        </v-card-title>
         <v-divider></v-divider>
        <v-card-text class="message-body">
          <div id="messages">
            <v-list-item
              v-for="message in messages"
              :key="message.key"
              class="mt-4"
            >

              <v-list-item-content
                :class="{ 'green accent-4 message-out': message.user === 'You', 'teal darken-2 message-in': message.user !== 'You' }"
                class="message white--text pa-sm-2" 
              >
                <v-list-item-title 
                  v-text="message.body"
                > 
                </v-list-item-title>
              </v-list-item-content>
              <p :class="{ 'date-message-out': message.user === 'You', 'date-message-in': message.user !== 'You' }">
                  <span> {{ message.created_at }} </span>
              </p>
            </v-list-item> 
          </div>
          
          <div>
            <span v-for="current in users_currently_typing" :key="current.user" class="help-block" style="font-style: italic;">
                {{ current.user }} is typing...
            </span>
          </div>
          <v-divider></v-divider>
          <div id="input_zone">
            <v-form color="grey lighten-5" class="form">
              <v-container fluid>
                <v-textarea
                  v-model="message"
                  :append-outer-icon="'mdi-send'"
                  dense
                  flat
                  hide-details
                  rows="2"
                  outlined
                  placeholder="Type a message..."
                  type="text"
                  clear-icon="mdi-close-circle"
                  clearable
                  @click:append-outer="sendMessage"
                  @keydown="isTyping"
                  @blur="nottyping"
                  required
                  >
                    <template v-slot:prepend>
                        <emoji-picker @emoji="insert" :search="search">
                          <div
                            class="emoji-invoker"
                            slot="emoji-invoker"
                            slot-scope="{ events: { click: clickEvent } }"
                            @click.stop="clickEvent"
                          >
                            <v-icon>mdi-emoticon-happy</v-icon>
                          </div>
                          <div slot="emoji-picker" slot-scope="{ emojis, insert, display }">
                            <div class="emoji-picker">
                              <div class="emoji-picker__search">
                                <input type="text" v-model="search" v-focus>
                              </div>
                              <div>
                                <div v-for="(emojiGroup, category) in emojis" :key="category">
                                  <h5>{{ category }}</h5>
                                  <div class="emojis">
                                    <span
                                      v-for="(emoji, emojiName) in emojiGroup"
                                      :key="emojiName"
                                      @click="insert(emoji)"
                                      :title="emojiName"
                                    >{{ emoji }}</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </emoji-picker>
                    </template>
                </v-textarea>
              </v-container>
            </v-form>
          </div>
        </v-card-text>
      </v-card>
  </v-app>
</template>

<script>
export default {
  title: "Aerolink | Messenger | " + window.location.href.split("/").pop(),
  props:{
    online_indicator: String
  },
  data() {
    return {
      message: "",
      messages: [],
      active_user: "",
      user_id: "",
      typing: false,
      users_currently_typing: [],
      input: '',
      search: '',
    };
  },
  methods: {
       insert(emoji) {
      this.message += emoji
    },
    sendMessage() {

      let username = window.location.href.split("/").pop(); // get the username from url

      //get the user id through username in the url
      //  @click="$router.push({ path: `/message/${user.username}` })"
          
      let send_message = message => {
        axios.get("/api/user_id/" + username).then(response => {
          this.user_id = response.data.id;
          //send message
          axios.post("/api/send/" + this.user_id, {
            message: message,
            sent_to: this.user_id
          });

          this.messages.push({
            body: message,
            user: "You"
          });

        });
      };
      if(!this.message == ""){
        send_message(this.message);
        this.clearMessage();
      }
    },
    clearMessage() {
      this.message = "";
    },

    initialize() {
      let username = window.location.href.split("/").pop(); // get the username from url
      let created_at = [];
      axios.get("/api/user_id/" + username).then(response => {
        this.user_id = response.data.id;
        this.active_user = response.data.username;

        axios
          .get("/api/messages/" + this.user_id)
          .then(response => {
            for (var p = 0; p < response.data.length; p++) {
              var user_n = "";
              if (response.data[p].sent_to != this.$store.getters.getUser.id) {
                user_n = "You";
              }

              var date = new Date(response.data[p].created_at);

              var timeofdate = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
              this.messages.push({
                body: response.data[p].message,
                user: user_n,
                created_at: date.toDateString() + " " + timeofdate
              });

              //sort message by created_at
              this.messages.sort(
                (a, b) => (a.created_at > b.created_at ? 1 : -1)
              );
            }

            document.getElementById('messages').scrollTop  = document.getElementById('messages').scrollHeight + 100;
          })
          .catch(function(error) {
            console.log(error);
          });
      });

                       
    },
    isTyping() {
      
      let listenChannel = "chat-" + window.location.href.split("/").pop();
      let channel = Echo.private(listenChannel);
      channel.whisper("typing", {
        user: this.$store.getters.getUser.username,
        typing: true
      });
      
    },

    nottyping() {
      let listenChannel = "chat-" + window.location.href.split("/").pop();
      let channel = Echo.private(listenChannel);
      channel.whisper("typing", {
        user: this.$store.getters.getUser.username,
        typing: false
      });

    }
  },
  created() {
    this.initialize();
  },
  mounted() {

    Echo.private(`chat-${this.$store.getters.getUser.username}`)
    .listenForWhisper("typing", e => {
      let getIndex = arr => {
        return this.users_currently_typing.findIndex(
          currently_typing => currently_typing.user === arr.user
        );
      };

      let entity_index = getIndex(e);

      if (entity_index === -1) {
        this.users_currently_typing.push(e); //add user to currently typing
        entity_index = getIndex(e);
      }

      if (entity_index !== -1 && !e.typing) {
        this.users_currently_typing.splice(entity_index, 1); //remove user form currently typing
      }
    });

    Echo.private('chat-sent-to-' + this.$store.getters.getUser.id).listen("ChatEvent", (e) => {
      if(e.messages.sent_to === this.$store.getters.getUser.id) {
        this.messages.push({
          body: e.messages.message,
          user: ""
        });
        //automatic scroll to down when someone sent you a message
        document.getElementById('messages').scrollTop  = document.getElementById('messages').scrollHeight + 100;

        //play sound when someone sent you a message
         var audio = new Audio("http://soundbible.com/mp3/Elevator Ding-SoundBible.com-685385892.mp3");
        audio.play();
      }
    });

  },
  directives: {
    focus: {
      inserted(el) {
        el.focus()
      },
    },
  },
};
</script>