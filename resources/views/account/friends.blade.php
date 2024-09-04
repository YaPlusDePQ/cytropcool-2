<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/account/friends.css')}}" rel='stylesheet'>
    <script src="{{ asset('/js/account/friends.js')}}" rel='stylesheet'></script>
    <title>CYtropcool - Amis</title>
</head>
<body>
    @include('header')

    <div class='section-title' onclick="HideMenu();">
        <h1>AMIS</h1>
        <p>Ici, tu peux voir tes Quoicoupotes. Si tu veux t'en faire de nouveaux, il te suffit de partager ton profil ou d'aller sur le leur et de cliquer sur "Ajouter en ami".</p>

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
    </div>
    @if(count($requests) > 0)
    <div class='section-requests' onclick="HideMenu();">
        <h2>DEMANDE D'AMI</h2>
            @foreach($requests as $r)
                <div class="request">
                    <span>{{$r->name}}</span>
                    <button class='cancel' onclick="deleteRequest({{$r->id}})">Refuser</button>
                    <button class='accept' onclick="acceptRequest({{$r->id}})">Accepter</button>
                </div>
            @endforeach

    </div>

    <hr class='separator'>

    @endif


    <div class='section-friends'>
        @if(count($friends) > 0)
            @foreach($friends as $f)
                <div class="friend" onclick="displayMenu('menu{{$f}}');">
                    @include('holdable.run.user')
                    <button class="more"><img src="{{ asset('./img/3dot.png') }}"></button>
                    <ul id='menu{{$f}}' class="fmenu">
                        <li><button onclick="window.location = '{{url('/profile/'.$f)}}'">Profile</button></li>
                        @if($sessions[$f])
                        <li><button onclick="window.location = '{{url('/cytropivre/join/'.$sessions[$f])}}'">Rejoindre</button></li>
                        @endif
                        <li><button class='delete' onclick="deleteFriend({{$f}})">Supprimer</button></li>
                    </ul>
                    
                </div>
                @include('holdable.run.increment')
            @endforeach
        @else
            Tu n'as pas de quoicoupote :'(
        @endif
    </div>

    <form id="accept" method="POST" hidden>
        @csrf <!-- {{ csrf_field() }} -->
        <input name="_method" value="PUT" readonly required hidden/>
        <input type="number" name="from" id="accept-input" readonly required>
    </form>

    <form id="delete" method="POST" hidden>
        @csrf <!-- {{ csrf_field() }} -->
        <input name="_method" value="DELETE" readonly required hidden/>
        <input type="number" name="id" id="delete-input" readonly required>
    </form>
</body>

<script>HideMenu();</script>
</html>