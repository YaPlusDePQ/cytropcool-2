<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/home.css')}}" rel='stylesheet'>
    <title>CYtropcool - Home</title>
</head>
<body>
    @include('header')

    <div class='welcome'>
        <h1>Bienvenue sur <mark class='blue'>le site trop cool </mark>de  <mark class='blue'>CY Tech</mark>*</h1>
        <span>* Ce site n'est pas affilié à CY Tech ou CYU</span></br>
        <span>** La consommation d'alcool est dangereuse pour la santé</span></br>
        <span>*** Si vous avez soif, demandez au BDE</span>
        <p>Ici, on est là pour se mettre des énormes quoicoucaisses et avoir un max de cramptés.<b> Pas de place pour les quoicoubebous !</b></p>
        <p>Tu ne sais pas par où commencer ?</br> <b><a href='{{url('/signin')}}'> Va déjà t'inscrire ici ! </a></b></p>
    </div>

    <div class='article-display'>
        <h2 class='section-title'>Actualités</h2>
        <hr>
        @foreach($articles as $article)
            <div class='article' onclick="window.location = '{{url('/article/'.$article->smug)}}'">
                <h2>{{$article->title}}</h2>
                <hr>
                <p>{{$article->description}}</p>
                <a href={{url('/article/'.$article->smug)}}>Lire plus ➜</a>
            </div>
        @endforeach
    </div>

    
</body>
</html>