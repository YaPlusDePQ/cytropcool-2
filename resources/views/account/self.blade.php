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
                    <button onclick="document.getElementById('update').hidden = !document.getElementById('update').hidden">Modifier</button>
                </div>

                <div class="info-data">
                    <span> Pseudo </span>
                    <h1 id="name">{{Auth::user()->name;}}</h1>
                    <span> Morphologie </span>
                    <h2>{{Auth::user()->sexe == config('cytropcool.constant.male') ? "Homme" : "Femme"}} {{Auth::user()->weight;}}Kg</h2>
                    <span> Cramptés </span>
                    <h2>{{Auth::user()->crampte;}}</h2>
                </div>

                <div id="update" hidden>
                    <div class="section-title">
                        <h1>MISE À JOUR</h1>
                        <button onclick="document.getElementById('update').hidden = true">Annuler</button>
                    </div>
                    <form id="update-form" class="info-data">
                        
                        @csrf
                        <input name="_method" value="PUT" readonly required hidden/>

                        <div class="update-result" @if(!$errors->any()) {{'hidden'}} @endif>
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            @endif
                        </div>

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
                    <span class="badge" style="{{ $currentStyle->badge->css }}">{{  $currentStyle->badge->text }}</span>

                    <span class="pseudo" style="{{  $currentStyle->color->css.$currentStyle->font->css }}">{{ Auth::user()->name }}</span>
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

        <div id="style" hidden>
            <div class="section-title"  style="padding: 10px;background: #202020;color: #fff;">
                <h2>INVENTAIRE</h2>
                <button onclick="document.getElementById('style').hidden = true;
                    document.getElementById('home').hidden = false">Retour</button>
            </div>

            <div class="style-display">
                <span>Prévisualisation</span>
                <div class="display-wrapper">

                    <span id="preview-badge" class="badge"  style="{{ $currentStyle->badge->css }}">{{  $currentStyle->badge->text }}</span>

                    <span id="preview-pseudo" class="pseudo" style="{{  $currentStyle->color->css.$currentStyle->font->css }}">{{ Auth::user()->name }}</span>

                </div>

                <form id="style-form">

                    <div class="set-style-result" @if(!$errors->any()) {{'hidden'}} @endif>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        @endif
                    </div>
                    @csrf
                    <input name="_method" value="PATCH" readonly required hidden/>


                    <input type="number" name="badge" hidden required readonly>
                    <input type="number" name="color" hidden required readonly>
                    <input type="number" name="font" hidden required readonly>

                    <button type="submit" class="save-style" onclick="set_new_style()">Sauvegarder</button>
                </form>

            </div>

            <div class="elements-display">
                <div class="section-title">
                    <h2>BADGES</h2>
                </div>

                <div class="displayer">
                    <ul id="badge-displayer">
                        @foreach($styles as $style)
                            @if($style->type == "badge")
                        <li> 
                            <div class="displayer-element-content"> 
                                <span class="badge" style="{{ $style->data->css }}">{{$style->data->text == "" ? "Rien" : $style->data->text}}</span> 
                            </div>  
                            <div class="displayer-element-name">
                                <span>{{$style->name}}</span>
                            </div> 
                        </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="elements-display">
                <div class="section-title">
                    <h2>POLICES</h2>
                </div>

                <div class="displayer">
                    <ul id="font-displayer">
                        @foreach($styles as $style)
                            @if($style->type == "font")
                        <li> 
                            <div class="displayer-element-content"> 
                                <span class="pseudo" style="{{ $style->data->css }}">{{Auth::user()->name}}</span> 
                            </div>  
                            <div class="displayer-element-name">
                                <span>{{$style->name}}</span>
                            </div> 
                        </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="elements-display">
                <div class="section-title">
                    <h2>COULEURS</h2>
                </div>

                <div class="displayer">
                    <ul id="color-displayer">
                        @foreach($styles as $style)
                            @if($style->type == "color")
                        <li> 
                            <div class="displayer-element-content"> 
                                <span class="pseudo" style="{{ $style->data->css }}">{{Auth::user()->name}}</span> 
                            </div>  
                            <div class="displayer-element-name">
                                <span>{{$style->name}}</span>
                            </div> 
                        </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>