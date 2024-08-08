<div>
    <{{$_INVENTORY_RUN->toDisplay->tag}}>

    @if($_INVENTORY_RUN->toDisplay->tag == 'style')
    ._name_inventory_{{$_INVENTORY_RUN->toDisplay->id}}{
        @endif
        
        {!!$_INVENTORY_RUN->toDisplay->data!!}
        
        @if($_INVENTORY_RUN->toDisplay->tag == 'style')
    }
    @endif
    </{{$_INVENTORY_RUN->toDisplay->tag}}>
    <span class="{{$_INVENTORY_RUN->customClass}} _name_inventory_{{$_INVENTORY_RUN->toDisplay->id}}">{{ $_HOLD_RUN->currentUser->username }}</span>
</div>