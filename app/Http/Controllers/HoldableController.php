<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use \Exception;
use \stdClass;


class HoldableController extends Controller
{
    public static function addHoldable($userID, array $stylesId)
    {
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $styleTable = config('cytropcool.database.table.holdable');
        
        foreach($stylesId as $style){
            try{
                DB::insert("INSERT INTO
                    $userHoldingTable
                    (user_id , item_id) 
                VALUES 
                    (?,?)
                ;", [$userID, $style]);
            }
            catch(Exception $e){
                continue;
            }
            
        }
    }
    
    public static function getCurrentHold($userID){
        $userTable = config('auth.providers.users.table');
        $holdTable = config('cytropcool.database.table.holdable');
        
        $result = json_decode(DB::select("SELECT hold FROM $userTable WHERE id=? LIMIT 1;", [$userID])[0]->hold);
        $hold = new stdClass();
        
        foreach($result as $type => $holdableId){
            
            if(is_array($holdableId)){
                
                foreach($holdableId as $hid){
                    $item =  DB::select("SELECT * FROM $holdTable WHERE id=? LIMIT 1;", [$hid])[0];
                    $c = $item->category;
                    $hold->$c = $item;
                }
            }
            else{
                $item = DB::select("SELECT * FROM $holdTable WHERE id=? LIMIT 1;", [$holdableId])[0];
                $c = $item->category;
                $hold->$c = $item;
            }
        }
        
        return $hold;
    }
    
    public static function displayHold(array $userIDs){
        $userTable = config('auth.providers.users.table');
        $holdTable = config('cytropcool.database.table.holdable');
        
        $hold = [];
        
        
        foreach($userIDs as $id){
            $result = json_decode(DB::select("SELECT hold FROM $userTable WHERE id=? LIMIT 1;", [$id])[0]->hold);
            $buffer = new stdClass();
            $buffer->userId = $id;
            
            foreach($result as $type => $holdableId){
                
                if(is_array($holdableId)){
                    $buffer->$type = [];
                    
                    foreach($holdableId as $hid){
                        array_push($buffer->$type, DB::select("SELECT id,data FROM $holdTable WHERE id=? LIMIT 1;", [$hid])[0]);
                    }
                }
                else{
                    $buffer->$type = DB::select("SELECT id,data FROM $holdTable WHERE id=? LIMIT 1;", [$holdableId])[0];
                }
            }
            
            array_push($hold, $buffer);
            
        }
        
        return ["_HOLD_RUN_CURRENT_USER" => 0, "_HOLD_RUN_DATA" => $hold];
    }
    
    public static function getInventory($userID){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $styleTable = config('cytropcool.database.table.holdable');
        
        $styles = DB::select("SELECT
            $styleTable.id,
            $styleTable.type,
            $styleTable.category,
            $styleTable.name,
            $styleTable.data
        FROM
            $styleTable
        JOIN
            $userHoldingTable
        ON
            $styleTable.id = $userHoldingTable.item_id 
        WHERE
            $userHoldingTable.user_id = ?
        ORDER BY
            $styleTable.id ASC
        ;", [$userID]);
        
        $inventory = new stdClass();
        
        foreach($styles as $style){
            $cat = $style->category;
            
            if(!isset($inventory->$cat)){
                $inventory->$cat = [$style];
            }
            else{
                array_push($inventory->$cat, $style);
            }
        }
        
        
        return $inventory;
    }
}
