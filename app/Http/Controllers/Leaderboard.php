<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Models\Game;

class Leaderboard extends Controller
{
    //
    /**
     * Display a listing of players
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $output = array();

        //Get some players
        $players = User::where('nickname', '<>', '')->get()->sortBy('nickname');
        foreach($players as $player) {
            $wins = $player->totalWinCount();
            $draws = $player->totalDrawCount();
            $losses = $player->totalLossCount();

            $output[] = array("id" => $player->id, "nickname" => $player->nickname, "wins" => $wins, "draws" => $draws, "losses" => $losses);
        }

        return response()->json($output);
    }
}
