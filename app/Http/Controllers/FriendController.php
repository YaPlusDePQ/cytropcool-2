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

class FriendController extends Controller
{
    public static function getFriends(){
        $friendsTable = config('cytropcool.database.table.user_friends');
        $friendsList = [];
        $friendsQuery = DB::select("SELECT
            *
        FROM
            $friendsTable
        WHERE
            (
                `from` = ?
                OR
                `to` = ?
            )
            AND
            accepted = 1
        ;", [Auth::user()->id, Auth::user()->id]);

        foreach($friendsQuery as $result){
            array_push($friendsList, ($result->from == Auth::user()->id ? $result->to : $result->from));
        }

        return $friendsList;
    }

    public static function getRequest(){
        $friendsTable = config('cytropcool.database.table.user_friends');
        $requestList = [];
        $friendsQuery = DB::select("SELECT
            *
        FROM
            $friendsTable
        WHERE
            `to` = ?
            AND
            accepted = 0
        ;", [Auth::user()->id]);

        foreach($friendsQuery as $result){
            array_push($requestList, ProfileController::getUser($result->from));
        }

        return $requestList;
    }

    public static function sendRequest(string $userId){
        $friendsTable = config('cytropcool.database.table.user_friends');

        if($userId == Auth::user()->id){
            return Redirect::back()->withErrors(["failed" => "Bah oui mais non du coup."]);
        }

        $alreadyQuery = DB::select("SELECT
            *
        FROM
            $friendsTable
        WHERE
            (`from`=? AND `to`=?)
            OR
            (`to`=? AND `from`=?)
        ;", [Auth::user()->id, $userId, Auth::user()->id, $userId]);

        if(count($alreadyQuery) != 0){
            return Redirect::back()->withErrors(["failed" => "Vous êtes déjà amis ou une demande a déjà été envoyée."]);
        }

        $affected = DB::insert("INSERT INTO
            $friendsTable
            (`from` , `to`) 
        VALUES 
            (?,?)
        ;", [Auth::user()->id, $userId]);

        if($affected == 1){
            return Redirect::back()->with(["success" => "Une demande d'ami a été envoyée."]);
        }
        else{
            return Redirect::back()->withErrors(["failed" => "Oops, une erreur est survenue."]);
        }

    }

    public static function acceptRequest(Request $request){
        $friendsTable = config('cytropcool.database.table.user_friends');

        $validator = Validator::make($request->all(), [
            'from' => 'bail|required|int|min:1'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors(["failed" => $validator->errors()->first()]);
        }

        $affected = DB::update("UPDATE 
            $friendsTable 
        SET
            accepted=1
        WHERE
            `from`=?
            AND
            `to`=?
        ;", 
        [$request->from, Auth::user()->id]);

        if($affected == 1){
            return Redirect::back()->withErrors(["failed" => "Ami ajouté avec succès."]);
        }
        else{
            return Redirect::back()->withErrors(["failed" => "Oops, une erreur est survenue."]);
        }
    }

    public static function deleteFriend(Request $request){
        $friendsTable = config('cytropcool.database.table.user_friends');

        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|int|min:1'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors(["failed" => $validator->errors()->first()]);
        }

        $affected = DB::delete("DELETE FROM 
            $friendsTable 
        WHERE
            (`from`=? AND `to`=?)
            OR
            (`to`=? AND `from`=?)
        ;", 
        [$request->id, Auth::user()->id, $request->id, Auth::user()->id]);

        
        if($affected == 1){
            return Redirect::back();
        }
        else{
            return Redirect::back()->withErrors(["failed" => "Oops, une erreur est survenue."]);
        }

    }

}
