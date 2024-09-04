<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public static function getArticles(){
        $articleTable = config('cytropcool.database.table.article');
        return DB::select("SELECT * FROM $articleTable;");
    }

    public static function getArticle(string $smug){
        $articleTable = config('cytropcool.database.table.article');
        $article = DB::select("SELECT * FROM $articleTable WHERE smug=? LIMIT 1;", [$smug]);
        if(count($article) != 1){
            return null;
        }
        return $article[0];
    }
}
