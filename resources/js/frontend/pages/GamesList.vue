<template>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chess-board"></i> Active Games
        </div>
        <div class="card-body">
            <ul id="games-list" v-if="games.length" class="list-group">
                <li v-for="game in games"  class="list-group-item">
                    {{ game.id }}:
                    <router-link v-if="isPlayer(game)" :to="{ name: 'play-game', params: { gameId: game.id }}">{{game.playerX}} vs. {{game.playerO}}</router-link>
                    <router-link v-if="!isPlayer(game)" :to="{ name: 'watch-game', params: { gameId: game.id }}">{{game.playerX}} vs. {{game.playerO}}</router-link>
                </li>
            </ul>
            <div v-if="!games.length" class="align-content-center">No Current Games</div>
        </div>
    </div>
</template>
<script>
  import axios from 'axios';
  export default {
    mounted() {
      this.refreshList();
      //In a "real" system this would be setup with a websocket and pub/sub for notifications of new games
      setInterval(this.refreshList, 30000);
    },
    data() {
      return {
        games: []
      };
    },
    methods: {
      isPlayer(game) {
        return (!game.winner && (game.player_x_id == window.currentUserId || game.player_o_id == window.currentUserId));
      },
      refreshList() {
        var self = this;
        axios.get('/api/games').then(response => {
          self.games = response.data;
        }).catch(error => {
          console.log(error, error.response.data);
        });
      }
    }
  };
</script>
