<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Http\Controllers\HoldableController;

use \stdClass;
use \DateTime;
use \DateTimeZone;
use Carbon\Carbon;


class CytropivreController extends Controller
{
    private static function isShot($size, $bottoms_up){
        return $size < 6 && $bottoms_up;
    }

    private static function getUseCrampte($alcool_pure, $glmax, $mass){
        $magic_value_alcool = config('cytropcool.constant.crampte_alcool');
        $magic_value_gmax = config('cytropcool.constant.crampte_gmax');
        $magic_value_for_mass = config('cytropcool.constant.crampte_mass');
    
        $crampte = ($alcool_pure/$magic_value_alcool) + $mass/( abs($magic_value_gmax - $glmax) + $magic_value_for_mass);
        return intval($crampte);
    }
    
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

    public static function getSessionData(String $sessionId){
        $sessionTable = config('cytropcool.database.table.sessions');
        
        $r = DB::select("SELECT * FROM $sessionTable WHERE id=? AND ended=0 LIMIT 1;", [$sessionId]);
        
        return count($r) == 0 ? null : $r[0];
    }

    public static function getPublicSession(){
        $sessionTable = config('cytropcool.database.table.sessions');

        return DB::select("SELECT * FROM $sessionTable WHERE private=0 AND ended=0;");
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
        
        
        $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
        $dt->setTimestamp(time());
        $id = strtolower(Str::random(6));
        
        while( count(DB::select("SELECT id FROM $sessionTable WHERE id=?;", [$id])) ){
            $id = strtolower(Str::random(6));
        }
        
        $affected = DB::insert("INSERT INTO 
                $sessionTable 
                (`id`, `admin`, `name`, `private`, `created_at`, `last_update`) 
            VALUES 
                (?, ?, ?, ?, ?, ?)
            ;", 
        [$id, Auth::user()->id, $request->name, $request->private ? 1 : 0, $dt->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s')]);

        if(!$affected){
            return Redirect::back()->withErrors(["failed"=>"Impossible de creer la session. Reessaye plus tard."])->withInput();
        }
        
        $affected = DB::insert("INSERT INTO $userSessionTable (`user_id`,`session_id`) VALUES (?,?) ", [Auth::user()->id, $id]);

        if(!$affected){
            return Redirect::back()->withErrors(["failed"=>"Impossible de rejoindre la session. Reessaye plus tard."])->withInput();
        }
        
        return redirect('/cytropivre/session');
        
    }

    
    public static function joinSession(Request $request){
        $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
        $dt->setTimestamp(time());

        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|size:6',
        ]);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        
        if(CytropivreController::getSession(Auth::user()->id) != null){
            return Redirect::back()->withErrors(["failed"=>"Tu dois quitter ta session avant d'en rejoindre une."]);
        }
        
        $sessionTable = config('cytropcool.database.table.sessions');
        $userSessionTable = config('cytropcool.database.table.user_session');
        
        if(CytropivreController::getSessionData($request->id) == null){
            return Redirect::back()->withErrors(["failed"=>"La session n'existe pas"]);
        }
        
        $affected = DB::insert("INSERT INTO $userSessionTable (`user_id`,`session_id`) VALUES (?,?) ", [Auth::user()->id, $request->id]);
        
        if($affected){
            return redirect('/cytropivre/session');
        }
        else{
            return Redirect::back()->withErrors(["failed"=>"Impossible de rejoindre la session. Reessaye plus tard."]);
        }
    }

