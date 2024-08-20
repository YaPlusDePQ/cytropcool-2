<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/account/self.css')}}" rel='stylesheet'>
    <script src="{{ asset('/js/account/self.js')}}" rel='stylesheet'></script>
    <script>window.currentHold = {} </script>
    <title>CYtropcool - Profil</title>
</head>
<body>

    @include('header')

    <div class="content">
        <div id="home" @if(Session::has('hold-failed') || Session::has('hold-success')) {{'hidden'}} @endif>

            <div class="info">
                
                <div class="section-title">
                    <h1>PROFIL</h1>
                    <button onclick="document.getElementById('update').hidden = !document.getElementById('update').hidden">Modifier</button>
                </div>

                <div class="info-data">
                    <span class="title"> Pseudo </span>
                    <h1 id="name">{{Auth::user()->name;}}</h1>
                    <span class="title"> Morphologie </span>
                    <h2>{{Auth::user()->sexe == config('cytropcool.constant.male') ? "Homme" : "Femme"}} {{Auth::user()->weight;}}Kg</h2>
                    <span class="title"> Cramptés </span>

                    <h2>{{Auth::user()->crampte;}}</h2>
                    <div class="failed-msg" @if(!Session::has('update-failed')) {{'hidden'}} @endif>
                        @if (Session::has('update-failed'))
                            {{ Session::get('update-failed') }}
                        @endif
                    </div>
                    <div class="success-msg" @if(!Session::has('update-success')) {{'hidden'}} @endif>
                        @if (Session::has('update-success'))
                        {{ Session::get('update-success') }}
                        @endif
                    </div>
                </div>

                <div id="update" @if(!Session::has('update-failed')) {{'hidden'}} @endif>
                    <div class="section-title">
                        <h1>MISE À JOUR</h1>
                        <button onclick="document.getElementById('update').hidden = true">Annuler</button>
                    </div>
                    <form id="update-form" class="info-data" method="POST">
                        
                        @csrf
                        <input name="_method" value="PUT" readonly required hidden/>

                        

                        <span> Pseudo </span>
                        <input type="text" name="name" value="{{old('email') ?? Auth::user()->name;}}" required>

                        <span> Poids </span>
                        <input type="number" name="weight" value="{{old('weight') ?? Auth::user()->weight;}}" required>

                        <span> Sexe </span>
                        <select type="number" name="sexe" value="{{ old('sexe') ??  Auth::user()->sexe;}}" required>
                            <option value="{{config('cytropcool.constant.male')}}" {{Auth::user()->sexe == config('cytropcool.constant.male') ? 'selected' : ''}}>Homme</option>
                            <option value="{{config('cytropcool.constant.female')}}" {{Auth::user()->sexe == config('cytropcool.constant.female') ? 'selected' : ''}}>Femme</option>
                        </select>

                        <button type="submit" class="update-action">Mettre à jour</button>
            
                    </form>

                </div>
            </div>

            <div class="current-style">
                <div class="section-title">
                    <h2>STYLE</h2>
                    <button onclick="document.getElementById('style').hidden = false;
                    document.getElementById('home').hidden = true">Voir plus</button>
                </div>

                <div class="display-wrapper">
                    @include('holdable.run.user')
                </div>
                

            </div>

            <div class="last-session">
                <div class="section-title">
                    <h2>DERNIÈRE SESSION</h2>
                    <button onclick="document.getElementById('historic').hidden = false;
                    document.getElementById('home').hidden = true">Voir plus</button>
                </div>
                <div id="last-session">
                    
                    @if($history != [])
                        
                    <div class="session-wrapper">

                        <div class="session-title">
                            <span>{{ $history[0]->name}}</span>
                            <button>Statistiques</button>
                        </div>

                        <div class="session-stat">
                            <li>
                                <span class="stat-value"><span>{{ number_format( $history[0]->max_gl, 1) }}</span> g/L</span>
                                <span class="stat-description">Alcoolémie max atteinte</span>
                            </li>

                            <li>
                                <span class="stat-value"><span>{{ number_format( $history[0]->alcool_quantity, 1) }}</span> L</span>
                                <span class="stat-description">d'alcool bu</span>
                            </li>

                            <li>
                                <span class="stat-value"><span>{{ number_format( $history[0]->alcool_quantity, 1) }}</span> L</span>
                                <span class="stat-description">d'alcool pur bu</span>
                            </li>

                            <li>
                                <span class="stat-value"><span>{{ $history[0]->shot }}</span></span>
                                <span class="stat-description">Shot(s)</span>
                            </li>

                            <li>
                                <span class="stat-value"><span>{{ $history[0]->glass }}</span></span>
                                <span class="stat-description">Verre(s)</span>
                            </li>

                        </div>

                    </div>
                    @else
                        T'as fait aucune quoicousoirée
                    @endif
                </div>
            </div>

            <hr class="separator">

            <div class="stats-showcase">

                <div class="section-title">
                    <h2>STATISTIQUES</h2>
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
    
        <div id="historic" hidden>
            <div class="section-title">
                <h2>HISTORIQUE DES SESSIONS</h2>
                <button onclick="document.getElementById('historic').hidden = true;
                    document.getElementById('home').hidden = false">Retour</button>
            </div>

            <div id="historic-viewer">
                @if($history != [])
                    @foreach($history as $session)
                        <div class="session-wrapper">

                            <div class="session-title">
                                <span>{{ $session->name}}</span>
                                <button onclick="this.parentNode.parentNode.children[1].hidden = !this.parentNode.parentNode.children[1].hidden" >Statistiques</button>
                            </div>

                            <div class="session-stat" hidden>
                                <li>
                                    <span class="stat-value"><span>{{ number_format( $session->max_gl, 1) }}</span> g/L</span>
                                    <span class="stat-description">Alcoolémie max atteinte</span>
                                </li>

                                <li>
                                    <span class="stat-value"><span>{{ number_format( $session->alcool_quantity, 1) }}</span> L</span>
                                    <span class="stat-description">d'alcool bu</span>
                                </li>

                                <li>
                                    <span class="stat-value"><span>{{ number_format( $session->alcool_quantity, 1) }}</span> L</span>
                                    <span class="stat-description">d'alcool pur bu</span>
                                </li>

                                <li>
                                    <span class="stat-value"><span>{{ $session->shot }}</span></span>
                                    <span class="stat-description">Shot(s)</span>
                                </li>

                                <li>
                                    <span class="stat-value"><span>{{ $session->glass }}</span></span>
                                    <span class="stat-description">Verre(s)</span>
                                </li>
                                </div>

                            </div>

                        </div>
                    @endforeach
                @else
                    T'as fait aucune quoicousoirée
                @endif
            </div>
        </div>

        <div id="style" @if(!Session::has('hold-failed') && !Session::has('hold-success')) {{'hidden'}} @endif>
            <div class="section-title"  style="padding: 10px;background: #202020;color: #fff;">
                <h2>INVENTAIRE</h2>
                <button onclick="document.getElementById('style').hidden = true;
                    document.getElementById('home').hidden = false">Retour</button>
            </div>

            <div class="style-display">
                <span>Prévisualisation</span>
                <div class="display-wrapper" >
                    <div id="preview">
                        @include('holdable.run.user')
                    </div>
                    <img id='load-preview' src="{{ asset('./img/loading.gif') }}" style='width:31px;' hidden>
                </div>

                <form id="save-holdable-form" method="POST">

                    <div class="failed-msg" @if(!Session::has('hold-failed')) {{'hidden'}} @endif>
                        @if (Session::has('hold-failed'))
                            {{ Session::get('hold-failed') }}
                        @endif
                    </div>
                    <div class="success-msg" @if(!Session::has('hold-success')) {{'hidden'}} @endif>
                        @if (Session::has('hold-success'))
                        {{ Session::get('hold-success') }}
                        @endif
                    </div>

                    @csrf
                    <input name="_method" value="POST" readonly required hidden/>

                    <select name="saveHolds[]" multiple hidden>
                    @foreach($inventory as $cat => $data)
                        @foreach($data as $item)
                            <option type="number" id="save-hold-{{$item->id}}" category="{{$item->category}}" value="{{$item->id}}" @if($item->hold) selected @endif>
                        @endforeach
                    @endforeach
                    </select>

                    <button type="submit" class="save-style">Sauvegarder</button>
                </form>

            </div>

            @foreach($inventory as $cat => $holds)
            <div class="elements-display">
                <div class="section-title">
                    <h2>{{ strtoupper($cat) }}</h2>
                </div>

                <div class="displayer">
                    <ul>
                        @foreach($holds as $h)
                        <li onclick='onClickHoldElement({{$h->id}}, "{{$h->category}}")'>
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