<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Animation Arc-en-Ciel</title>
<style>
/* Style de base pour le texte */
.typewriter {
    color: #ffd700;
    font-family: 'Courier New', Courier, monospace;
    font-size: 36px;
    border-right: 2px solid #ffd700;
    white-space: nowrap;
    overflow: hidden;
    width: 0; /* Commence avec une largeur nulle */
    animation: typing 4s steps(40, end) 1s infinite, blink 0.75s step-end infinite;
}

@keyframes typing {
    0% {
        width: 0;
    }
    50% {
        width: 100%; /* Affiche tout le texte */
    }
    90% {
        width: 100%; /* Maintient le texte visible un moment */
    }
    100% {
        width: 0; /* Cache le texte pour recommencer */
    }
}

@keyframes blink {
    50% {
        border-color: transparent;
    }
}

/* Définition de l'animation */

</style>
</head>
<body>

<!-- Texte animé avec les couleurs de l'arc-en-ciel -->
<div class="typewriter">Arc-en-Ciel</div>

</body>
</html>