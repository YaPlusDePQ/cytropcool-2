@if(isset($_HOLD_RUN->currentUser->before))
    @foreach($_HOLD_RUN->currentUser->before as $tag => $d)

    <{{$tag}} @if($tag != 'style') style="display:flex" @endif>

        @foreach($_HOLD_RUN->currentUser->before[$tag] as $item)
            {!!  str_replace(['&class-start&', '&class-end&'], ['._before'.$_HOLD_RUN->userIdClass.$_HOLD_RUN->currentUser->id.'{', '}'], $item->data)  !!}
        @endforeach

    </{{$tag}}>

    @endforeach
@endif