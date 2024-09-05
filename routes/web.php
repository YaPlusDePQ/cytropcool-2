<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Middleware\StdAuth;
use App\Http\Middleware\SessionAuth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HoldableController;
use App\Http\Controllers\CytropivreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FriendController;

/**
 * ########################################################################
 *           HOME
 * ########################################################################
 */

 Route::get('/', function () {
    return view('home', ['articles'=>HomeController::getArticles()]);
});

Route::get('/article/{title}', function ($smug) {
    $article = HomeController::getArticle($smug);
    if($article == null){
        abort(404);
    }
    return view('article.'.$article->view, ['article'=>$article]);
});

/**
 * ########################################################################
 *           LOGIN/LOGOUT/SIGNIN
 * ########################################################################
 */

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function (Request $request) {
    return AuthController::StdLogin($request);
});

Route::get('/logout', function (Request $request) {
    return AuthController::StdLogout($request);
});

Route::get('/signin', function(){
    return view('auth.signin');
});

Route::post('/signin', function (Request $request) {
    return AuthController::StdSignin($request);
});

Route::get('/forgot-password', function (Request $request) {
    return view('auth.forgot-password');
});

Route::post('/forgot-password', function (Request $request) {
    return AuthController::StdForgotPassword($request);
});

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
});

Route::post('/reset-password/{token}', function (Request $request) {
    return  AuthController::StdResetPassword($request);
});

/**
 * ########################################################################
 *           Account
 * ########################################################################
 */

Route::get('/profile', function () {
    return view('account.self', [
        'inventory' => HoldableController::getInventory(Auth::user()->id),
        'stats' => ProfileController::getStats(Auth::user()->id),
        'ranks' => ProfileController::getRanks(Auth::user()->id),
        'history' => ProfileController::getSessionsHistory(Auth::user()->id)
    ]
    +HoldableController::displayHold([Auth::user()->id], ['pseudo'])
    +HoldableController::displayInventory(Auth::user()->id, ['displayer_pseudo'])
);
})->middleware([StdAuth::class]);

Route::put('/profile', function (Request $request){
    return ProfileController::updateUser($request);
})->middleware([StdAuth::class]);

Route::post('/profile', function (Request $request){
    return ProfileController::updateHold($request);
})->middleware([StdAuth::class]);

Route::post('/holdable/preview', function (Request $request) {
    return ProfileController::previewHold($request);
})->middleware([StdAuth::class]);

Route::get('/profile/{userID}', function (String $userID) {
    $user = ProfileController::getUser($userID);
    if($user == null){
        return view('account.unknow');
    }

    $inventory = HoldableController::getInventory($user->id, false);
    $cost = 0;
    foreach($inventory as $cat => $holds){
        foreach($holds as $h){
            $cost += $h->bought_at;
        }
    }


    return view('account.public', [
        'user' => $user,
        'inventory' => $inventory,
        'stats' => ProfileController::getStats(Auth::user()->id),
        'ranks' => ProfileController::getRanks(Auth::user()->id),
        'inventoryCost' => $cost
    ]
    +HoldableController::displayHold([$userID], ['public_pseudo'])
    +HoldableController::displayInventory($userID, ['public_displayer_pseudo'])
    );
});

Route::post('/profile/{userID}', function ($userID) {
    return FriendController::sendRequest($userID);
 })->middleware([StdAuth::class]);
 

Route::get('/friends', function () {
    $ids = FriendController::getFriends();
    $friendsSession = [];
    foreach($ids as $id){
        $friendsSession[ $id] = CytropivreController::getSession($id);
    }
    return view('account.friends', [
        'friends' => $ids,
        'sessions' => $friendsSession,
        'requests' => FriendController::getRequest()
    ]
    +HoldableController::displayHold($ids));
})->middleware([StdAuth::class]);

Route::put('/friends', function (Request $request) {
   return FriendController::acceptRequest($request);
})->middleware([StdAuth::class]);

Route::delete('/friends', function (Request $request) {
    return FriendController::deleteFriend($request);
 })->middleware([StdAuth::class]);

