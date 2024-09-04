@if($_INVENTORY_RUN->toDisplay->data)
<div class="_before_inventory_{{$_INVENTORY_RUN->toDisplay->id}}">
    <{{$_INVENTORY_RUN->toDisplay->tag}} >

    @if($_INVENTORY_RUN->toDisplay->tag == 'style')
    ._before_inventory_{{$_INVENTORY_RUN->toDisplay->id}}{
        @endif
        
        {!!$_INVENTORY_RUN->toDisplay->data!!}
        
        @if($_INVENTORY_RUN->toDisplay->tag == 'style')
    }
    <span>DEMO</span>
    @endif
    </{{$_INVENTORY_RUN->toDisplay->tag}}>
</div>
<div>
    <span class="{{$_INVENTORY_RUN->customClass}}">{{ $_INVENTORY_RUN->username[0] }}...</span>
</div>
@else
<div>
    <span class="{{$_INVENTORY_RUN->customClass}}">Rien</span>
</div>
@endif