<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PlayerController extends Controller
{

    public function index($game = null)
    {
        if($game === 'rugby' || $game === 'nba'){
            if($game == "nba") {
                return view('nba');
            }else{
                return view('rugby');
            }
        }else{
            return 'game not found';
        }
    }
    /**
     * Show a player profile
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function show($game, $team)
    {

        $data = $this->fetchPlayers($game, $team);

        return $data;

    }

    public function fetchPlayers($game,$team)
    {
        if($game == 'nba' && $team == 'gsw') {
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/nba.players';

            $stats = $this->fetchApi('https://www.zeald.com/developer-tests-api/x_endpoint/nba.stats');

            $json = $this->fetchApi($baseEndpoint);

            $data = $this->getNba($json,$stats);

        }else{
            $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/allblacks';
            $json = $this->fetchApi($baseEndpoint);
            $data = $this->getRugby($json);
        }

        return $data;
    }

    public function fetchApi($baseEndpoint)
    {
        $json = Http::get("$baseEndpoint", [
            'API_KEY' => config('api.key'),
        ])->json();

        return $json;
    }

    public function getRugby($json)
    {
        $data = array();
        foreach($json as $key => $value) {
            $names = collect(preg_split('/\s+/', $value['name']));
            $data[$key] = [
                "first_name" => $names[0],
                "last_name" => $names[1],
                "conversions" =>  $value['conversions'],
                "penalties" =>  $value['penalties'],
                "id" =>  $value['id'],
                "number" =>  $value['number'],
            ];
            $data[$key]['featured'] = [
                'points' => $value['points'],
                'games' => $value['games'],
                'tries' => $value['tries'],
            ];
            $data[$key]['stats'] = [
                'position' => $value['position'],
                'weight' => $value['weight'],
                'height' => $value['height'],
                'age' => $value['age'],
            ];

        }

        return $data;
    }

    public function getNba($json, $stats)
    {
        $stats = collect($stats);

        $playerStat = [];

        $data = array();
        foreach($json as $key => $value) {

            $playerStat = $stats->where('player_id', $value['id'])->toArray();
            $arr = array();
            foreach($playerStat as $stat) {
                $arr += $stat;
            }


            $age = date_diff(date_create($value['birthday']), date_create(date('Y-m-d')));
            $height =  $value['feet'] . "′" . $value['inches'] . "″";

            $data[$key] = [
                "first_name" => $value['first_name'],
                "last_name" => $value['last_name'],
                "id" =>  $value['id'],
                "number" =>  $value['number'],
            ];
            $data[$key]['featured'] = [
                'ASSIST PER GAME' => $arr['assists'],
                'POINTS PER GAME' => $arr['points'],
                'REBOUND PER GAME' => $arr['rebounds'],
            ];
            $data[$key]['stats'] = [
                'position' => $value['position'],
                'weight' => $value['weight'],
                'height' => $height,
                'age' => $age->format("%y"),
            ];

        }

        return $data;
    }



//    /**
//     * Retrieve player data from the API
//     *
//     * @param int $id
//     * @return \Illuminate\Support\Collection
//     */
//    protected function player(int $id): Collection
//    {
//        // return collect([
//        //     "tries" => 21,
//        //     "games" => 102,
//        //     "number" => 9,
//        //     "position" => "Halfback",
//        //     "points" => 107,
//        //     "name" => "Aaron Smith",
//        //     "height" => 173,
//        //     "age" => 33,
//        //     "conversions" => 1,
//        //     "weight" => 83,
//        //     "penalties" => 0,
//        //     "id" => "1",
//        // ]);
//
//        $baseEndpoint = 'https://www.zeald.com/developer-tests-api/x_endpoint/allblacks';
//
//        $json = Http::get("$baseEndpoint", [
//            'API_KEY' => config('api.key'),
//        ])->json();
//
//
//        return collect(array_shift($json));
//
//
//    }
//
//    /**
//     * Determine the image for the player based off their name
//     *
//     * @param string $name
//     * @return string filename
//     */
//    protected function image(string $name): string
//    {
//        return preg_replace('/\W+/', '-', strtolower($name)) . '.png';
//    }
//
//    /**
//     * Build stats to feature for this player
//     *
//     * @param \Illuminate\Support\Collection $player
//     * @return \Illuminate\Support\Collection features
//     */
//    protected function feature(Collection $player): Collection
//    {
//        return collect([
//            ['label' => 'Points', 'value' => $player->get('points')],
//            ['label' => 'Games', 'value' => $player->get('games')],
//            ['label' => 'Tries', 'value' => $player->get('tries')],
//        ]);
//    }
}
