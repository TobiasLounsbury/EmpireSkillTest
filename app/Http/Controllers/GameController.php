<?php

namespace App\Http\Controllers;

use App\Models\Game;
//use App\Repositories\GameRepository;
use Illuminate\Http\Request;
use App\Models\Auth\User;


class GameController extends Controller
{

    protected $model;

    public function __construct(Game $game) {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if (request()->input('user')) {}
        $games = Game::whereNull("winner")->get()->sortByDesc("id");
        $output = array();
        foreach($games as $game) {
            $g = $game->toArray();
            $g['playerX'] = $game->playerX->nickname;
            $g['playerO'] = $game->playerO->nickname;
            $output[] = $g;
        }
        return response()->json($output);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if(!$user) { return response()->json(array("status" => false, "message" => "User must be logged in to play games with friends."));}

        $secondPlayerId = $request->input('secondPlayer');
        if ($user->id == $secondPlayerId) {return response()->json(array("status" => false, "message" => "You can't play a game with yourself."));}

        $secondPlayer = User::findOrFail($secondPlayerId);
        $iam = $request->input('iam');

        $game = new Game();
        if($iam == "x") {
            $game->player_x_id = $user->id;
            $game->player_o_id = $secondPlayer->id;
        } else {
            $game->player_o_id = $user->id;
            $game->player_x_id = $secondPlayer->id;
        }

        $game->save();

        return response()->json($game);
    }

    /**
     * Display the specified resource.
     *
     * @param  Game $game
     * @param $outputStyle
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        $output = $game->toArray();
        $output['playerX'] = $game->playerX->nickname;
        $output['playerO'] = $game->playerO->nickname;
        $output['squares'] = $game->buildSquaresArray();
        $output['currentPlayer'] = $game->currentPlayer();


        return response()->json($output);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Game $game
     * @return \Illuminate\Http\Response
     * @throws \ErrorException
     */
    public function play(Request $request)
    {

        $square = $request->input('square');
        $player = $request->input('player');
        $gameId = $request->input('gameId');

        if (!is_numeric($gameId)) {
            throw new \ErrorException("GameId is invalid");
        }


        $user = auth()->user();
        $game = Game::where('id', '=', $gameId)->first();

        $pidf = "player_{$player}_id";
        $tmp = $game->$pidf;
        if ($game->$pidf != $user->id) {
            throw new \ErrorException("Player ID Doesn't Match; No Cheating!");
        }

        //add the move.
        $game->addMove($square, $player);

        //Return the output.
        $output = array(
            "status" => "success",

            "gameData" => $game->toArray()
        );
        $output['gameData']['playerX'] = $game->playerX->nickname;
        $output['gameData']['playerO'] = $game->playerO->nickname;
        $output['gameData']['squares'] = $game->buildSquaresArray();
        $output['gameData']['currentPlayer'] = $game->currentPlayer();
        return response()->json($output);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        //
    }
}