/**
 * ########################################################################
 *           CYTROPIVRE
 * ########################################################################
 */


Route::get('/cytropivre/create', function(){
    return view('cytropivre.create');
})->middleware([StdAuth::class]);

Route::post('/cytropivre/create', function(Request $request){
    return CytropivreController::createSession($request);
})->middleware([StdAuth::class]);

Route::get('/cytropivre/search', function(){
    return view('cytropivre.search', ['sessions' => CytropivreController::getPublicSession()]);
})->middleware([StdAuth::class]);

Route::post('/cytropivre/search', function(Request $request){
    return CytropivreController::joinSession($request);
})->middleware([StdAuth::class]);

Route::get('/cytropivre/join/{sessionId}', function($sessionId){
    return CytropivreController::joinSessionLink($sessionId);
})->middleware([StdAuth::class]);

Route::get('/cytropivre/session', function(){
    $userSessionTable = config('cytropcool.database.table.user_session');

    $drinks = CytropivreController::getDrink();
    $eat = DB::select("SELECT eat FROM $userSessionTable WHERE user_id = ?", [Auth::user()->id])[0]->eat;

    $dt = new DateTime("now", new DateTimeZone('Europe/Paris'));
    $dt->setTimestamp(time());

    return view('cytropivre.session', [
        'session' => CytropivreController::getSessionData(CytropivreController::getSession(Auth::user()->id)),
        'eat' => $eat,
        'rate' => CytropivreController::getCurrentRateOfUser($drinks, Auth::user()->sexe, Auth::user()->weight, $eat),
        'drinks' => $drinks,
        'date' => $dt->format('Y-m-d\TH:i')
    ]+HoldableController::displayHold([Auth::user()->id]));
})->middleware([StdAuth::class, SessionAuth::class]);

Route::patch('/cytropivre/session', function(Request $request){
    return CytropivreController::setEat($request);
})->middleware([StdAuth::class, SessionAuth::class]);

Route::post('/cytropivre/session', function(Request $request){
    return CytropivreController::addDrink($request);
})->middleware([StdAuth::class, SessionAuth::class]);

Route::put('/cytropivre/session', function(Request $request){
    return CytropivreController::updateDrink($request);
})->middleware([StdAuth::class, SessionAuth::class]);

Route::delete('/cytropivre/session', function(Request $request){
    if(isset($request->delete_session)){
        return CytropivreController::quitSession();
    }
    else{
        return CytropivreController::deleteDrink($request);
    }
})->middleware([StdAuth::class, SessionAuth::class]);

Route::get('/cytropivre/scoreboard', function(){
    $sessionId = CytropivreController::getSession(Auth::user()->id);

    return view('cytropivre.scoreboard', CytropivreController::getScoreboard($sessionId));
})->middleware([StdAuth::class, SessionAuth::class]);

Route::get('/cytropivre/scoreboard/update', function(){
    $sessionId = CytropivreController::getSession(Auth::user()->id);

    return view('cytropivre.sbInside', CytropivreController::getScoreboard($sessionId));
})->middleware([StdAuth::class, SessionAuth::class]);

/**
 * ########################################################################
 *           SHOP
 * ########################################################################
 */

 Route::get('/shop', function(){
    return view('shop', [
        'shop'=>ShopController::getShop()
    ]+HoldableController::displayInventory(Auth::user()->id, ['displayer_pseudo']));
})->middleware([StdAuth::class]);

Route::post('/shop', function(Request $request){
    return ShopController::buyHold($request);
})->middleware([StdAuth::class]);

/**
 * ########################################################################
 *           CYTROPFUN
 * ########################################################################
 */

 Route::get('/cytropfun/ouiounon', function(){
    return view('cytropfun.ouiounon');
});

/**
 * ########################################################################
 *           DEBUG
 * ########################################################################
 */

Route::get('/debug/data-view', function () {
    return view('debug.data-view',['data'=>FriendController::getRequest()]);
})->middleware([StdAuth::class]);

Route::get('/debug/test', function () {
    return view('debug.test');
});