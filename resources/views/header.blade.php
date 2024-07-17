<header  style="margin-bottom: 0px;">
    <link rel="stylesheet" href="{{ asset('/css/header.css') }}">
    <script src="{{ asset('/js/header.js') }}"></script>

    <div class="menu">
        <button id="show-hide-nav" onclick="hideshow('nav', false);hideshow('cytropivre', true)"><img src="{{ asset('./img/56763.png') }}"></button>
        <h1>CYTROPCOOL</h1>
    </div>
        
    <ul class="nav" id="nav" hidden>
        <li><a href="./">HOME</a></li>
        <li {{ Auth::check() ? 'hidden' : '' }} ><a href="./login">CONNEXION</a></li>
        <li {{ Auth::check() ? 'hidden' : '' }} ><a href="./signin">S'INSCRIRE</a></li>
        <li {{ Auth::check() ? '' : 'hidden' }} ><button onclick="hideshow('account', false)">COMPTE</button></li>
        <ul class="nav-sub" id="account" hidden>
            <li><a href="./profile">PROFIL</a></li>
            <li><a href="./">AMIS</a></li>
        </ul>
        <li {{ Auth::check() ? '' : 'hidden' }} ><button onclick="hideshow('cytropivre', false)">CYTROPIVRE</button></li>
        <ul class="nav-sub" id="cytropivre" hidden>
            <li {{ Auth::check() && Auth::user()->session == null ? 'hidden' : '' }} ><a href="./">SESSION</a></li>
            <li {{ Auth::check() && Auth::user()->session == null ? 'hidden' : '' }} ><a href="./">SCOREBOARD</a></li>
            <li {{ Auth::check() && Auth::user()->session != null ? 'hidden' : '' }} ><a href="./">REJOINDRE UNE SESSION</a></li>
            <li {{ Auth::check() && Auth::user()->session != null ? 'hidden' : '' }} ><a href="./">CRÉER UNE SESSION</a></li>
        </ul>
        <li {{ Auth::check() ? '' : 'hidden' }} ><a href="./">SHOP</a></li>
        <li><button onclick="hideshow('cytropfun', false)">CYTROPFUN</button></li>
        <ul class="nav-sub" id="cytropfun" hidden>
            <li><a href="./">OUIOUNON.NET</a></li>
        </ul>
        <li {{ Auth::check() ? '' : 'hidden' }} ><a href="./logout">DECONNEXION</a></li>
    </ul>
</header>