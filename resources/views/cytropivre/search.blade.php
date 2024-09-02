<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/cytropivre/search.css')}}" rel='stylesheet'>
    <title>CYtropcool - Rejoindre une session</title>
</head>
<body>
    
    @include('header')

    <div class="content">
        <h1>REJOINDRE</h1>

        <div  class="failed-msg" @if(!$errors->any())  {{'hidden'}} @endif>
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

        <div class="code">

            <form method="POST">
                @csrf <!-- {{ csrf_field() }} -->
                <span>Code:</span>
                <input type="text" name="id" value="" maxlength=6>
                <button type="submit" class="action" >Rejoindre</button> 
            </form>

        </div>

        <hr class="separator">

        <div class="session-select">
            <h2>SESSIONS PUBLIQUES</h2>
            <div id="session-viewer">
                @foreach($sessions as $session)
                <div class="session-wrapper">
                    <span>{{$session->name}}</span> 
                    <form method="POST">
                        @csrf <!-- {{ csrf_field() }} -->
                        <input type="text" name="id" value="{{$session->id}}" hidden>
                        <button type="submit" class="action" >Rejoindre</button> 
                    </form>
                </div>
                @endforeach
            </div>
        <div>
        
    </div>

</body>
</html>