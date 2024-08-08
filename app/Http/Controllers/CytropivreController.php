<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CytropivreController extends Controller
{
    
    public static function getSession($userID){
        $userSessionTable = config('cytropcool.database.table.user_session');
        $result = DB::select("SELECT
            session_id
        FROM
            $userSessionTable
        WHERE
            user_id = ?
        ;", [$userID]);
        return count($result) == 0 ? null : $result[0]->session_id;
    }
    
    public static function createSession(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:1',
            'private' => 'bail|nullable'
        ]);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        
        if(CytropivreController::getSession(Auth::user()->id) != null){
            return Redirect::back()->withErrors(["failed"=>"tu dois quitter ta session avant d'en créer une."])->withInput();
        }
        
        $sessionTable = config('cytropcool.database.table.sessions');
        $userSessionTable = config('cytropcool.database.table.user_session');
        
        
        $date = date('Y-m-d H:i:s');
        $id = Str::random(6);
        
        while( count(DB::select("SELECT id FROM $sessionTable WHERE id=?;", [$id])) ){
            $id = Str::random(6);
        }
        
        DB::insert("INSERT INTO 
                $sessionTable 
                (`id`, `admin`, `name`, `private`, `created_at`, `last_update`) 
            VALUES 
                (?, ?, ?, ?, ?, ?)
            ;", 
        [$id, Auth::user()->id, $request->name, $request->private ? 1 : 0, $date, $date]);
        
        DB::insert("INSERT INTO $userSessionTable (`user_id`,`session_id`) VALUES (?,?) ", [Auth::user()->id, $id]);
        
        return redirect('/cytropivre/session');
        
    }

    public static function getSessionData(String $sessionId){
        $sessionTable = config('cytropcool.database.table.sessions');

        $r = DB::select("SELECT * FROM $sessionTable WHERE id=? AND ended=0 LIMIT 1;", [$sessionId]);

        return coutn($r) == 0 ? null : $r[0];
    }
    
    public static function joinSession(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|size:6',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        if(CytropivreController::getSession(Auth::user()->id) != null){
            return Redirect::back()->withErrors(["failed"=>"Tu dois quitter ta session avant d'en rejpoindre une."]);
        }

        $sessionTable = config('cytropcool.database.table.sessions');
        $userSessionTable = config('cytropcool.database.table.user_session');

        if(CytropivreController::getSessionData($request->id) == null){
            return Redirect::back()->withErrors(["failed"=>"La session n'existe pas"]);
        }

        DB::insert("INSERT INTO $userSessionTable (`user_id`,`session_id`) VALUES (?,?) ", [Auth::user()->id, $request->id]);

        return redirect('/cytropivre/session');
    }

    public static function quitSession(){
        $userTable = config('auth.providers.users.table');
        $sessionTable = config('cytropcool.database.table.sessions');
        $userSessionTable = config('cytropcool.database.table.user_session');
        $drinkTable = config('cytropcool.database.table.drinks');
        $statsTable = config('cytropcool.database.table.user_statistiques');

        if(CytropivreController::getSession(Auth::user()->id) == null){
            return Redirect::back();
        }

        $sessionData = CytropivreController::getSessionData(CytropivreController::getSession(Auth::user()->id));

        if($sessionData->admin != Auth::user()->id){
            DB::delete("DELETE FROM $userSessionTable WHERE user_id=?", [Auth::user()->id]);
        }
        else{
            DB::update("UPDATE $sessionTable SET ended=1 WHERE id=?", [$sessionData->id]);

            $users = DB::select("SELECT user_id AS id, eat FROM $userSessionTable WHERE session_id=?", [$sessionData->id]);

            foreach($users as $user){
                $userData = DB::select("SELECT * FROM $userTable WHERE id=?", [$user->id]);

                $stats = new stdClass();
                $stats->user_id = $userData->id;
                $stats->session_id = $sessionData->id;
                $stats->max_gl = 0;
                $stats->alcool_quantity = 0;
                $stats->pure_alcool_quantity = 0;
                $stats->glass = 0;
                $stats->shot = 0;

                $userDrinks = DB::select("SELECT * FROM $drinkTable WHERE session_id = ? AND user_id = ?;", [$stats->session_id, $stats->user_id]);
                $stats->max_gl = getCurrentRateOfUser($userDrinks, $userData->sexe, $userData->weight, $user->eat)->max_rate;
                foreach($userDrinks as $drink){
                    $stats->alcool_quantity += ($drink->size)*$drink->alcool_quantity;
                    $stats->pure_alcool_quantity += (($drink->size)*$drink->alcool_quantity)*$drink->alcool_degre;

                    if($drink->size < 6 && $drink->bottoms_up){
                        $stats->shot += 1;
                    }
                    else{
                        $stats->glass += 1;
                    }
                }

                DB::insert("INSERT INTO 
                    $statsTable 
                    (`user_id`, `session_id`, `max_gl`, `alcool_quantity`, `pure_alcool_quantity`, `glass`, `shot`) 
                VALUES 
                    (?,?,?,?,?,?,?)
                ;",
                [$stats->user_id, $stats->session_id, $stats->max_gl, $stats->alcool_quantity, $stats->pure_alcool_quantity, $stats->glass, $stats->shot]);
            }


            DB::delete("DELETE FROM $userSessionTable WHERE session_id=?", [$sessionData->id]);
        }

        return redirect('/');
    }


    
    public static function getCurrentRateOfUser(array $drinks, float $sexe, float $weight, int $eat): array
    {
        $DECAY = config('cytropcool.constant.decay');
        
        $TIME_TO_MAX = $eat ? config('cytropcool.constant.time_to_max_eat') : config('cytropcool.constant.time_to_max_no_eat');
        
        $TIME_TO_MAX_BOTTOMS_UP = config('cytropcool.constant.time_to_max_bottoms_up');
        
        $TIME_TO_MAX_NO_BOTTOMS_UP = config('cytropcool.constant.time_to_max_no_bottoms_up');
        
        $TIME_FIRST_DRINK = strtotime($drinks[0]->drink_at);
        
        $current_date = strtotime(date('Y-m-d H:i:s'));
        
        $insynch_max_rate_current_time = 0;
        $insynch_max_rate_last_time = $TIME_FIRST_DRINK + $TIME_TO_MAX + ( $drinks[0]->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP );
        
        $rate = 0;
        $insynch_max_current_rate = 0;
        $max = ["rate"=>0, "time"=>$current_date];
        
        foreach($drinks as $drink){
            $ingestion_gap = ( $drink->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP );
            
            $tt = ( $drink->size * 10 * $drink->number * ($drink->alcool_quantity/100) * ($drink->alcool_degre/100) * 0.8) / ($sexe * $weight);
            
            //live rate
            if($current_date >= (strtotime($drink->drink_at)+ $TIME_TO_MAX + $ingestion_gap) ){
                $rate += $tt;
            }
            else if($current_date >= (strtotime($drink->drink_at) + $ingestion_gap) ){
                $rate += ($tt *( $current_date - (strtotime($drink->drink_at) + $ingestion_gap ))) / ($TIME_TO_MAX + $ingestion_gap);
            }
            
            //insynch max rate
            $insynch_max_rate_current_time = (strtotime($drink->drink_at)+ $TIME_TO_MAX + $ingestion_gap);
            $insynch_max_current_rate += $tt; 
            
            $insynch_max_reduction = $DECAY * ($insynch_max_rate_current_time - $insynch_max_rate_last_time); //get decay betwen two drink
            if($insynch_max_reduction > 0) $insynch_max_current_rate -= $insynch_max_reduction;
            
            if($max["rate"] < $insynch_max_current_rate){
                $max["rate"] = $insynch_max_current_rate;
                $max["time"] = $insynch_max_rate_current_time;
            }
            
            $insynch_max_rate_last_time = $insynch_max_rate_current_time;
        }
        
        $reduction = $DECAY * ($current_date - ($TIME_FIRST_DRINK + 30*60 + ( $drinks[0]->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP )));
        if($reduction > 0) $rate -= $reduction;
        if($rate < 0) $rate = 0;

        $return = new stdClass();
        $return->rate = $rate;
        $return->max_rate = $max["rate"];
        $return->max_rate_time = $max["time"];

        return $return;
    }
    
    
}