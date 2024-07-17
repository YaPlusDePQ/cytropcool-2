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


class StyleController extends Controller
{
    public static function addStyle($userID, array $stylesId)
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

    public static function getCurrentStyle($userID){
        $userTable = config('auth.providers.users.table');
        $styleTable = config('cytropcool.database.table.holdable');

        $userStyle = json_decode(DB::select("SELECT style FROM $userTable WHERE id=? LIMIT 1;", [$userID])[0]->style, true);
        $userFullStyle = new stdClass();

        $data = null;
        foreach($userStyle as $type => $id){
            $userFullStyle->$type = new stdClass();
            $userFullStyle->$type->id = $id;


            $data = json_decode(DB::select("SELECT data FROM $styleTable WHERE id=? LIMIT 1;", [$id])[0]->data, true);

            $userFullStyle->$type = (object) array_merge((array) $userFullStyle->$type, $data);
        }

        return $userFullStyle;
    }

    public static function getStyles($userID){
        $userHoldingTable = config('cytropcool.database.table.user_holding');
        $styleTable = config('cytropcool.database.table.holdable');

        $styles = DB::select("SELECT
            $styleTable.id,
            $styleTable.type,
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

        foreach($styles as $style){
            $style->data = json_decode($style->data);
        }

        return $styles;
    }
}
