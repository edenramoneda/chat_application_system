<template>
  <v-app>

      <v-card
        outlined
      >
        <v-card-title 
        
        > {{ active_user}} </v-card-title>
         <v-divider></v-divider>
        <v-card-text class="message-body">
          <div id="messages">
            <v-list-item
              v-for="message in messages"
              :key="message.key"
            >
           
              <v-list-item-content
                :class="{ 'blue darken-1 message-out': message.user === 'You', 'blue-grey lighten-1 message-in': message.user !== 'You' }"
                class="message white--text pa-sm-2" 

              >
                                 <template v-slot:prepend>
              <span class="help-block" style="font-style: italic;">
                {{ message.created_at}}
              </span>
              </template>
                <v-list-item-title 
                  v-text="message.body"
                >
                
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item> 
          </div>
          
          <div>
            <span v-for="current in users_currently_typing" :key="current.user" class="help-block" style="font-style: italic;">
                {{ current.user }} is typing...
            </span>
          </div>
          <v-divider></v-divider>
          <div id="input_zone">
            <v-form color="grey lighten-5">
              <v-container fluid>
                <v-textarea
                  v-model="message"
                  :append-outer-icon="'mdi-send'"
                  rows="2"
                  outlined
                  placeholder="Type a message..."
                  type="text"
                  clear-icon="mdi-close-circle"
                  clearable
                  @click:append-outer="sendMessage"
                  @keydown="isTyping"
                  @blur="nottyping"
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

  data() {
    return {
      message: "",
      otherUserMessage: "",
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

      send_message(this.message);
      this.clearMessage();
      
            Echo.channel('chat-sent-to-' + window.location.href.split("/").pop()).listen("ChatEvent", e=> {
              console.log("MESSAGE")
            })
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

              this.messages.push({
                body: response.data[p].message,
                user: user_n,
                created_at: response.data[p].created_at
              });

              //sort message by created_at
              this.messages.sort(
                (a, b) => (a.created_at > b.created_at ? 1 : -1)
              );
            }
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