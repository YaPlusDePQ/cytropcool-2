@if($_INVENTORY_RUN->toDisplay->data)
<div class="_before_inventory_{{$_INVENTORY_RUN->toDisplay->id}}">
    <{{$_INVENTORY_RUN->toDisplay->tag}} >

        {!!  str_replace(['&class-start&', '&class-end&'], ['._before_inventory_'.$_INVENTORY_RUN->toDisplay->id.'{', '}'], $_INVENTORY_RUN->toDisplay->data)  !!}
        
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