<div>
    <{{$_INVENTORY_RUN->toDisplay->tag}}>

        {!!  str_replace(['&class-start&', '&class-end&'], ['._name_inventory_'.$_INVENTORY_RUN->toDisplay->id.'{', '}'], $_INVENTORY_RUN->toDisplay->data)  !!}
        
    </{{$_INVENTORY_RUN->toDisplay->tag}}>
    <span class="{{$_INVENTORY_RUN->customClass}} _name_inventory_{{$_INVENTORY_RUN->toDisplay->id}}">{{ $_INVENTORY_RUN->username }}</span>
</div>