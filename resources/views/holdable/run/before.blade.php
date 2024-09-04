@if(isset($_HOLD_RUN->currentUser->before))
    @foreach($_HOLD_RUN->currentUser->before as $tag => $d)

    <{{$tag}} @if($tag != 'style') style="display:flex" @endif>

        @foreach($_HOLD_RUN->currentUser->before[$tag] as $item)
            @if($tag == 'style')
            ._before{{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}{
            @endif
                {!!$item->data!!}
            @if($tag == 'style')
            }
            @endif
        @endforeach

    </{{$tag}}>

    @endforeach
@endif