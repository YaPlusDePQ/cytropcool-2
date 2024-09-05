<?php use App\Http\Controllers\CytropivreController; ?>

<header  style="margin-bottom: 0px;">
    <link rel="stylesheet" href="{{ asset('/css/header.css') }}">
    <script src="{{ asset('/js/header.js') }}"></script>

    <div class="menu">
        <button id="show-hide-nav" onclick="hideshow('nav', false);hideshow('cytropivre', true)"><img src="{{ asset('./img/56763.png') }}"></button>
        <h1>CYTROPCOOL</h1>
    </div>
        
    <ul class="nav" id="nav" hidden>
        <li><a href="{{url('/')}}">HOME</a></li>
        <li {{ Auth::check() ? 'hidden' : '' }} ><a href="{{url('/login')}}">CONNEXION</a></li>
        <li {{ Auth::check() ? 'hidden' : '' }} ><a href="{{url('/signin')}}">S'INSCRIRE</a></li>
        <li {{ Auth::check() ? '' : 'hidden' }} ><button onclick="hideshow('account', false)">COMPTE</button></li>
        <ul class="nav-sub" id="account" hidden>
            <li><a href="{{url('/profile')}}">PROFIL</a></li>
            <li><a href="{{url('/friends')}}">AMIS</a></li>
        </ul>
        <li {{ Auth::check() ? '' : 'hidden' }} ><button onclick="hideshow('cytropivre', false)">CYTROPIVRE</button></li>
        <ul class="nav-sub" id="cytropivre" hidden>
            <li {{ Auth::check() && CytropivreController::getSession(Auth::user()->id) == null ? 'hidden' : '' }} ><a href="{{url('/cytropivre/session')}}">SESSION</a></li>
            <li {{ Auth::check() && CytropivreController::getSession(Auth::user()->id) == null ? 'hidden' : '' }} ><a href="{{url('/cytropivre/scoreboard')}}">SCOREBOARD</a></li>
            <li {{ Auth::check() && CytropivreController::getSession(Auth::user()->id) != null ? 'hidden' : '' }} ><a href="{{url('/cytropivre/search')}}">REJOINDRE UNE SESSION</a></li>
            <li {{ Auth::check() && CytropivreController::getSession(Auth::user()->id) != null ? 'hidden' : '' }} ><a href="{{url('/cytropivre/create')}}">CRÉER UNE SESSION</a></li>
        </ul>
        <li {{ Auth::check() ? '' : 'hidden' }} ><a href="{{url('/shop')}}">SHOP</a></li>
        <li><button onclick="hideshow('cytropfun', false)">CYTROPFUN</button></li>
        <ul class="nav-sub" id="cytropfun" hidden>
            <li><a href="{{url('/cytropfun/ouiounon')}}">OUIOUNON.NET</a></li>
        </ul>
        <li {{ Auth::check() ? '' : 'hidden' }} ><a href="{{url('/logout')}}">DECONNEXION</a></li>
    </ul>
</header>