    public static function joinSessionLink(string $id){
        
        if (strlen($id) != 6) {
            return redirect('/cytropivre/search')->withErrors(["failed"=>"Le lien n'est pas correctement formé."]);
        }
        
        if(CytropivreController::getSession(Auth::user()->id) != null){
            return redirect('/cytropivre/search')->withErrors(["failed"=>"Tu dois quitter ta session avant d'en rejoindre une."]);
        }
        
        $sessionTable = config('cytropcool.database.table.sessions');
        $userSessionTable = config('cytropcool.database.table.user_session');
        
        if(CytropivreController::getSessionData($id) == null){
            return redirect('/cytropivre/search')->withErrors(["failed"=>"La session n'existe pas"]);
        }
        
        $affected = DB::insert("INSERT INTO $userSessionTable (`user_id`,`session_id`) VALUES (?,?) ", [Auth::user()->id, $id]);
        
        if($affected){
            return redirect('/cytropivre/session');
        }
        else{
            return redirect('/cytropivre/search')->withErrors(["failed"=>"Impossible de rejoindre la session. Reessaye plus tard."]);
        }
    }
    
    public static function quitSession(){
        $userTable = config('auth.providers.users.table');
        $sessionTable = config('cytropcool.database.table.sessions');
        $userSessionTable = config('cytropcool.database.table.user_session');
        $drinkTable = config('cytropcool.database.table.drinks');
        $statsTable = config('cytropcool.database.table.user_statistiques');
        
        $sessionData = CytropivreController::getSessionData(CytropivreController::getSession(Auth::user()->id));
        
        if($sessionData->admin != Auth::user()->id){
            DB::delete("DELETE FROM $userSessionTable WHERE user_id=?", [Auth::user()->id]);
        }
        else{
            DB::update("UPDATE $sessionTable SET ended=1 WHERE id=?", [$sessionData->id]);

            if(strtotime($sessionData->last_update) - strtotime($sessionData->created_at) < 3600){
                DB::delete("DELETE FROM $userSessionTable WHERE session_id=?", [$sessionData->id]);
                return redirect('/');
            }
            
            $users = DB::select("SELECT user_id AS id, eat FROM $userSessionTable WHERE session_id=?", [$sessionData->id]);
            
            foreach($users as $user){
                $userData = DB::select("SELECT * FROM $userTable WHERE id=?", [$user->id])[0];
                
                $stats = new stdClass();
                $stats->user_id = $userData->id;
                $stats->session_id = $sessionData->id;
                $stats->max_gl = 0;
                $stats->alcool_quantity = 0;
                $stats->pure_alcool_quantity = 0;
                $stats->glass = 0;
                $stats->shot = 0;
                
                $userDrinks = DB::select("SELECT * FROM $drinkTable WHERE session_id = ? AND user_id = ?;", [$stats->session_id, $stats->user_id]);
                $stats->max_gl = self::getCurrentRateOfUser($userDrinks, $userData->sexe, $userData->weight, $user->eat)->max_rate;
                foreach($userDrinks as $drink){
                    $stats->alcool_quantity += (($drink->size/100)*$drink->number)*($drink->alcool_quantity/100);
                    $stats->pure_alcool_quantity += ((($drink->size/100)*$drink->number)*($drink->alcool_quantity/100))*($drink->alcool_degre/100);
                    
                    if(self::isShot($drink->size, $drink->bottoms_up)){
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

                $crampte = self::getUseCrampte($stats->pure_alcool_quantity, $stats->max_gl, $userData->weight);
                DB::update("UPDATE $userTable SET crampte = ? WHERE id = ?;", [$crampte + $userData->crampte, $userData->id]);
            }
            
            
            DB::delete("DELETE FROM $userSessionTable WHERE session_id=?", [$sessionData->id]);
        }
        
        return redirect('/');
    }
    
    public static function setEat(Request $request){
        $userSessionTable = config('cytropcool.database.table.user_session');
        
        $validator = Validator::make($request->all(), [
            'eat' => 'bail|required|boolean'
        ]);
        
        if ($validator->fails()) {
            return Redirect::back();
        }

        DB::update("UPDATE 
            $userSessionTable 
        SET
            eat=?
        WHERE
            user_id=?
        ;", 
        [$request->eat, Auth::user()->id]);
        
        return Redirect::back();
    }
    
    public static function getCurrentRateOfUser(array $drinks, float $sexe, float $weight, int $eat)
    {
        $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
        $dt->setTimestamp(time());

        $return = new stdClass();
        $return->rate = 0;
        $return->max_rate = 0;
        $return->max_rate_time = $dt->format('H:i');

        if(count($drinks) == 0){
            return $return;
        }

        $DECAY = config('cytropcool.constant.decay');

        $TIME_BEFORE_DECAY = config('cytropcool.constant.time_before_decay');
        
        $TIME_TO_MAX = $eat ? config('cytropcool.constant.time_to_max_eat') : config('cytropcool.constant.time_to_max_no_eat');
        
        $TIME_TO_MAX_BOTTOMS_UP = config('cytropcool.constant.time_to_max_bottoms_up');
        
        $TIME_TO_MAX_NO_BOTTOMS_UP = config('cytropcool.constant.time_to_max_no_bottoms_up');

        $current_date = strtotime($dt->format('Y-m-d H:i:s'));

        $TIME_FIRST_DRINK = strtotime($drinks[0]->drink_at);
        $i = 0;

        foreach($drinks as $drink){
            $tt = ( $drink->size * 10 * $drink->number * ($drink->alcool_quantity/100) * ($drink->alcool_degre/100) * 0.8) / ($sexe * $weight);

            if( ($tt/$DECAY + strtotime($drink->drink_at) + $TIME_TO_MAX + ($drink->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP )) < $current_date ){
                $i+=1;
                continue;
            }
            else{
                $TIME_FIRST_DRINK = strtotime($drink->drink_at);
                break;
            }
        }

        if($i == count($drinks)){
            $i-=1;
        }
        
        $insynch_max_rate_last_time = $TIME_FIRST_DRINK + $TIME_TO_MAX + ( $drinks[$i]->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP );
        
        $rate = 0;
        $insynch_max_current_rate = 0;
        $insynch_max_rate_current_time = 0;

        $max = ["rate"=>0, "time"=>$current_date];
        
        foreach($drinks as $drink){
            $ingestion_gap = ( $drink->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP );
            
            $tt = ( $drink->size * 10 * $drink->number * ($drink->alcool_quantity/100) * ($drink->alcool_degre/100) * 0.8) / ($sexe * $weight);
            
            //live rate
            if($current_date >= (strtotime($drink->drink_at)+ $TIME_TO_MAX + $ingestion_gap) ){
                if($current_date < ($tt/$DECAY + strtotime($drink->drink_at) + $TIME_TO_MAX + ($drink->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP ))){
                    $rate += $tt;
                }
            }
            else if($current_date >= (strtotime($drink->drink_at) + $ingestion_gap) ){
                $rate += ($tt *( $current_date - (strtotime($drink->drink_at) + $ingestion_gap ))) / ($TIME_TO_MAX + $ingestion_gap);
            }
            
            //insynch max rate
            $insynch_max_rate_current_time = (strtotime($drink->drink_at)+ $TIME_TO_MAX + $ingestion_gap);
            
            $insynch_max_reduction = $DECAY * ($insynch_max_rate_current_time - $insynch_max_rate_last_time); //get decay betwen two drink
            if($insynch_max_reduction > 0) $insynch_max_current_rate -= $insynch_max_reduction; 
            if($insynch_max_current_rate < 0) $insynch_max_current_rate = 0;

            $insynch_max_current_rate += $tt; 

            if($max["rate"] < $insynch_max_current_rate){
                $max["rate"] = $insynch_max_current_rate;
                $max["time"] = $insynch_max_rate_current_time;
            }
            
            $insynch_max_rate_last_time = $insynch_max_rate_current_time;
        }
        
        $reduction = $DECAY * ($current_date - ($TIME_FIRST_DRINK + $TIME_BEFORE_DECAY + ( $drinks[$i]->bottoms_up ? $TIME_TO_MAX_BOTTOMS_UP : $TIME_TO_MAX_NO_BOTTOMS_UP )));
        if($reduction > 0) $rate -= $reduction;
        if($rate < 0) $rate = 0;
        
        $return->rate = round($rate,1);
        $return->max_rate = round($max["rate"],1);
        $return->max_rate_time = date('H:i', $max["time"]);
        
        return $return;
    }

    public static function getDrink(){
        $drinkTable = config('cytropcool.database.table.drinks');

        $userId = Auth::user()->id;
        $sessionId = CytropivreController::getSession(Auth::user()->id);

        return DB::select("SELECT * FROM $drinkTable WHERE user_id=? AND session_id=? ORDER BY drink_at ASC;", [$userId, $sessionId]);
    }
    
    public static function addDrink(Request $request, bool $hidden = false){
        $drinkTable = config('cytropcool.database.table.drinks');
        $sessionTable = config('cytropcool.database.table.sessions');
        
        $validator = Validator::make($request->all(), [
            'number' => 'bail|required|int|min:1',
            'size' => 'bail|required|int|min:0.1',
            'alcool' => 'bail|required|string|max:20',
            'alcool_quantity' => 'bail|required|int|min:0|max:100',
            'alcool_degre' => 'bail|required|int|min:0|max:100',
            'bottoms_up' => 'bail|required|boolean',
            'drink_at' => 'bail|required|date_format:Y-m-d\TH:i'
        ]);
        
        if ($validator->fails()) {
            return Redirect::back()->with(["add-failed" => $validator->errors()->first()])->withInput();
        }
        
        $userId = Auth::user()->id;
        $sessionId = CytropivreController::getSession(Auth::user()->id);

        $convertedDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->drink_at)->format('Y-m-d H:i:s');
        
        $type = self::isShot($request->size, $request->bottoms_up) ? 'Shot' : 'Verre';
        $hour = date("H:i", strtotime($convertedDate));
        $name = "$request->number $type de $request->alcool ($request->size cl) à $hour";
        
        $affected = DB::insert("INSERT INTO 
            $drinkTable 
            (`session_id`, `user_id`, `name`, `alcool`, `number`, `size`, `alcool_quantity`, `alcool_degre`, `bottoms_up`, `drink_at`, `hidden`) 
        VALUES 
            (?,?,?,?,?,?,?,?,?,?,?)
        ;",
        [$sessionId, $userId, $name, $request->alcool, $request->number, $request->size, $request->alcool_quantity, $request->alcool_degre, $request->bottoms_up, $convertedDate, $hidden]);
        
        if($affected){ 
            $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
            $dt->setTimestamp(time());
            DB::update("UPDATE $sessionTable SET last_update=? WHERE id=? AND ended=0;", [$dt->format('Y-m-d H:i:s'), $sessionId]);
            return Redirect::back()->with(["add-success" => "Ta boisson a été ajouté !"]);
        }
        else{
            return Redirect::back()->with(["add-failed" => "Oops, une erreur est survenue."]);
        }
    }

    
    public static function updateDrink(Request $request){
        $drinkTable = config('cytropcool.database.table.drinks');
        $sessionTable = config('cytropcool.database.table.sessions');
        
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|int|min:1',
            'number' => 'bail|required|int|min:1',
            'size' => 'bail|required|int|min:0.1',
            'alcool' => 'bail|required|string|max:20',
            'alcool_quantity' => 'bail|required|int|min:0|max:100',
            'alcool_degre' => 'bail|required|int|min:0|max:100',
            'bottoms_up' => 'bail|required|boolean',
            'drink_at' => 'bail|required|date_format:Y-m-d\TH:i'
        ]);
        
