<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/account/self.css')}}" rel='stylesheet'>
    <title>CYtropcool - Profil</title>
</head>
<body>

    @include('header')

    <div class="content">
        <div id="home">

            <div class="info">
                
                <div class="section-title">
                    <h1>PROFIL</h1>
                </div>

                <div class="info-data">
                    <div style="display: flex;align-items: center;margin-top: 5px;">
                        @include('holdable.run.user')
                    </div>
                    <span  class="title"> Cramptés </span>

                    <h2>{{$user->crampte;}}</h2>

                    <span  class="title"> Valeur du profil </span>

                    <h2>{{$inventoryCost}}</h2>
                </div>
               
            </div>

            <div class="stats-showcase" style="padding-top:10px">

                <div class="section-title">
                    <h1>STATISTIQUES</h1>
                </div>

                <div class="stats-showcase-display">

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ number_format( $stats->sum_max_gl, 1) }} g/L</span>
                        <span class="stat-description">Somme de tous tes taux d'alcoolémie</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->sum_max_gl }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ number_format( $stats->max_gl, 1) }} g/L</span>
                        <span class="stat-description">Taux d'alcoolémie max en une session</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->max_gl }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ number_format( $stats->sum_alcool_quantity, 1) }} L</span>
                        <span class="stat-description">Somme de tout l'alcool que tu as bu</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->sum_alcool_quantity }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ number_format( $stats->max_alcool_quantity, 1) }} L</span>
                        <span class="stat-description">Quantité max d'alcool bu en une session</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->max_alcool_quantity }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ number_format( $stats->sum_pure_alcool_quantity, 1) }} L</span>
                        <span class="stat-description">Somme de la quantité d'alcool pur bu</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->sum_pure_alcool_quantity }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ number_format( $stats->max_pure_alcool_quantity, 1) }} L</span>
                        <span class="stat-description">Quantité max d'alcool pur bu en une session</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->max_pure_alcool_quantity }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ $stats->max_glass }}</span>
                        <span class="stat-description">Nombre total de verres pris</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->max_glass }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ $stats->sum_glass }}</span>
                        <span class="stat-description">Nombre max de verres bu en une session</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->sum_glass }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ $stats->sum_shot }}</span>
                        <span class="stat-description">Nombre total de shots pris</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->sum_shot }}</span>
                    </div>

                    <div class="stat-wrapper">
                        <span class="stat-value">{{ $stats->max_shot }}</span>
                        <span class="stat-description">Nombre max de shots bu en une session</span>
                        <span class="stat-rank">Classement: Top {{ $ranks->max_shot }}</span>
                    </div>

                </div>
            </div>

        </div>

        <div id="style" style="background: #202020;color: #fff;">
            <div class="section-title"  style="padding: 10px;">
                <h1>INVENTAIRE</h1>
            </div>

            @foreach($inventory as $cat => $holds)
            <hr style="margin-left:30px;margin-right:30px;border-radius:25px">

            <div class="elements-display" style="border:none;">
                <div class="section-title">
                    <h2>{{ strtoupper($cat) }}</h2>
                </div>

                <div class="public-displayer">
                    <ul>
                    @foreach($holds as $h)
                        <li>
                            <div class="displayer-element-content"> 
                                @include('holdable.inventory.show', ['_item' => $h])
                            </div>
                            <p>{{$h->name}}</p>
                        </li>
                    @endforeach
                    </ul>
                </div>

            </div>
            @endforeach

    
        </div>
    </div>
</body>
</html>