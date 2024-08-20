@if( !isset($_HOLD_RUN) )
    <h1 style="font-weight:bold;color:red;">ERR: MISSING _HOLD_RUN VARIABLE. USE HoldableController::displayHold <h1>

@else
<div style='display:flex;flex-direction:row;'>

    <div class=" _before{{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}" style='margin:auto'>
        @include('holdable.run.before')
    </div>

    <div class=" _name{{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}" style='margin:auto;margin-right:5px;margin-left:5px;'>
        @include('holdable.run.name')
        <name class="{{$_HOLD_RUN->customClass}} {{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}">{{ $_HOLD_RUN->currentUser->username }}</name>
    </div>

    <div class=" _after{{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}" style='margin:auto'>
        @include('holdable.run.after')
    </div>

</div>

@endif
@if( isset($debug) )
    {{dd($debug)}}

@endif