        if ($validator->fails()) {
            return Redirect::back()->with(["update-failed" => $validator->errors()->first()])->withInput();
        }
        
        $userId = Auth::user()->id;
        $sessionId = CytropivreController::getSession(Auth::user()->id);

        $convertedDate = Carbon::createFromFormat('Y-m-d\TH:i', $request->drink_at)->format('Y-m-d H:i:s');
        
        $type = self::isShot($request->size, $request->bottoms_up) ? 'Shot' : 'Verre';
        $hour = date("H:i", strtotime($convertedDate));
        $name = "$request->number $type de $request->alcool ($request->size cl) à $hour";
        
        $affected = DB::update("UPDATE 
            $drinkTable 
        SET
            name=?,
            alcool=?,
            number=?,
            size=?,
            alcool_quantity=?,
            alcool_degre=?,
            bottoms_up=?,
            drink_at=?
        WHERE
            id=?
            AND
            user_id=?
            AND
            session_id=?
            AND
            hidden=0
        ;", 
        [$name, $request->alcool, $request->number, $request->size, $request->alcool_quantity, $request->alcool_degre, $request->bottoms_up, $convertedDate, $request->id, $userId, $sessionId]);
        
        if($affected == 1){
            $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
            $dt->setTimestamp(time());
            DB::update("UPDATE $sessionTable SET last_update=? WHERE id=? AND ended=0;", [$dt->format('Y-m-d H:i:s'), $sessionId]);
            return Redirect::back()->with(["update-success" => "Ta boisson a été mis a jour !"]);
        }
        else{
            return Redirect::back()->with(["update-failed" => "Oops, une erreur est survenue."]);
        }
    }
    
    public static function deleteDrink(Request $request){
        $drinkTable = config('cytropcool.database.table.drinks');
        $sessionTable = config('cytropcool.database.table.sessions');

        
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|int|min:1'
        ]);
        
        if ($validator->fails()) {
            return Redirect::back()->with(["update-failed" => $validator->errors()->first()])->withInput();
        }

        $userId = Auth::user()->id;
        $sessionId = CytropivreController::getSession(Auth::user()->id);
        
        $affected = DB::delete("DELETE FROM
            $drinkTable
        WHERE
            id=?
            AND
            user_id=?
            AND
            session_id=?
        ", [$request->id, $userId, $sessionId]);
        
        if($affected == 1){
            $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
            $dt->setTimestamp(time());
            DB::update("UPDATE $sessionTable SET last_update=? WHERE id=? AND ended=0;", [$dt->format('Y-m-d H:i:s'), $sessionId]);
            return Redirect::back()->with(["update-success" => "Ta boisson a été supprimée !"]);
        }
        else{
            return Redirect::back()->with(["update-failed" => "Oops, une erreur est survenue."]);
        }
    }

    public static function getScoreboard(string $sessionId){
        $userTable = config('auth.providers.users.table');
        $userSessionTable = config('cytropcool.database.table.user_session');
        $drinkTable = config('cytropcool.database.table.drinks');

        if (strlen($sessionId) != 6) {
            return [];
        }
        
        if(CytropivreController::getSessionData($sessionId) == null){
            return [];
        }

        $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
        $dt->setTimestamp(time());

        $data = ['_CYTI_GL'=>[], '_CYTI_TIME'=>$dt->format('H:i')];

        $users = DB::select("SELECT user_id AS id,eat FROM $userSessionTable WHERE session_id=?", [$sessionId]);
        $ids = [];

        foreach($users as $user){
            array_push($ids, $user->id);

            $userData = DB::select("SELECT * FROM $userTable WHERE id=?", [$user->id])[0];
            $userDrinks = DB::select("SELECT * FROM $drinkTable WHERE session_id = ? AND user_id = ?;", [$sessionId, $user->id]);
            array_push($data['_CYTI_GL'], self::getCurrentRateOfUser($userDrinks, $userData->sexe, $userData->weight, $user->eat)->rate);
        }   
        $data += HoldableController::displayHold($ids);

        return $data;
    }
}