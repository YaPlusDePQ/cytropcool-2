<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verre Rempli avec Deux Barres Verticales</title>
    <style>
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slider-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .slider-container label {
            margin-bottom: 10px;
            font-weight: bold;
        }

        .slider-container input[type="range"] {
            width: 150px;
            height: 8px;
            transform: rotate(-90deg);
            margin-bottom: 20px;
        }


        .glass-container {
            position: relative;
            width: 120px;
            height: 250px;
            border: 3px solid #ccc;
            border-top:none;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
            background-color: rgba(255, 255, 255, 0.7);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .liquid {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 50%;
            background-color: #4CAF50;
            opacity: 0.7;
        }

        #liquid2 {
            background-color: #2196F3;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="slider-container">
            <label for="fill-level1">Remplissage 1 (%)</label>
            <input type="range" id="fill-level1" min="0" max="100" value="50" oninput="updateFillLevel('liquid1', this.value)">
        </div>

        <div class="glass-container">
            <div class="liquid" id="liquid1"></div>
            <div class="liquid" id="liquid2"></div>
        </div>

        <div class="slider-container">
            <label for="fill-level2">Remplissage 2 (%)</label>
            <input type="range" id="fill-level2" min="0" max="100" value="25" oninput="updateFillLevel('liquid2', this.value)">
        </div>
    </div>

    <script>
        function updateFillLevel(liquidId, value) {
            const liquid = document.getElementById(liquidId);
            liquid.style.height = value + '%';
        }
    </script>
</body>
</html>
