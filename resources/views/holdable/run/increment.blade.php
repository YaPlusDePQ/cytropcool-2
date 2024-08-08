@if( !isset($_HOLD_RUN) )
    <h1 style="font-weight:bold;color:red;">ERR: MISSING _HOLD_RUN VARIABLE. USE HoldableController::displayHold <h1>

@else

@php($_HOLD_RUN->cui += 1)

@if($_HOLD_RUN->cui >= count($_HOLD_RUN->user))
    @php($_HOLD_RUN->cui = 0)
@endif

@php($_HOLD_RUN->currentUser = $_HOLD_RUN->user[$_HOLD_RUN->cui])

@endif