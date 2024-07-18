<li json="{{json_encode($_HOLD_INVENTORY_DATA)}}">
    <div>
        <span style="{{$_HOLD_INVENTORY_DATA->data}}">{{Auth::user()->name}}</span>
    </div>
    <p>
        {{$_HOLD_INVENTORY_DATA->name}}
    </p>
</li>
