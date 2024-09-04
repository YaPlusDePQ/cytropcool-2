<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/article/'.$article->smug.'.css')}}" rel='stylesheet'>
    <link href="{{ asset('/css/article/default.css')}}" rel='stylesheet'>
    <script src="{{ asset('/js/article/'.$article->smug.'.js')}}"></script>
    <title>CYtropcool - {{$article->title}}</title>
</head>
<body>
    @include('header')

    <div class='article-wrapper'>
        <div class='article-title'>
            <h1>{{$article->title}}</h1>
        </div>

        <div class='article-content'>
            
        </div>
    </div>

    

</body>
</html>