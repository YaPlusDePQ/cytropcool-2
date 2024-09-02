@if(isset($_HOLD_RUN->currentUser->before))
    @foreach($_HOLD_RUN->currentUser->before as $tag => $d)

    <{{$tag}} @if($tag != 'style') style="display:flex" @endif>

        @if($tag == 'style')
            ._before{{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}{
        @endif

        @foreach($_HOLD_RUN->currentUser->before[$tag] as $item)
                {!!$item->data!!}
        @endforeach

        @if($tag == 'style')
            }
        @endif
    </{{$tag}}>

    @endforeach
@endif