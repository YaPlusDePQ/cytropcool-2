<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/auth/login.css')}}" rel='stylesheet'>
    <title>CYtropcool - Réinitalisation de mot de passe</title>
</head>
<body>
    @include('header')

    <div class="content">
        <h1>RÉINITALISATION DE MOT DE PASSE</h1>
        <div class="form-wrapper">
            <form id="login-form" class="form" method="POST">

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

                <span>E-mail:</span>
                <input type="text" name="email" value='{{old("email")}}' required>

                <button type="submit" class="action" >Réinitaliser le mot de passe</button>

            </form>

        </div>
        
        <p> Déjà dans la quoicouteam ? <a href="login">Connecte toi !</a></p>
    </div>
</body>
</html>