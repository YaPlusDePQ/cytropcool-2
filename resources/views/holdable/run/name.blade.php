@if(isset($_HOLD_RUN->currentUser->name))
    @foreach($_HOLD_RUN->currentUser->name as $tag => $d)

    <{{$tag}} @if($tag != 'style') style="display:flex" @endif>

        @foreach($_HOLD_RUN->currentUser->name[$tag] as $item)
            {!!  str_replace(['&class-start&', '&class-end&'], ['.'.$_HOLD_RUN->userIdClass.$_HOLD_RUN->currentUser->id.'{', '}'], $item->data)  !!}
        @endforeach

    </{{$tag}}>

    @endforeach
@endif