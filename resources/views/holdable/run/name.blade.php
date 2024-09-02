@if(isset($_HOLD_RUN->currentUser->name))
    @foreach($_HOLD_RUN->currentUser->name as $tag => $d)

    <{{$tag}} @if($tag != 'style') style="display:flex" @endif>

        @if($tag == 'style')
            .{{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}{
        @endif

        @foreach($_HOLD_RUN->currentUser->name[$tag] as $item)
                {!!$item->data!!}
        @endforeach

        @if($tag == 'style')
            }
        @endif
    </{{$tag}}>

    @endforeach
@endif