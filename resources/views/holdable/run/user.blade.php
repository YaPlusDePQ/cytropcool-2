@if( !isset($_HOLD_RUN) )
    <h1 style="font-weight:bold;color:red;">ERR: MISSING _HOLD_RUN VARIABLE. USE HoldableController::displayHold <h1>

@else

<div class=" _before_user{{$_HOLD_RUN->currentUser->id}}">
    @include('holdable.run.before')
</div>

<div class=" _name_user{{$_HOLD_RUN->currentUser->id}}">
    @include('holdable.run.name')
    <name class="{{$_HOLD_RUN->customClass}} _user{{$_HOLD_RUN->currentUser->id}}">{{ $_HOLD_RUN->currentUser->username }}</name>
</div>

<div class=" _after_user{{$_HOLD_RUN->currentUser->id}}">
    @include('holdable.run.after')
</div>

@endif


