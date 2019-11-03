<template>
    <div class="ttt-playing" v-bind:class="{ active: myTurn }">
        <div v-if="!isLocal" class="list-players"><span class="player-x"><router-link :to="{ name: 'player-detail', params: { playerId: gameData.player_x_id }}">{{gameData.playerX}}</router-link> (X)</span> vs. <span class="player-o"><router-link :to="{ name: 'player-detail', params: { playerId: gameData.player_o_id }}">{{gameData.playerO}}</router-link> (O)</span></div>
        <tic-tac-toe v-bind:game-data="gameData" v-on:clickInSquare="clickInSquare"></tic-tac-toe>
        <button v-on:click="playAgain" v-if="showPlayAgain">Play Again!</button>
    </div>
</template>

<script>
  export default {
    mounted() {
      if(this.$route.params.gameId !== 'local') {
        this.updateGameData(this.$route.params.gameId);
        setInterval(this.updateGameData, 10000);
      }
    },
    data() {
      return {
        gameData: {currentPlayer: 'x', winner: ''},
        gameId: this.$route.params.gameId,
        isLocal: (this.$route.params.gameId === 'local'),
        playingAs: 'both'
      }
    },
    computed: {
      showPlayAgain(){ return (!!(this.gameData.winner) && this.isLocal);},
      myTurn() {
        //In local games it is always the browser user's turn
        if (this.isLocal) {
          return true;
        }
        return (this.gameData.currentPlayer === this.playingAs);
      }
    },
    methods: {
      clickInSquare(square) {
        if(this.isLocal) {
          this.localClick(square);
        } else {
          this.attemptPlay(square);
        }
      },
      attemptPlay(square) {
        if(this.myTurn) {
          //Check to see if the square is empty
          if (!this.gameData.squares.hasOwnProperty(square) || this.gameData.squares[square].length === 0) {

            var self = this;
            axios.post('/api/game/' + encodeURIComponent(this.gameId) + '/play', {"square": square, player: this.playingAs, gameId: this.gameId}).then(response => {
              self.$set(self, 'gameData', response.data.gameData);
              self.setPlayingAs();
            }).catch(error => {
              console.log(error, error.response.data);
            });

          }
        }
      },

      localClick(square) {
        if(!this.showPlayAgain) {
          if(!this.gameData.hasOwnProperty("squares")) {this.$set(this.gameData, 'squares', {});}
          if (!this.gameData.squares.hasOwnProperty(square) || this.gameData.squares[square].length === 0) {
            //Set the Square Value
            this.$set(this.gameData.squares, square, this.gameData.currentPlayer);
            //Switch Players
            this.gameData.currentPlayer = (this.gameData.currentPlayer === 'x') ? 'o' : 'x';

            //check for a win condition
            const wins = {2: [0,1,2],35: [3,4,5],68: [6,7,8], 6: [0,3,6],17: [1,4,7],28: [2,5,8],8: [0,4,8],26: [2,4,6]};
            for(var c in wins) {
              if(this.conditionMet(wins[c])) {
                this.gameData.winner = this.gameData.squares[wins[c][0]];
                this.$set(this.gameData, "strike", c);
              }
            }
            //check for Draw
            if(this.isDraw()) {
              this.gameData.winner = 'd';
            }
          }
        }
      },
      conditionMet(win) {
        //Make sure we have data
        if(this.gameData.hasOwnProperty("squares")) {
          //Make sure that all keys exist so we don't throw errors
          if (this.gameData.squares.hasOwnProperty(win[0]) && this.gameData.squares.hasOwnProperty(win[1]) && this.gameData.squares.hasOwnProperty(win[2])) {
            //Make sure that the squares are not all blank
            if (this.gameData.squares[win[0]]) {
              //Check if all squares match
              if (this.gameData.squares[win[0]] == this.gameData.squares[win[1]]) {
                return (this.gameData.squares[win[0]] == this.gameData.squares[win[2]])
              }
            }
          }
        }
        return false;
      },
      isDraw() {
        //Make sure there is no winner
        if(!this.gameData.winner) {
          //Check all squares to see if they are empty (no index defaults to empty)
          for (var i in _.range(0,9)) {
            if(!this.gameData.squares.hasOwnProperty(i) || this.gameData.squares[i] == '') {
              return false;
            }
          }
          return true;
        }
        return false;
      },
      //Reset the Game Board
      playAgain() {
        for (var i in _.range(0,9)) {
          this.$set(this.gameData.squares, i, '');
        }
        this.$set(this.gameData, 'winner', '');

      },
      setPlayingAs() {
        //If we aren't logged in you can only play local games
        if (window.currentUserId) {
          if (this.gameData.hasOwnProperty("player_x_id") && this.gameData.hasOwnProperty("player_o_id")) {
            //Map current user id to either x, o, or false
            let cid = (this.gameData.player_x_id == window.currentUserId) ? 'x' : 'both';
            cid = (this.gameData.player_o_id == window.currentUserId) ? 'o' : cid;

            //Set the players character
            if(cid !== this.playingAs) {
              this.$set(this, 'playingAs', cid);
            }
          }
        }
      },
      //Fetch updated game data from the API
      updateGameData(gameId) {
        gameId = gameId || this.gameId;
        var self = this;
        axios.get('/api/game/' + encodeURIComponent(gameId)).then(response => {
          self.$set(self, 'gameData', response.data);
          self.setPlayingAs();
        }).catch(error => {
          console.log(error, error.response.data);
        });
      },
    }
  };
</script>
