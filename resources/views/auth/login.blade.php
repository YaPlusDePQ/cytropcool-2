<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/auth/login.css')}}" rel='stylesheet'>
    <title>CYtropcool - Connexion</title>
</head>
<body>
    
    @include('header')

    <div class="content">
        <h1>CONNEXION</h1>
        <div class="form-wrapper">
            <form id="login-form" class="form" method="POST">

                @csrf <!-- {{ csrf_field() }} -->

                <input type="text" name="redirect" value="{{Session::has('redirect') ? Session::get('redirect') : old('redirect')}}" hidden>

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

                <span>Mot de passe:</span>
                <input type="password" name="password" required>

                <button type="submit" class="action" >Connexion</button>
                <p> <a href="forgot-password">Mot de passe oublié </a> </p>

            </form>

        </div>
        
        <p>Quoicounouveau ? <a href="signin">Crée-toi un compte !</a> </p>
    </div>
</body>
</html>