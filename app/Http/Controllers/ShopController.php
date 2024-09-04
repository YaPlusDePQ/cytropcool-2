<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\HoldableController;

use \stdClass;

class ShopController extends Controller
{
    public static function getShop(){
        $userId = Auth::user()->id;
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $holdableTable = config('cytropcool.database.table.holdable');
        
        $userHoldings = DB::select("SELECT
                *
            FROM
                $holdableTable
            WHERE
                $holdableTable.id NOT IN (SELECT item_id FROM $userHoldingTable WHERE user_id = ?)
                AND
                $holdableTable.shop = 1
            ORDER BY
                $holdableTable.id ASC
            ;", [$userId]);
        
        $shop = new stdClass();
        
        foreach($userHoldings as $h){
            $cat = $h->category;
            
            if(!isset($shop->$cat)){
                $shop->$cat = [$h];
            }
            else{
                array_push($shop->$cat, $h);
            }
        }
        
        
        return $shop;
    }

    public static function buyHold(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|int|min:1'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors(["failed" => $validator->errors()->first()]);
        }

        $holdableTable = config('cytropcool.database.table.holdable');
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $userTable = config('auth.providers.users.table');

        $userId = Auth::user()->id;

        $item = DB::select("SELECT 
            * 
        FROM 
            $holdableTable 
        WHERE
            id = ?
            AND
            shop = 1
        LIMIT 1;
        ", [$request->id]);

        if(count($item) == 0){
            return Redirect::back()->withErrors(["failed" => "L'item sélectionné n'existe pas."]);
        }

        $item = $item[0];

        $alreadyBought = DB::select("SELECT 
            * 
        FROM 
            $userHoldingTable 
        WHERE
            item_id  = ?
            AND
            user_id = ?
        LIMIT 1;
        ", [$request->id, $userId]);

        if(count($alreadyBought) != 0){
            return Redirect::back()->withErrors(["failed" => "Tu possèdes déjà cet item."]);
        }

        if($item->price > Auth::user()->crampte){
            return Redirect::back()->withErrors(["failed" => "Tu ne possèdes pas assez de crampté."]);
        }

        if(HoldableController::addHoldable($userId, [$item])){
            DB::update("UPDATE 
                $userTable 
            SET
                crampte=?
            WHERE
                id=?
            ;", 
            [Auth::user()->crampte - $item->price, Auth::user()->id]);

            return Redirect::back()->with(["success" => "L'item a bien été acheté !"]);
        }
        else{
            return Redirect::back()->withErrors(["failed" => "Oops, une erreur est survenue."]);
        }

    }
}
