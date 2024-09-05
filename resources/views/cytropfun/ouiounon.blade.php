<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/cytropfun/ouiounon.css')}}" rel='stylesheet'>
    <script src="{{ asset('/js/cytropfun/ouiounon.js')}}"></script>
    <title>CYtropcool - Oui ou non</title>
</head>
<body>
    @include('header')
    <div class="ask">
        <span>Ta question: </span>
        <input id="ask-input" onclick="this.value=''" value="est-ce que je dois boire ?">
        <button onclick="answer()">Oui ou non ??????</button>
    </div>

    <div class="result" id="result">
        
    </div>
</body>
</html>