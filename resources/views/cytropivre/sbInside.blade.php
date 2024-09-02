@if(isset($_CYTI_GL) && count($_CYTI_GL) > 0)
    @foreach($_CYTI_GL as $gl)
        <div class="wrapper">
            @include('holdable.run.user')
            <span class="{{$gl < 1 ? 'low' : ($gl < 2 ? 'medium' :($gl < 3 ? 'high' : 'very-high'))}}">{{$gl}} g/L</span>
        </div>
        @include('holdable.run.increment')
    @endforeach

    <div>
        <span class="time">scoreboard à {{$_CYTI_TIME}}</span>
    </div>
@endif