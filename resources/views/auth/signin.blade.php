<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/auth/signin.css')}}" rel='stylesheet'>
    <title>CYtropcool - Inscription</title>
</head>
<body>
    @include('header')
    
    <div class="content">
        <h1>S'INSCIRE</h1>

        <div class="form-wrapper" >
            <form id="signup-form" class="form" method="POST">

                @csrf <!-- {{ csrf_field() }} -->

                <div  class="failed-msg" @if(!$errors->any()) {{'hidden'}} @endif>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    @endif
                </div>
                <div class="success-msg" @if(!Session::has('success')) {{'hidden'}} @endif>
                    @if (Session::has('success'))
                    {{ Session::get('success') }}
                    @endif
                </div>

                <span>E-mail</span>
                <input type="text" name="email"  value="{{old('email')}}">

                <span>Pseudo</span>
                <input type="text" name="name"  value="{{old('name')}}">

                <span>Mot de passe</span>
                <input type="password" name="password">

                <span>Confirmer le mot de passe</span>
                <input type="password" name="password_confirmation">

                <span>Poids</span>
                <input type="number" name="weight"  value="{{old('weight')}}">

                <span>Sexe</span>
                <select name="sexe"  value="{{old('sexe')}}">
                    <option value="{{config('cytropcool.constant.male')}}">Homme</option>
                    <option value="{{config('cytropcool.constant.female')}}">Femme</option>
                </select>
                <button type="submit" class="action" >S'inscire</button>

            </form>

        </div>
        
        <p> Déjà dans la quoicouteam ? <a href="login">Connecte toi !</a></p>
        
    </div>
</body>
</html>