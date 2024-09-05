<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0-rc.4/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0-rc.4/dist/js/tom-select.complete.min.js"></script>

    <link href="{{ asset('/css/cytropivre/session.css')}}" rel='stylesheet'>
    <script src="{{ asset('/js/cytropivre/session.js')}}" rel='stylesheet'></script>

    <title>CYtropcool - Session</title>
</head>
<body>
    
    @include('header')

    <div class="session-title">
            
        <h1>{{$session->name}}</h1>
    
        <h2> Code d'accès: {{$session->id}} <button class='share-button' onclick="navigator.clipboard.writeText('{{url('/cytropivre/join/'.$session->id)}}');alert('Lien de participation copié !');"><img class="share" src={{ asset('./img/share.png') }}></button></h2>

        <form method='POST'>
            @csrf <!-- {{ csrf_field() }} -->
            <input name="_method" value="DELETE" readonly required hidden/>
            <input name="delete_session" value='delete_session' readonly required hidden/>

            <button type="submit" class='session-action' onclick="return confirm('{{ $session->admin == Auth::user()->id ? 'Terminer la session ?' : 'Quitter la session ?'}}')">{{ $session->admin == Auth::user()->id ? 'Terminer la session' : 'Quitter la session'}}</button>
        </form>

    </div>

    <div class="home" id="home"  @if(Session::has('add-success') || Session::has('add-failed') || Session::has('update-success') || Session::has('update-failed') ) {{'hidden'}} @endif>

        <div class="info">

            
            <div class="section-title">
                <h1>PROFIL</h1>
                <a href={{url('/profile')}}><button>Modifier</button></a>
            </div>

            <div class="info-data">
                <span class="title"> Pseudo </span>
                <h1 class="display-wrapper" >@include('holdable.run.user')</h1>
                <span class="title"> Morphologie </span>
                <h2>{{Auth::user()->sexe == config('cytropcool.constant.male') ? "Homme" : "Femme"}} {{Auth::user()->weight;}}Kg</h2>
                <span class="title"> Repas avant le premier verre ? (3h max)</span>
                
                <form class='eat' method='POST'>
                    @csrf <!-- {{ csrf_field() }} -->
                    <input name="_method" value="PATCH" readonly required hidden/>
                    <select onchange='this.form.submit()' name='eat'>
                    @if($eat)
                        <option value=1 selected>Oui</option>
                        <option value=0>Non</option>
                    @else
                        <option value=0 selected>Non</option>
                        <option value=1>Oui</option>
                    @endif

                    </select>

                </form>

                
            </div>
        </div>

        <div class="stats" id="stat-bar">

            <div class="section-title">
                <h1>ALCOOLÉMIE</h1>
            </div>

            <div class="bar-wrapper">
                <div class="barOverflow">
                    <div class="bar" style="background:linear-gradient(white, white) padding-box, linear-gradient({{ $rate->max_rate == 0 ? 0:($rate->rate/$rate->max_rate)*180 }}deg, #123456 12.5%, #2494b6 50%, #bbb 50%) border-box;"></div>
                </div>
                <div class="in-bar">
                    <span class="current" id="current_rate">{{$rate->rate}}g/L</span>
                    <span class="max" id="max_rate">Maximum: {{$rate->max_rate}}g/L à {{$rate->max_rate_time}}</span>
                </div>
                
            </div>
        </div>

        <div class="drink-action">
            <button onclick="showHide('add_drink', 'home')">Nouvelle boisson</button>
            <button onclick="showHide('manage_drink', 'home')">Gérer les boissons</button>
        </div>

    </div>

    <div class="add_drink" id="add_drink" @if(!(Session::has('add-success') || Session::has('add-failed'))) {{'hidden'}} @endif>
        <div class="section-title" style="margin:10px">
            <h1>NOUVELLE BOISSON</h1>
            <button onclick="showHide('home', 'add_drink')">Retour</button>
        </div>

        <div id="shortcut" style="margin:10px">
            <div class="sh_wrapper">
                <button onclick="ShortCut(25, 'Vodka', 35, 33, 1, 0);changeHideClass('on_custom', true);changeActive('sh', this)" class="sh pushed">Verre de 25cl 1/3 de Vodka</button>
                <button onclick="ShortCut(2, 'Vodka', 35, 50, 1, 1);changeHideClass('on_custom', true);changeActive('sh', this)"  class="sh">Shot BDE</button>
                <button onclick="ShortCut(4, 'Tequila', 35, 100, 1, 1);changeHideClass('on_custom', true);changeActive('sh', this)"  class="sh">Tek Paf</button>
                <button onclick="ShortCut(25,'Bière', 6, 100, 1, 0);changeHideClass('on_custom', true);changeActive('sh', this)"  class="sh">Bière 25cl</button>
                <button onclick="ShortCut(50, 'Bière', 6, 100, 1, 0);changeHideClass('on_custom', true);changeActive('sh', this)"  class="sh">Bière 50cl</button>
                
            </div>

            <div class="sh_wrapper">
                <button onclick="ShortCut(25, 'Vodka', 35, 33, 1, 0);changeHideClass('on_custom');changeActive('sh', this)"  class="sh">Personnalisé</button> 
            </div>
        </div>



        <form class="drink_form" method='POST'> 
            <hr>


            @csrf <!-- {{ csrf_field() }} -->
            <input name="_method" value="POST" readonly required hidden/>

            <div class="drink_input on_custom" hidden>
                <span>Taille (cl) :</span> 
                <input class="width-lock" type="number" id="size" name="size" value="33"> 
            </div>

            
            <div class="drink_input on_custom" hidden>
                <span>Alcool :</span>
                <select class="width-lock" id="alcool" name="alcool" onchange="setAlcool(this.value, 'alcool_degre', 'fillAlcool-input')">
                    <option value="Vodka">Vodka</option>
                    <option value="Bière">Bière</option>
                    <option value="Tequila">Tequila</option>
                    <option value="Rhum">Rhum</option>
                    <option value="Jäger">Jäger</option>
                    <option value="Whisky">Whisky</option>
                    <option value="Ricard">Ricard</option>
                    <option value="Jet 27">Jet 27</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>

            <div class="drink_input on_custom" hidden>
                <span>Degre :</span> 
                <input class="width-lock" type="number" id="alcool_degre" name="alcool_degre" value="35">
            </div>

            <div class="drink_input custom-form-container on_custom" hidden>

                <div class="glass-container">
                    <div class="liquid" id="liquid1" style="height:90%"></div>
                    <div class="liquid" id="fillAlcool" style="height:29.7%"></div>
                </div>

                <div class="slider-container">
                    <input type="range" id="alcool_quantity" step="any" min="0.0" max="90.0" oninput="updateFillLevel('fillAlcool', this.value)">
                    <label for="alcool_quantity" id="fillAlcool-label">
                        <input type="number" min=0 max=100 id="fillAlcool-input" onchange="manualUpdateFillLevel('fillAlcool',this.value)" value=33 name="alcool_quantity">% d'alcool
                    </label>
                </div>

            </div>


            <div class="drink_input">
                <span>Nombre(s) :</span> 
                <input class="width-lock" type="number" id="number" name="number" value="1">
            </div>

            <div class="drink_input">
                <span>Cul sec ?</span>
                <select class="width-lock" id="bottoms_up" name="bottoms_up">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                </select>
                
            </div>

            <div class="drink_input">
                <span>Verre servie a</span> 
                <input class="width-lock" type="datetime-local" id="drink_at" name="drink_at" value="{{$date}}">
            </div>
    
            <div class="add_drink_submit">
                <button type="submit">Ajouter</button>
            </div>

            <div  class="failed-msg" @if(!Session::has('add-failed')) {{'hidden'}} @endif>
                @if (Session::has('add-failed'))
                    {{ Session::get('add-failed') }}
                @endif
            </div>
            <div class="success-msg" @if(!Session::has('add-success')) {{'hidden'}} @endif>
                @if (Session::has('add-success'))
                    {{ Session::get('add-success') }}
                @endif
            </div>
        </form> 
            
    </div>

    <div class="manage_drink" id="manage_drink" @if(!(Session::has('update-success') || Session::has('update-failed'))) {{'hidden'}} @endif>
        <div class="section-title" style="margin:10px">
            <h1>MES BOISSONS</h1>
            <button onclick="showHide('home', 'manage_drink')">Retour</button>
            <div  class="failed-msg" @if(!Session::has('update-failed')) {{'hidden'}} @endif>
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

        <div class="drinks_display">
            @foreach($drinks as $drink)
                <div class="drink_wrapper">
                    <span>{{$drink->name}}</span>
                    <button onclick='setUpdateForm( 
                        "{{$drink->name}}", 
                        "{{$drink->id}}", 
                        "{{$drink->size}}", 
                        "{{$drink->alcool}}", 
                        "{{$drink->alcool_degre}}", 
                        "{{$drink->alcool_quantity}}", 
                        "{{$drink->number}}", 
                        "{{$drink->bottoms_up}}", 
                        "{{$drink->drink_at}}", );
                        document.getElementById("updateForm").classList.add("active");'>Modifier</button>
                </div>
            @endforeach
        </div>

        <div id="updateForm" class='updateForm'>
            <div class="modal">
                <div class='updateFormName'>
                    <h3 id='updateName'></h3>
                    <button class="close" onclick="document.getElementById('updateForm').classList.remove('active');">&times;</button>
                </div>

                <form class="" method='POST'> 

                    @csrf <!-- {{ csrf_field() }} -->
                    <input name="_method" id="updateMethod" value="PUT" readonly required hidden/>
                    <input class="width-lock" type="number" id="updateId" name="id" readonly required hidden> 

                    <div class="drink_input">
                        <span>Taille (cl) :</span> 
                        <input class="width-lock" type="number" id="updateSize" name="size"> 
                    </div>


                    <div class="drink_input">
                        <span>Alcool :</span>
                        <select class="width-lock" id="updateAlcool" name="alcool" onchange="setAlcool(this.value, 'updateAlcoolDegre', 'updateAlcoolQuantity')">
                            <option value="Vodka">Vodka</option>
                            <option value="Bière">Bière</option>
                            <option value="Tequila">Tequila</option>
                            <option value="Rhum">Rhum</option>
                            <option value="Jäger">Jäger</option>
                            <option value="Whisky">Whisky</option>
                            <option value="Ricard">Ricard</option>
                            <option value="Jet 27">Jet 27</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div class="drink_input" >
                        <span>Degré :</span> 
                        <input class="width-lock" type="number" id="updateAlcoolDegre" name="alcool_degre">
                    </div>

                    <div class="drink_input">
                        <span>Quantité d'alcool (%) :</span> 
                        <input lass="width-lock" type="number" min=0 max=100 id="updateAlcoolQuantity" name="alcool_quantity">
                    </div>

                    <div class="drink_input">
                        <span>Nombre(s) :</span> <input class="width-lock" type="number" id="updateNumber" name="number">
                    </div>

                    <div class="drink_input">
                        <span>Cul sec ?</span>
                        <select class="width-lock" id="updateBottomsUp" name="bottoms_up">
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                                    
                    </div>

                    <div class="drink_input">
                        <span>Verre servie a</span> 
                        <input class="width-lock" type="datetime-local" id="updateDrinkAt" name="drink_at">
                    </div>

                    <div class="update_drink_submit">
                        <button type="submit" class="edit">Modifier</button>
                        <button type="submit" class="delete" onclick="return confirm('Supprimer la boisson ? Attention cette action est définitive.') ? document.getElementById('updateMethod').value = 'DELETE':false;" >Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
            
        
    </div>

</body>
<script>
    window.addAlcool = new TomSelect("#alcool",{
        create: true,
        sortField: {
            field: "text",
            direction: "desc"
        }
    });

    window.updateAlcool = new TomSelect("#updateAlcool",{
        create: true,
        sortField: {
            field: "text",
            direction: "desc"
        }
    });
    manualUpdateFillLevel('fillAlcool',33);
    ShortCut(25, 'Vodka', 35, 33, 1, 0);
</script>
</html>