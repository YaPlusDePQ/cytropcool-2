@if($_INVENTORY_RUN->toDisplay->data)
<div>
    <span class="{{$_INVENTORY_RUN->customClass}}">{{ $_HOLD_RUN->currentUser->username[0] }}...</span>
</div>
<div class="_after_inventory_{{$_INVENTORY_RUN->toDisplay->id}}">
    <{{$_INVENTORY_RUN->toDisplay->tag}}>

    @if($_INVENTORY_RUN->toDisplay->tag == 'style')
    ._after_inventory_{{$_INVENTORY_RUN->toDisplay->id}}{
        @endif
        
        {!!$_INVENTORY_RUN->toDisplay->data!!}
        
        @if($_INVENTORY_RUN->toDisplay->tag == 'style')
    }
    <span>DEMO</span>
    @endif
    </{{$_INVENTORY_RUN->toDisplay->tag}}>
</div>
@else
<div>
    <span class="{{$_INVENTORY_RUN->customClass}}">Rien</span>
</div>
@endif