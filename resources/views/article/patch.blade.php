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
            <p>
                <b>Pour ce premier jour de WEI, comme l'année dernière, la grande mise à jour CYtropcool est arrivée avec son lot de surprises...</b>
            </p>
            <p>
                Premièrement, une refonte visuelle et technique du site a été réalisée avec pour objectif de rendre CYtropcool bien plus polyvalent et ergonomique pour l'avenir. <b>Les mots de passe ont d'ailleurs tous été réinitialisés, et un lien devrait être disponible dans votre boîte mail.</b>
            </p>
            <p>
                Pour cette seconde saison de CYtropIvre, <b>toutes les statistiques ont été réinitialisées à 0</b> (un récapitulatif des statistiques de l'année précédente arrivera prochainement, elles n'ont pas disparu ;) ). <b>Les récompenses pour les top 1, 2 et 3 ont été distribuées</b>, respectivement :
                <ul>
                    <li><b class='p1'>1er</b> : 100 cramptés + achievement</li>
                    <li><b class='p2'>2ème</b> : 50 cramptés</li>
                    <li><b class='p3'>3ème</b> : 25 cramptés</li>
                </ul>
                Des ajustements sur l'algorithme de calcul de l'alcoolémie ont été effectués afin de mieux refléter la réalité :
                <ul>
                    <li><b class='nerf'>NERF</b> : Dégradation de l'alcool <b class='nerf'>de 0,20/h à 0,30/h</b>.</li>
                    <li><b class='nerf'>NERF</b> : Début de la dégradation de l'alcool <b class='nerf'>de 30 minutes à 25 minutes</b>.</li>
                    <li><b class='buff'>BUFF</b> : Pic d'alcoolémie sans manger <b class='buff'>de 30 minutes à 25 minutes</b> après le verre.</li>
                </ul>
            </p>
            <p>
                Enfin, comme vous avez pu le remarquer plus haut, grâce au CY Style Engine 2, de tout nouveaux types de styles sont désormais disponibles !
                <ul>
                    <li><b>Achievements</b> : s'affichent à droite du pseudo et s'obtiennent grâce à des exploits</li>
                    <li><b>Animations</b> : permettent d'animer votre pseudo</li>
                </ul>
            </p>
            <p style='font-size:large;margin-top:20px;'>
                <b>Merci de participer à CYTropcool et bonnes quoicoucaisses !</b>
            </p>

        </div>

        <div class='article-leave'>
            <a href={{url('/')}}>Retour</a>
        </div>
    </div>

</body>
</html>

- Somme de tous tes taux d'alcoolémie
- Taux d'alcoolémie max en une session
- Somme de tout l'alcool que tu as bu
- Quantité max d'alcool bu en une session
- Somme de la quantité d'alcool pur bu
- Quantité max d'alcool pur bu en une session
- Nombre total de verres pris
- Nombre max de verres bu en une session
- Nombre total de shots pris
- Nombre max de shots bu en une session
