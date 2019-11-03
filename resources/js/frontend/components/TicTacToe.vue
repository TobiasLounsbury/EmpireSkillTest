<template>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-chess-board"></i> Tic-Tac-Toe Game Board
            <span class="ttt-current-player" v-if="!gameData.winner">Current Player: {{currentPlayer}}</span>
        </div>
        <div class="card-body">

            <div class="container">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300" class="ttt-view" v-on:click="clickHandler">
                    <!-- Horizontal Lines //-->
                    <line x1="3" y1="100" x2="297" y2="100" class="ttt-game-hash"/>
                    <line x1="3" y1="200" x2="297" y2="200" class="ttt-game-hash"/>
                    <!-- Verticals //-->
                    <line x1="100" y1="3" x2="100" y2="297" class="ttt-game-hash"/>
                    <line x1="200" y1="3" x2="200" y2="297" class="ttt-game-hash"/>

                    <text x="50" y="50" class="ttt-play">{{squares[0]}}</text>
                    <text x="150" y="50" class="ttt-play">{{squares[1]}}</text>
                    <text x="250" y="50" class="ttt-play">{{squares[2]}}</text>

                    <text x="50" y="150" class="ttt-play">{{squares[3]}}</text>
                    <text x="150" y="150" class="ttt-play">{{squares[4]}}</text>
                    <text x="250" y="150" class="ttt-play">{{squares[5]}}</text>

                    <text x="50" y="250" class="ttt-play">{{squares[6]}}</text>
                    <text x="150" y="250" class="ttt-play">{{squares[7]}}</text>
                    <text x="250" y="250" class="ttt-play">{{squares[8]}}</text>

                    <line :x1="winStrike.x1" :y1="winStrike.y1" :x2="winStrike.x2" :y2="winStrike.y2" class="ttt-win-strike" v-if="showWin" />

                    <text x="150" y="150" transform="rotate(25 150,150)" class="ttt-draw" v-if="showDraw">Draw</text>

                </svg>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    mounted() {
      //console.log('Component mounted.')
    },

    props: ["gameData"],
    data() {
      return {
        localData: this.gameData
      };
    },
    methods: {
      clickHandler(e) {
        if(e.target.nodeName == "svg") {
          const rect = e.target.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          const w = rect.width / 3;
          const h = rect.height / 3;

          let square = (3 * Math.floor(y / h)) + Math.floor(x / w);
          this.$emit('clickInSquare', square);
        }
      }
    },
    computed: {
      showWin() {return (this.gameData.winner === 'o' || this.gameData.winner === 'x')},
      showDraw() {return (this.gameData.winner === 'd')},
      squares() {
        let squares = [];
        for (var i in _.range(0,9)) {
          squares[i] = (this.gameData && this.gameData.squares && this.gameData.squares.hasOwnProperty(i)) ? this.gameData.squares[i] : '';
        }
        return squares;
      },
      currentPlayer() {return (this.gameData.currentPlayer) ? this.gameData.currentPlayer.toUpperCase() : '';},
      winStrike() {
        switch(parseInt(this.gameData.strike)) {

          //Horizontal Wins
          case 2:
            return {x1: 25, x2: 275, y1: 50, y2: 50};
          case 35:
            return {x1: 25, x2: 275, y1: 150, y2: 150};
          case 68:
            return {x1: 25, x2: 275, y1: 250, y2: 250};

          //Vertical Wins
          case 6:
            return {x1: 50, x2: 50, y1: 25, y2: 275};
          case 17:
            return {x1: 150, x2: 150, y1: 25, y2: 275};
          case 28:
            return {x1: 250, x2: 250, y1: 25, y2: 275};

          //Vertical Wins
          case 8:
            return {x1: 25, x2: 275, y1: 25, y2: 275};
          case 26:
            return {x1: 275, x2: 25, y1: 25, y2: 275};

          default:
            return {x1: -1, x2: -1, y1: -1, y2: -1};
        }
      }
    }
  }
</script>
