<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Http\Controllers\ProfileController;


use \Exception;
use \stdClass;


class HoldableController extends Controller
{
    public static function addHoldable($userID, array $style)
    {
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $styleTable = config('cytropcool.database.table.holdable');
        
        foreach($styles as $style){
            try{
                DB::insert("INSERT INTO
                    $userHoldingTable
                    (user_id , item_id, bought_at) 
                VALUES 
                    (?,?)
                ;", [$userID, $style->id, $style->price]);
            }
            catch(Exception $e){
                continue;
            }
            
        }
    }
    
    public static function getCurrentHold($userID){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $holdableTable = config('cytropcool.database.table.holdable');
        $_metaType = config('cytropcool.database.table.meta_holdable_type');


        $hold = new stdClass();
        $hold->id = $userID;
        $hold->username = ProfileController::getUser($userID)->name;

        $locked = [];

        $userHoldings = DB::select("SELECT
            $holdableTable.id,
            $holdableTable.category,
            $holdableTable.name,
            $holdableTable.data,
            $holdableTable.only,
            $_metaType.id as type,
            $_metaType.position,
            $_metaType.tag
        FROM
            $userHoldingTable
        JOIN
            $holdableTable
        ON
            $userHoldingTable.item_id = $holdableTable.id
        JOIN
            $_metaType
        ON
            $holdableTable.type = $_metaType.id
        WHERE
                $userHoldingTable.user_id = ?
            AND
                $userHoldingTable.hold = 1
        ;", [$userID]);

        foreach($userHoldings as $item){
            $p = $item->position;
            if(!isset($hold->$p)){
                $hold->$p = [];
            }

            if($item->only){
                array_push($locked, $item->position);
                $hold->$p = ["$item->tag" => [$item]];
            }
            else if( !in_array($item->type, $locked)){
                if(!isset($hold->$p["$item->tag"])){
                    $hold->$p["$item->tag"] = [];
                }
                array_push($hold->$p["$item->tag"], $item);
            }
        }
        return $hold;
    }
    
    public static function displayHold(array $userIDs, array $customUserNameClass = []){
        $runVariable = new stdClass();
        $runVariable->cui = 0;
        $runVariable->user = [];
        $runVariable->customClass = implode(' ',$customUserNameClass);
        
        foreach($userIDs as $id){
            array_push($runVariable->user, self::getCurrentHold($id));
        }

        $runVariable->currentUser = $runVariable->user[$runVariable->cui];
        
        return ["_HOLD_RUN" => $runVariable];
    }
    
    public static function getInventory($userID){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $styleTable = config('cytropcool.database.table.holdable');
        $_metaType = config('cytropcool.database.table.meta_holdable_type');
        
        $styles = DB::select("SELECT
            $styleTable.id,
            $styleTable.category,
            $styleTable.name,
            $styleTable.data,
            $userHoldingTable.hold,
            $_metaType.id as type,
            $_metaType.position,
            $_metaType.tag
        FROM
            $styleTable
        LEFT JOIN
            $userHoldingTable
        ON
            $styleTable.id = $userHoldingTable.item_id 
        JOIN
            $_metaType
        ON
            $styleTable.type = $_metaType.id
        WHERE
            $userHoldingTable.user_id = ?
            OR
            $styleTable.auto_hold = 1
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

    public static function displayInventory(int $userID, array $customUserNameClass = []){
        $runVariable = new stdClass();
        $runVariable->username = ProfileController::getUser($userID)->name;
        $runVariable->customClass = implode(' ',$customUserNameClass);
        $runVariable->toDisplay = null; 

        return ["_INVENTORY_RUN" => $runVariable];
    }
}
