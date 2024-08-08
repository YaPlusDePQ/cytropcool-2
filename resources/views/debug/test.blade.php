<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enchanted Text Effect</title>
    <style>
        @keyframes enchantment {
    0% {
        color: #ff00ff;
        text-shadow: 
            0 0 10px #ff00ff, 
            0 0 20px #ff00ff, 
            0 0 30px #ff00ff, 
            0 0 40px #ff00ff, 
            0 0 50px #ff00ff, 
            0 0 60px #ff00ff;
        transform: translateY(0) skewX(0deg);
    }
    25% {
        color: #00ffff;
        text-shadow: 
            0 0 10px #00ffff, 
            0 0 20px #00ffff, 
            0 0 30px #00ffff, 
            0 0 40px #00ffff, 
            0 0 50px #00ffff, 
            0 0 60px #00ffff;
        transform: translateY(-5px) skewX(10deg);
    }
    50% {
        color: #ff00ff;
        text-shadow: 
            0 0 10px #ff00ff, 
            0 0 20px #ff00ff, 
            0 0 30px #ff00ff, 
            0 0 40px #ff00ff, 
            0 0 50px #ff00ff, 
            0 0 60px #ff00ff;
        transform: translateY(0) skewX(0deg);
    }
    75% {
        color: #00ffff;
        text-shadow: 
            0 0 10px #00ffff, 
            0 0 20px #00ffff, 
            0 0 30px #00ffff, 
            0 0 40px #00ffff, 
            0 0 50px #00ffff, 
            0 0 60px #00ffff;
        transform: translateY(5px) skewX(-10deg);
    }
    100% {
        color: #ff00ff;
        text-shadow: 
            0 0 10px #ff00ff, 
            0 0 20px #ff00ff, 
            0 0 30px #ff00ff, 
            0 0 40px #ff00ff, 
            0 0 50px #ff00ff, 
            0 0 60px #ff00ff;
        transform: translateY(0) skewX(0deg);
    }
}

        body, html {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1a1a1a;
            font-family: 'Minecraft', sans-serif;
        }

        .container {
            text-align: center;
        }

        .enchanted-text {
            font-size: 48px;
            color: #ffffff;
            position: relative;
            display: inline-block;
            overflow: hidden;
            animation: enchantment 3s infinite;
        }
    </style>
</head>
<body>
    <!-- <div class="container">
        <div class="enchanted-text">Minecraft Enchantment</div>
    </div> -->

    @include('holdable.run.user')
    @include('holdable.run.increment')
    @include('holdable.run.increment')
    @include('holdable.run.increment')
    @include('holdable.run.increment')
    @include('holdable.run.increment')
    @include('holdable.run.user')

</body>
</html>
