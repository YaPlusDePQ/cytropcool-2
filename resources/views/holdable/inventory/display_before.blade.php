<li json="{{json_encode($_HOLD_INVENTORY_DATA)}}">
    <div>
    @if($_HOLD_INVENTORY_DATA->data)
    {!!$_HOLD_INVENTORY_DATA->data!!}
    <span>...</span>
    @else
    <span>Rien</span>
    @endif
    </div>
    <p>
        {{$_HOLD_INVENTORY_DATA->name}}
    </p>
</li>
