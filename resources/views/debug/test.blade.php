<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Animation Arc-en-Ciel</title>
<style>
/* Style de base pour le texte */
.rainbow-text {            
    font-size: 48px;
    font-weight: bold;
    text-align: center;
    color:red;
    margin-top: 20%;
    animation: strobe 0.1s infinite alternate;
}

@keyframes strobe {
    0% { color: default; } 
    100% {  
        -webkit-filter: invert(100%);
        filter: invert(100%); 
    }
}

{}

/* Définition de l'animation */

</style>
</head>
<body>

<!-- Texte animé avec les couleurs de l'arc-en-ciel -->
<div class="rainbow-text">Arc-en-Ciel 🌈</div>

</body>
</html>