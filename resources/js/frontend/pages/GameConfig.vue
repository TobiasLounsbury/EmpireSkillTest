<template>
    <div>
        <div class="container">
            <h4><router-link :to="{ name: 'play-game', params: {gameId: 'local'}}">Play a Local Game</router-link></h4>
            <strong>- or -</strong>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chess-board"></i> Start a Game with a Friend
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>I'll Play As: </label>
                        <input v-model="settings.iam" name="iam" type="radio" value="x">X -
                        <input v-model="settings.iam" name="iam" type="radio" value="o">O
                    </div>
                    <div class="form-group">
                        <label>Play against: </label>
                        <Select2 v-model="settings.secondPlayer" :options="players" />
                    </div>
                    <button v-on:click="attemptToCreateGame" :disabled="filled" >Start the Game!</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  import axios from 'axios';

  export default {
    mounted() {
      var self = this;
      axios.get('/api/players').then(response => {
        for (var i in response.data) {
          //Remove the current user from the list.
          if(response.data[i].id == window.currentUserId) {
            response.data.splice(i, 1);
          } else {
            //Set the key "text" to the nickname so select2 will populate the dropdown.
            response.data[i].text = response.data[i].nickname;
          }
        }
        self.players = response.data;
      }).catch(error => {
        console.log(error, error.response.data);
      });
    },
    data() {

      return {
        players: [],
        settings: {secondPlayer: '', iam: "x"}
      };
    },
    computed: {
      filled() {
        return !(this.settings.secondPlayer);
      }
    },
    methods: {
      attemptToCreateGame() {
        if(!this.settings.secondPlayer) {
          alert("You must select someone to play with");
          return false;
        }
        var self = this;
        axios.put('/api/game/start', this.settings).then(response => {
          //Send them to the newly created game.
          self.$router.replace({ name: 'play-game', params: { gameId: response.data.id } });
          return false;
        }).catch(error => {
          console.log(error, error.response.data);
        });

        return false;
      }
    }

  };
</script>
