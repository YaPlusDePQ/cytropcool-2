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

    public static function filterHoldable($userID, array $holdableIds){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $holdableTable = config('cytropcool.database.table.holdable');

        $ids = implode(',', $holdableIds);

        $askedHoldable = DB::select("SELECT 
            * 
        FROM 
            $holdableTable 
        JOIN
            $userHoldingTable
        ON
            $holdableTable.id = $userHoldingTable.item_id 
        WHERE
            $userHoldingTable.user_id = ?
            AND
            $holdableTable.id IN ($ids)
            ;
        ", [$userID]);

        $filterdHoldable = [];
        $category = [];
        foreach( $askedHoldable as $item ){
            if( $item->category == null || $item->position == null || $item->tag == null){
                continue;
            }

            if(!in_array( $item->category, $category)){
                array_push($category, $item->category);
                array_push($filterdHoldable, $item->id);
            }
        }

        return $filterdHoldable;
    }

    public static function addHoldable($userID, array $styles){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $styleTable = config('cytropcool.database.table.holdable');
        $added = 0;
        foreach($styles as $style){
            try{
                DB::insert("INSERT INTO
                    $userHoldingTable
                    (user_id , item_id, bought_at) 
                VALUES 
                    (?,?,?)
                ;", [$userID, $style->id, $style->price]);
                $added += 1;
            }
            catch(Exception $e){
                continue;
            }
            
        }

        return $added;
    }

    public static function setHoldable($userID, array $holdableIds){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $holdableTable = config('cytropcool.database.table.holdable');

        $filteredIds = self::filterHoldable($userID, $holdableIds);
        if(count($filteredIds) == 0){
            $filteredIds = [-1];
        }

        $ids = implode(',', $filteredIds);

        $affected = DB::update("UPDATE 
            $userHoldingTable 
        SET
            hold=(item_id IN ($ids))
        WHERE
            user_id=?
        ;", 
        [$userID]);

        return $affected;
    }
    
    public static function getCurrentHold($userID, array $holdableIds = []){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $holdableTable = config('cytropcool.database.table.holdable');

        $hold = new stdClass();
        $hold->id = $userID;
        $hold->username = ProfileController::getUser($userID)->name;

        $userHoldings = [];

        if(count($holdableIds) == 0){
            $userHoldings = DB::select("SELECT
                *
            FROM
                $holdableTable
            JOIN
                $userHoldingTable
            ON
                $holdableTable.id = $userHoldingTable.item_id

            WHERE
                    $userHoldingTable.user_id = ?
                AND 
                    $userHoldingTable.hold = 1

                
            ;", [$userID]);
        }
        else{

            $filteredIds = self::filterHoldable($userID, $holdableIds);
            if(count($filteredIds) > 0){
                $ids = implode(',', $filteredIds);
                $userHoldings = DB::select("SELECT
                    *
                FROM
                    $holdableTable
                WHERE
                    $holdableTable.id IN ($ids)
                ;");
            }
            
        }
        

        $defaultHoldable = DB::select("SELECT * FROM $holdableTable WHERE name = 'default' AND auto_hold = 1;");

        $category = [];
        foreach( $userHoldings as $item ){
            $item->data = str_replace('&asset&', asset('/'), $item->data);
            if( $item->category == null || $item->position == null || $item->tag == null){
                continue;
            }

            if(!in_array( $item->category, $category)){
                array_push($category, $item->category);
            }
        }

        foreach( $defaultHoldable as $dfh ){
            if( $dfh->category == null || $dfh->position == null || $dfh->tag == null){
                continue;
            }

            if(!in_array( $dfh->category, $category)){
                array_push($userHoldings, $dfh);
            }
        }

        foreach($userHoldings as $item){
            if( $item->category == null || $item->position == null || $item->tag == null){
                continue;
            }

            $p = $item->position;
            if(!isset($hold->$p)){
                $hold->$p = [];
            }

            if(!isset($hold->$p["$item->tag"])){
                $hold->$p["$item->tag"] = [];
            }
            array_push($hold->$p["$item->tag"], $item);
        }
        return $hold;
    }
    
    public static function displayHold(array $userIDs, array $customUserNameClass = [], $userIdClass = '_user'){
        $runVariable = new stdClass();
        $runVariable->cui = 0;
        $runVariable->user = [];
        $runVariable->customClass = implode(' ',$customUserNameClass);
        $runVariable->userIdClass = $userIdClass;

        foreach($userIDs as $id){
            array_push($runVariable->user, self::getCurrentHold($id));
        }

        if(count($runVariable->user) > 0){
            $runVariable->currentUser = $runVariable->user[$runVariable->cui];
        }
        else{
            $runVariable->currentUser = null;
        }
        
        return ["_HOLD_RUN" => $runVariable];
    }
    
    public static function displayHoldPreview($userID, array $customUserNameClass = [], array $holdableIds, $userIdClass = '_user'){
        $runVariable = new stdClass();
        $runVariable->cui = 0;
        $runVariable->user = [];
        $runVariable->customClass = implode(' ',$customUserNameClass);
        $runVariable->userIdClass = $userIdClass;

        array_push($runVariable->user, self::getCurrentHold($userID, $holdableIds));

        $runVariable->currentUser = $runVariable->user[$runVariable->cui];


        return ["_HOLD_RUN" => $runVariable];
    }

    
    public static function getInventory($userID, $autoHold = true){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $styleTable = config('cytropcool.database.table.holdable');
        
        $styles = DB::select("SELECT
           *
        FROM
            $styleTable
        LEFT JOIN
            $userHoldingTable
        ON
            $styleTable.id = $userHoldingTable.item_id 
        WHERE
            $userHoldingTable.user_id = ?
            OR
            ($styleTable.auto_hold = 1 AND ?)
        ORDER BY
            $styleTable.id ASC
        ;", [$userID, $autoHold]);
        
        $inventory = new stdClass();
        
        foreach($styles as $style){
            $style->data = str_replace('&asset&', asset('/'), $style->data);
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
