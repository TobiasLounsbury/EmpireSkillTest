<template>
    <div class="ttt-watching">
        <div class="list-players"><span class="player-x"><router-link :to="{ name: 'player-detail', params: { playerId: gameData.player_x_id }}">{{gameData.playerX}}</router-link> (X)</span> vs. <span class="player-o"><router-link :to="{ name: 'player-detail', params: { playerId: gameData.player_o_id }}">{{gameData.playerO}}</router-link> (O)</span></div>
        <tic-tac-toe v-bind:game-data="gameData"></tic-tac-toe>
    </div>
</template>

<script>
  export default {
    mounted() {
      this.updateGameData();
      //In a "real" system this would be setup with a websocket and pub/sub for notifications of new moves
      setInterval(this.updateGameData, 10000);
    },
    data() {
      return {
        gameId: this.$route.params.gameId,
        gameData: {
          playerX: '',
          playerO: '',
        }
      }
    },
    methods: {
      updateGameData(gameId) {
        gameId = gameId || this.gameId;
        var self = this;
        axios.get('/api/game/' + encodeURIComponent(gameId)).then(response => {
          self.gameData = response.data;
        }).catch(error => {
          console.log(error, error.response.data);
        });
      },
    }
  };
</script>
