<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Support\Str;

use App\Http\Controllers\HoldableController;


class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public static function StdLogin(Request $request): RedirectResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'bail|required',
            'redirect' => 'nullable'
        ]);
    
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            if (Auth::attempt(['email'=> $request->email, 'password' => $request->password])) {
                $request->session()->regenerate();
                
                if($request->redirect) return redirect($request->redirect);
                else return redirect('/profile');
            }
            return Redirect::back()->withErrors('L\'e-mail ou le mot de passe est incorrect.')->withInput();
        }
    }

    public static function StdLogout(Request $request): RedirectResponse
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }

    public static function StdSignin(Request $request): RedirectResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'name' => 'bail|required|min:1|max:25',
            'password' => 'bail|required|confirmed|min:1',
            'weight' => 'bail|required|int|min:20',
            'sexe' => ['bail','required',str_replace('.', '\.', 'regex:/'.config('cytropcool.constant.female').'|'.config('cytropcool.constant.male').'/')]
        ]);
    
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $userTable = config('auth.providers.users.table');
            $statsTable = config('cytropcool.database.table.user_statistiques');
            
            $existingUser = DB::select("SELECT email FROM $userTable WHERE email = ? LIMIT 1;", [$request->email]);
            if( $existingUser){
                return Redirect::back()->withErrors('L\'e-mail est déjà utilisé.')->withInput();
            }

            DB::insert("INSERT INTO 
                $userTable 
                (`email`, `password`, `name`, `weight`, `sexe`) 
            VALUES
                (?, ?, ?, ?, ?)
            ;", 
            [$request->email, Hash::make($request->password), $request->name, $request->weight, $request->sexe]);

            $result = DB::select("SELECT 
                id
            FROM 
                $userTable 
            WHERE
                email=?
            LIMIT 1
            ;", 
            [$request->email]);

            if(count($result) == 0){
                return Redirect::back()->withErrors('Oops, une erreur est survenue.')->withInput();
            }

            return Redirect::back()->with(['success' => 'Inscription réussie, tu peux te connecter.']);
        }
    }

    public static function StdForgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $userTable = config('auth.providers.users.table');
        $forgotPasswordTokenTable = config('auth.passwords.users.table');

        $existingUser = DB::select("SELECT email FROM $userTable WHERE email = ? LIMIT 1;", [$request->email]);
        if(!$existingUser){
            return Redirect::back()->withErrors('L\'e-mail ne correspond à aucun compte.')->withInput();
        }

        DB::insert("INSERT INTO $forgotPasswordTokenTable (email, token, created_at) values (?,?,?);", [$request->email, Str::random(60), date("Y-m-d H:i:s")]);
    
        $tokenData  = DB::select("SELECT email, token FROM $forgotPasswordTokenTable WHERE email = ? LIMIT 1", [$request->email]);

        if(count($tokenData) == 0){
            return Redirect::back()->withErrors(['Oops, une erreur est survenue.']);
        }
        else{
            $tokenData = $tokenData[0];
        }
        
        $token = $tokenData->token;
        $email = $tokenData->email;

        
        /**
         * TODO: send email
         */


        return Redirect::back()->with(['success' => 'Un mail avec un lien de réinitialisation a été envoyé.']);
    }

    public static function StdResetPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'bail|required|email',
            'password' => 'bail|required|confirmed',
            'token' => 'bail|required'
        ]);
     
    
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $userTable = config('auth.providers.users.table');
        $forgotPasswordTokenTable = config('auth.passwords.users.table');

        $user = DB::select("SELECT created_at FROM $forgotPasswordTokenTable WHERE token = ? AND email = ? LIMIT 1;", [$request->token, $request->email]);

        if($user == []){
            return Redirect::back()->withErrors('Le lien utilisé est incorrect.');
        }
        else{
            $user = $user[0];
        }

        if(new \DateTime(date("Y-m-d H:i:s")) > (new \DateTime($user->created_at))->add(new \DateInterval('PT' . config('auth.passwords.users.expire') . 'M'))){
            return Redirect::back()->withErrors('Le lien utilisé a expiré.');
        }
        
        $affected = DB::update("UPDATE $userTable SET password = ? WHERE email = ?;", [Hash::make($request->password), $request->email]);

        if($affected == 0){
            return Redirect::back()->withErrors('Oops, une erreur est survenue.');
        }

        DB::delete("DELETE FROM $forgotPasswordTokenTable WHERE token = ?", [$request->token]);

        return Redirect::back()->with(['success' => 'Le mot de passe a été réinitialisé.']);
    }
}