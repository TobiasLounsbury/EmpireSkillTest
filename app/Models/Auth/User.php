<?php

namespace App\Models\Auth;

use App\Models\Auth\Traits\Scope\UserScope;
use App\Models\Auth\Traits\Method\UserMethod;
use App\Models\Auth\Traits\Attribute\UserAttribute;
use App\Models\Auth\Traits\Relationship\UserRelationship;
use App\Models\Game;

/**
 * Class User.
 */
class User extends BaseUser
{
    use UserAttribute,
        UserMethod,
        UserRelationship,
        UserScope;

    public function gamesAsX() {
        return $this->hasMany('App\Models\Game', 'player_x_id');
    }

    public function gamesAsO() {
        return $this->hasMany('App\Models\Game', 'player_o_id');
    }

    public function games() {
        return $this->gamesAsX->merge($this->gamesAsO);
    }


    /**
     * Return the number of wins
     *
     * @return mixed
     */
    public function totalWinCount() {
        return  Game::where([["winner", "=", "x"], ['player_x_id', '=', $this->id]])->orWhere([["winner", "=", "o"], ['player_o_id', '=', $this->id]])->count();
    }

    /**
     * Count of wins playing as X
     *
     * @return mixed
     */
    public function winsAsXCount() {
        return  Game::where([["winner", "=", "x"], ['player_x_id', '=', $this->id]])->count();
    }

    /**
     * Count of Wins playing as O
     *
     * @return mixed
     */
    public function winsAsOCount() {
        return  Game::where([["winner", "=", "o"], ['player_o_id', '=', $this->id]])->count();
    }


    /**
     * Count of Draws playing as O
     *
     * @return mixed
     */
    public function drawsAsXCount() {
        return  Game::where([["winner", "=", "d"], ['player_x_id', '=', $this->id]])->count();
    }

    /**
     * Count of Draws playing as O
     *
     * @return mixed
     */
    public function drawsAsOCount() {
        return  Game::where([["winner", "=", "d"], ['player_o_id', '=', $this->id]])->count();
    }

    /**
     * Return the number of Draws
     *
     * @return mixed
     */
    public function totalDrawCount() {
        return Game::where([["winner", "=", "d"], ['player_x_id', '=', $this->id]])->orWhere([["winner", "=", "d"], ['player_o_id', '=', $this->id]])->count();
    }

    /**
     * Count of Losses playing as X
     *
     * @return mixed
     */
    public function lossesAsXCount() {
        return  Game::where([["winner", "=", "o"], ['player_x_id', '=', $this->id]])->count();
    }

    /**
     * Count of Losses playing as O
     *
     * @return mixed
     */
    public function lossesAsOCount() {
        return  Game::where([["winner", "=", "x"], ['player_o_id', '=', $this->id]])->count();
    }

    /**
     * Return number of games lost
     *
     * @return mixed
     */
    public function totalLossCount() {
        return Game::where([["winner", "=", "0"], ['player_x_id', '=', $this->id]])->orWhere([["winner", "=", "x"], ['player_o_id', '=', $this->id]])->count();
    }

    /** Return an array of stats.
     * @return mixed
     */
    public function stats() {
        $output = array();
        $output['winsAsX'] = $this->winsAsXCount();
        $output['winsAsO'] = $this->winsAsOCount();
        $output['totalWins'] = $output['winsAsO'] + $output['winsAsX'];

        $output['drawsAsX'] = $this->drawsAsXCount();
        $output['drawsAsO'] = $this->drawsAsOCount();
        $output['totalDraws'] = $output['drawsAsX'] + $output['drawsAsO'];

        $output['lossesAsX'] = $this->lossesAsXCount();
        $output['lossesAsO'] = $this->lossesAsOCount();
        $output['totalLosses'] = $output['lossesAsX'] + $output['lossesAsO'];

        $output['totalGames'] = $output['totalWins'] + $output['totalDraws'] + $output['totalLosses'];

        $output['winPercentage'] = ($output['totalGames'] > 0) ? $output['totalWins'] / $output['totalGames'] * 100 : 0;
        $output['drawPercentage'] = ($output['totalGames'] > 0) ? $output['totalDraws'] / $output['totalGames'] * 100 : 0;
        $output['lossPercentage'] = ($output['totalGames'] > 0) ? $output['totalLosses'] / $output['totalGames'] * 100 : 0;

        return $output;
    }





}
