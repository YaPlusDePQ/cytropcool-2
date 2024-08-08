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

class ProfileController extends Controller
{
    public static function getUser($userID)
    {
        $userTable = config('auth.providers.users.table');

        $user = DB::select("SELECT id,name,crampte FROM $userTable WHERE id=? LIMIT 1;", [$userID]);
        return $user == [] ? null : $user[0];
    }

    public static function getStats($userID): stdClass
    {
        $statsTable = config('cytropcool.database.table.user_statistiques');

        $stats = DB::select("SELECT
            MAX(max_gl) AS max_gl,
            SUM(max_gl) AS sum_max_gl,
            MAX(alcool_quantity) AS max_alcool_quantity,
            SUM(alcool_quantity) AS sum_alcool_quantity,
            MAX(pure_alcool_quantity) AS max_pure_alcool_quantity,
            SUM(pure_alcool_quantity) AS sum_pure_alcool_quantity,
            MAX(glass) AS max_glass,
            SUM(glass) AS sum_glass,
            MAX(shot) AS max_shot,
            SUM(shot) AS sum_shot
        FROM
            $statsTable
        WHERE
            $statsTable.user_id = ?
        GROUP BY
            $statsTable.user_id
        ;", [$userID]);

        if($stats == []){
            $default = new stdClass();
            $default->max_gl = 0;
            $default->sum_max_gl = 0;
            $default->max_alcool_quantity = 0;
            $default->sum_alcool_quantity = 0;
            $default->max_pure_alcool_quantity = 0;
            $default->sum_pure_alcool_quantity = 0;
            $default->max_glass = 0;
            $default->sum_glass = 0;
            $default->max_shot = 0;
            $default->sum_shot = 0;
            return $default;
        }
        else{
            return $stats[0];
        }
    }

    private static function calculateRanks($results, $user_id) {
        $sample = $results[0];
        $categories = array_keys(get_object_vars($sample));
        
        if (($key = array_search('user_id', $categories)) !== false) {
            unset($categories[$key]);
        }
    
        $ranks = new stdClass();
    
        foreach ($categories as $category) {
  
            usort($results, function($a, $b) use ($category) {
                return $b->$category - $a->$category;
            });

            $ranks->$category = count($results)+1;
            
            foreach ($results as $rank => $result) {
                if ($result->user_id == $user_id) {
                    $ranks->$category = $rank + 1; 
                    break;
                }
            }
        }
    
        return $ranks;
    }

    public static function getRanks($userID): stdClass
    {
        $statsTable = config('cytropcool.database.table.user_statistiques');

        $results = DB::select("SELECT
            user_id,
            MAX(max_gl) AS max_gl,
            SUM(max_gl) AS sum_max_gl,
            MAX(alcool_quantity) AS max_alcool_quantity,
            SUM(alcool_quantity) AS sum_alcool_quantity,
            MAX(pure_alcool_quantity) AS max_pure_alcool_quantity,
            SUM(pure_alcool_quantity) AS sum_pure_alcool_quantity,
            MAX(glass) AS max_glass,
            SUM(glass) AS sum_glass,
            MAX(shot) AS max_shot,
            SUM(shot) AS sum_shot
        FROM
            $statsTable
        GROUP BY
            $statsTable.user_id
        ;");


        if($results == []){
            $results = [];
            $default = new stdClass();
            $default->user_id = $userID;
            $default->max_gl = 0;
            $default->sum_max_gl = 0;
            $default->max_alcool_quantity = 0;
            $default->sum_alcool_quantity = 0;
            $default->max_pure_alcool_quantity = 0;
            $default->sum_pure_alcool_quantity = 0;
            $default->max_glass = 0;
            $default->sum_glass = 0;
            $default->max_shot = 0;
            $default->sum_shot = 0;
            array_push($results, $default);
        }
        

        return ProfileController::calculateRanks($results, $userID);
    }

    public static function getSessionsHistory($userID): array
    {
        $statsTable = config('cytropcool.database.table.user_statistiques');
        $sessionsTable = config('cytropcool.database.table.sessions');

        $history = DB::select("SELECT
            $sessionsTable.id,
            $sessionsTable.name,
            $statsTable.max_gl,
            $statsTable.alcool_quantity,
            $statsTable.pure_alcool_quantity,
            $statsTable.glass,
            $statsTable.shot
        FROM
            $statsTable
        LEFT JOIN 
            $sessionsTable
        ON
            $statsTable.session_id = $sessionsTable.id
        WHERE
            $statsTable.user_id = ?
        ORDER BY
            $sessionsTable.created_at DESC
        ;
        ", [$userID]);

        return $history;
    }

    public static function updateUser(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:1',
            'weight' => 'bail|required|int|min:20',
            'sexe' => ['bail','required',str_replace('.', '\.', 'regex:/'.config('cytropcool.constant.female').'|'.config('cytropcool.constant.male').'/')]
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with(["update-failed" => $validator->errors()->first()])->withInput();
        }
        else{
            $userTable = config('auth.providers.users.table');

            DB::update("UPDATE 
                $userTable 
            SET
                name=?,weight=?,sexe=?
            WHERE
                id=?
            ;", 
            [$request->name, $request->weight, $request->sexe, Auth::user()->id]);

            return Redirect::back()->with(["update-success" => "Tes données sont à jour."])->withInput();
        }
    }

    public static function updateHold(Request $request){
        $userTable = config('auth.providers.users.table');
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $holdTable = config('cytropcool.database.table.holdable');
        $meta_typeTable = config('cytropcool.database.table.meta_holdable_type');

        $userHold = HoldableController::getCurrentHold(Auth::user()->id);

        foreach($request->all() as $category => $value){
            if(str_starts_with($category, "_") || $category == "name" || $category == "weight" || $category == "sexe"){
                continue;
            }

            $item = DB::select("SELECT
                $holdTable.id,
                $holdTable.type,
                $holdTable.category,
                $holdTable.name,
                $holdTable.data
            FROM
                $userHoldingTable
            JOIN
                $holdTable
            ON
                $userHoldingTable.item_id = $holdTable.id
            WHERE
                item_id = ?
                AND
                user_id = ?
                AND
                $holdTable.category = ?
            LIMIT 1
            ;", [$request->$category, Auth::user()->id, $category]);


            if(count($item) == 1){
                $userHold->$category = $item[0];
            }
            else{
                return Redirect::back()->with(["hold-failed" => "Tu ne possède pas cet élèment ou il n'existe pas"])->withInput();
            }
        }

        $metaType = DB::select("SELECT * FROM $meta_typeTable");

        $newUserHold = new stdClass();

        foreach($metaType as $mt){
            $type = $mt->type;
            $newUserHold->$type = $mt->array ? [] : 0;
        }

        foreach($userHold as $category => $_){
            $type = $userHold->$category->type;
            if(is_array($newUserHold->$type)){
                array_push($newUserHold->$type, $userHold->$category->id);
            }
            else{
                $newUserHold->$type = $userHold->$category->id;
            }
        }

        DB::update("UPDATE 
            $userTable 
        SET
            hold=?
        WHERE
            id=?
        ;", [json_encode($newUserHold), Auth::user()->id]);

        return Redirect::back()->with(["hold-success" => "Ton style a été mit à jour !"])->withInput();

    }

}