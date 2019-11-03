<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class Game extends Model
{
    //List of win conditions
    public $winConditions = array(2 => [0,1,2], 35 => [3,4,5], 68 => [6,7,8], 6=> [0,3,6], 17=> [1,4,7], 28 => [2,5,8], 8 => [0,4,8], 26 => [2,4,6]);

    /**
     * Add a new move
     *
     * @param $square
     * @param $player
     * @throws \ErrorException
     */
    public function addMove($square, $player) {
        if(!$this->winner && $player == $this->currentPlayer()) {
            $move = Move::where([["game_id", "=", $this->id], ["square", "=", $square]])->get();

            if($move->count() > 0) {
                throw new \ErrorException("Play Already exists in that Square");
            }

            $move = new Move();
            $move->game_id = $this->id;
            $move->player = $player;
            $move->square = $square;
            $move->save();
            $this->load('moves');
        }
        $this->checkForWinState();
    }

    /**
     * Helper function that will check the game for proper save states
     */
    public function checkForWinState() {
        $squares = $this->buildSquaresArray();
        foreach($this->winConditions as $strike => $cond) {
            if($this->conditionMet($cond, $squares)) {
                $this->winner = $squares[$cond[0]];
                $this->strike = $strike;
                $this->save();
            }
        }

        //check for Draw
        if($this->isDraw($squares)) {
            $this->winner = 'd';
            $this->save();
        }
    }

    public function isDraw($squares = false) {
        if(!$squares) {
            $squares = $this->buildSquaresArray();
        }
        return !(in_array("", $squares));
    }

    private function conditionMet($cond, $squares = false) {
        if(!$squares) {
            $squares = $this->buildSquaresArray();
        }
        if (!$squares[$cond[0]] == "") {
            return ($squares[$cond[0]] == $squares[$cond[1]] && $squares[$cond[0]] == $squares[$cond[2]]);
        }
        return false;
    }

    /**
     * Return a formatted array of squares.
     *
     * @return array
     */
    public function buildSquaresArray() {
        $squares = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "");
        foreach ($this->moves as $move) {
            $squares[$move->square] = $move->player;
        }
        return $squares;
    }

    /**
     * This returns who is playing next.
     *
     * todo: Refactor this to be a bit more efficient/robust
     *
     * @return string
     */
    public function currentPlayer() {
        $p = 'x';
        foreach ($this->moves as $move) {
            $p = ($move->player == 'x') ? 'o' : 'x';
        }
        return $p;
    }

    /**
     * Creates the relationship with the Move model
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moves(){
        return $this->hasMany("App\Models\Move");
    }

    /**
     * Create relationship with Player X
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function playerX(){
        return $this->hasOne("App\Models\Auth\User", "id", "player_x_id");
    }

    /**
     * Create Relationship with Player O
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function playerO(){
        return $this->hasOne("App\Models\Auth\User", "id", "player_o_id");
    }

    /**
     * Helper function to return both players;
     *
     * @return mixed
     */
    public function players(){
        return $this->playerX()->merge($this.$this->playerO());
    }


}
