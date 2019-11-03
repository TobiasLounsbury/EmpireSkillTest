<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auth\User;

class PlayerController extends Controller
{
    /**
     * Display a list of players nicknames
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $players = User::all(['id','nickname'])->where('nickname', '<>', '')->sortBy('nickname');
        return response()->json(array_values($players->toArray()));
    }

    /**
     * Get the Stats for a player
     *
     * @return \Illuminate\Http\Response
     */
    public function details(User $player)
    {
        $output = ["id" => $player->id, "nickname" => $player->nickname];
        $output['stats'] = $player->stats();
        return response()->json($output);
    }

    /**
     * Get the games for a player
     *
     * @return \Illuminate\Http\Response
     */
    public function games(User $player)
    {
        $games = $player->games();
        $output = array();
        foreach($games as $game) {
            $g = $game->toArray();
            $g['playerX'] = $game->playerX->nickname;
            $g['playerO'] = $game->playerO->nickname;
            $output[] = $g;
        }
        return response()->json($output);
    }
}
