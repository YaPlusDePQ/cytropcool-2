<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/shop.css')}}" rel='stylesheet'>
    <script src="{{ asset('/js/shop.js')}}"></script>
    <title>CYtropcool - Shop</title>
</head>
<body>
    @include('header')

    <div class="main-title">
        <h1>QUOICOUSHOP</h1>
        <p>Bienvenue dans le Quoicoushop, ici tu peux acheter des goodies pour rendre ton pseudo plus beau pendant que tu te mets une énorme murge.</p>
        <div class="main-title-crampte">
            <span> Cramptés ©</span>
            <h2>{{Auth::user()->crampte}}</h2>
        </div>
        <div  class="failed" @if(!$errors->any()) {{'hidden'}} @endif>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            @endif
        </div>
        <div class="success" @if(!Session::has('success')) {{'hidden'}} @endif>
            @if (Session::has('success'))
                {{ Session::get('success') }}
            @endif
        </div>
    </div>

    <div class="shop">
        @if(count((array)$shop) == 0)
            <p>Désolé, tu possèdes absolument tout dans le Quoicoushop :(</p>
        @else
        @foreach($shop as $cat => $holds)
        <hr class="separator">

        <div class="elements-display">
            <div class="section-title">
                <h2>{{ strtoupper($cat) }}</h2>
            </div>

            <div class="displayer">
                <ul>
                @foreach($holds as $h)
                    <li onclick='setBuyForm(this, "{{$h->name}}", {{$h->price}}, {{$h->id}});document.getElementById("buy-form").classList.add("active");'>
                        <div class="displayer-element-content"> 
                            @include('holdable.inventory.show', ['_item' => $h])
                        </div>
                        <p>{{$h->name}}</p>
                        <button class='buy'>{{$h->price}} ©</button>
                    </li>
                @endforeach
                </ul>
            </div>

        </div>
        @endforeach
        @endif
    </div>

    <div id="buy-form" class='buy-form'>
        <div class="modal">
            <div class='buy-form-title'>
                <h3 id='hold-name'></h3>
            </div>

            <form method='POST'> 

                @csrf <!-- {{ csrf_field() }} -->
                <input class="width-lock" type="number" id="hold-id" name="id" readonly required hidden> 

                <div id="form-displayer" class="displayer-element-content"></div>

                <div class="buy-form-submit">
                    <button type="submit" class="buy" id='hold-buy-button'></button>
                    <button class="cancel" onclick="document.getElementById('buy-form').classList.remove('active');return false;" >Annuler</button>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>