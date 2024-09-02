<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/cytropivre/scoreboard.css')}}" rel='stylesheet'>
    <script src="{{ asset('/js/cytropivre/scoreboard.js')}}"></script>
    <title>CYtropcool - Scoreboard</title>
</head>
<body>
    
    @include('header')

    <div id="space">
        @include('cytropivre.sbInside')
    </div>

</body>
</html>