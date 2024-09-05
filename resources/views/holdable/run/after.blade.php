@if(isset($_HOLD_RUN->currentUser->after))
    @foreach($_HOLD_RUN->currentUser->after as $tag => $d)

    <{{$tag}} @if($tag != 'style') style="display:flex" @endif>

        @foreach($_HOLD_RUN->currentUser->after[$tag] as $item)
                {!!  str_replace(['&class-start&', '&class-end&'], ['._after'.$_HOLD_RUN->userIdClass.$_HOLD_RUN->currentUser->id.'{', '}'], $item->data)  !!}
        @endforeach

    </{{$tag}}>

    @endforeach
@endif