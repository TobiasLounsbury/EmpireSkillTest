<template>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-user"></i> {{player.nickname}}
        </div>
        <div class="card-body">
            <div class="card-group">
                <div class="card border-success">
                    <div class="card-body">
                        <strong class="card-title">Wins: </strong>
                        <span class="card-text">{{player.stats.totalWins}} ( {{player.stats.winPercentage}}% )</span>
                    </div>
                </div>
                <div class="card border-danger">
                    <div class="card-body">
                        <strong class="card-title">Losses: </strong>
                        <span class="card-text">{{player.stats.totalLosses}} ( {{player.stats.lossPercentage}}% )</span>
                    </div>
                </div>
                <div class="card border-info">
                    <div class="card-body">
                        <strong class="card-title">Draws: </strong>
                        <span class="card-text">{{player.stats.totalDraws}} ( {{player.stats.drawPercentage}}% )</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chess-board"></i> Games
                </div>
                <div class="card-body">
                    <ul id="player-games-list" v-if="games.length" class="list-group" >
                        <li v-for="game in sortedGames" class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ game.id }}: <router-link :to="{ name: 'watch-game', params: { gameId: game.id }}">vs. {{opponent(game)}}</router-link></span>
                            <span class="badge badge-pill" v-bind:class="wonLostClass(game)">{{wonLostTitle(game)}}</span>
                        </li>
                    </ul>
                    <div v-if="!games.length" class="align-content-center">No Current Games</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  import axios from 'axios';
  export default {
    mounted() {
      this.loadGames();
      this.loadPlayer();
    },
    data() {
      return {
        player: {"stats": {}},
        games: [],
        playerId: this.$route.params.playerId
      };
    },
    computed: {
      sortedGames() {
        var self = this;
        return this.games.sort(function(a, b) {
          return b.id - a.id;
        });
      },
    },
    methods: {
      opponent(game) {
        if(game.player_x_id == this.player.id) {
          return game.playerO;
        } else {
          return game.playerX;
        }
      },
      wonLostTitle(game) {
        if(!game.winner) {
          return "Active";
        } else if (game.winner == 'd') {
          return "Draw";
        } else if ((game.player_x_id == this.player.id && game.winner == 'x') || (game.player_o_id == this.player.id && game.winner == 'o')) {
          return 'Win';
        } else {
          return 'Loss';
        }
      },
      wonLostClass(game) {
        if(!game.winner) {
          return "badge-primary";
        } else if (game.winner == 'd') {
          return "badge-info";
        } else if ((game.player_x_id == this.player.id && game.winner == 'x') || (game.player_o_id == this.player.id && game.winner == 'o')) {
          return 'badge-success';
        } else {
          return 'badge-danger';
        }
      },
      loadGames() {
        var self = this;
        axios.get('/api/player/' + this.playerId + '/games').then(response => {
          self.games = response.data;
        }).catch(error => {
          console.log(error, error.response.data);
        });
      },
      loadPlayer() {
        var self = this;
        axios.get('/api/player/' + this.playerId).then(response => {
          self.player = response.data;
        }).catch(error => {
          console.log(error, error.response.data);
        });
      }
    }
  };
</script>
