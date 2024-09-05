@if($_INVENTORY_RUN->toDisplay->data)
<div>
    <span class="{{$_INVENTORY_RUN->customClass}}">{{ $_INVENTORY_RUN->username[0] }}...</span>
</div>
<div class="_after_inventory_{{$_INVENTORY_RUN->toDisplay->id}}">
    <{{$_INVENTORY_RUN->toDisplay->tag}}>
        
        {!!  str_replace(['&class-start&', '&class-end&'], ['._after_inventory_'.$_INVENTORY_RUN->toDisplay->id.'{', '}'], $_INVENTORY_RUN->toDisplay->data)  !!}

    </{{$_INVENTORY_RUN->toDisplay->tag}}>
</div>
@else
<div>
    <span class="{{$_INVENTORY_RUN->customClass}}">Rien</span>
</div>
@endif