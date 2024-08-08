<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/cytropivre/create.css')}}" rel='stylesheet'>
    <title>CYtropcool - Nouvelle session</title>
</head>
<body>
    
    @include('header')

    <div class="content">
        <h1>CREER UNE SESSION</h1>
        <div class="form-wrapper">
            <form class="form" method="POST">

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

                <span>Nom:</span>
                <input type="text" name="name" value='{{old("email")}}' required>

                <div class="checkbox">
                    <span>Session privée</span>
                    <input type="checkbox" name="private" >
                </div>

                <button type="submit" class="action" >Connexion</button>

            </form>

        </div>
        
    </div>
</body>
</html>