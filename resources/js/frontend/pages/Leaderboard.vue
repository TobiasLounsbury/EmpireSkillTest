<template>
    <div class="container">
        <h4>Leaderboard</h4>
        <table class="table">
            <tr>
                <th class="ttt-sortable" v-bind:class="{ active: isSort('nickname') }" v-on:click="updateSort('nickname')">Player
                    <i class="fas" v-bind:class="{ [sortClass]:true }"></i>
                </th>
                <th class="ttt-sortable" v-bind:class="{ active: isSort('wins') }" v-on:click="updateSort('wins')">Wins
                    <i class="fas" v-bind:class="{ [sortClass]:true }"></i>
                </th>
                <th class="ttt-sortable" v-bind:class="{ active: isSort('losses') }" v-on:click="updateSort('losses')">losses
                    <i class="fas" v-bind:class="{ [sortClass]:true }"></i>
                </th>
                <th class="ttt-sortable" v-bind:class="{ active: isSort('draws') }" v-on:click="updateSort('draws')">Draws
                    <i class="fas" v-bind:class="{ [sortClass]:true }"></i>
                </th>
            </tr>
            <tr v-for="player in sortedPlayers">
                <td><router-link :to="{ name: 'player-detail', params: { playerId: player.id }}">{{player.nickname}}</router-link></td>
                <td>{{player.wins}}</td>
                <td>{{player.losses}}</td>
                <td>{{player.draws}}</td>
            </tr>
        </table>
    </div>
</template>
<script>
  import axios from 'axios';
  export default {
    mounted() {
      this.refreshBoard();
    },
    data() {
      return {
        players: [],
        sortAD: 'asc',
        sortColumn: 'wins'
      };
    },
    computed: {
      sortedPlayers() {
        if(!this.sortColumn) {
          return this.players;
        }

        var self = this;
        return this.players.sort(function(a, b) {
          switch(self.sortAD) {
            case "asc":
              return (a[self.sortColumn] > b[self.sortColumn]) ? -1 : 1;
            case "desc":
            default:
              return (a[self.sortColumn] < b[self.sortColumn]) ? -1 : 1;
          }
        });
      },
      sortClass() {
        return (this.sortAD == 'asc') ? "fa-sort-up" : "fa-sort-down";
      }
    },
    methods: {
      updateSort(col) {
        if(this.sortColumn == col) {
          //flip asc desc
          this.sortAD = (this.sortAD == 'asc') ? 'desc' : 'asc';
        } else {
          //Set new Sort Column
          this.sortColumn = col;
          this.sortAD = 'asc;'
        }
      },
      isSort(col) {
        return (this.sortColumn == col);
      },
      refreshBoard() {
        var self = this;
        axios.get('/api/leaderboard').then(response => {
          self.players = response.data;
        }).catch(error => {
          console.log(error, error.response.data);
        });
      }
    }
  };
</script>
