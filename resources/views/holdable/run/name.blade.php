@if(isset($_HOLD_RUN->currentUser->name))
    @foreach($_HOLD_RUN->currentUser->name as $tag => $d)

    <{{$tag}} @if($tag != 'style') style="display:flex" @endif>

        @foreach($_HOLD_RUN->currentUser->name[$tag] as $item)
            @if($tag == 'style')
            .{{$_HOLD_RUN->userIdClass}}{{$_HOLD_RUN->currentUser->id}}{
            @endif
                {!!$item->data!!}
            @if($tag == 'style')
            }
            @endif
        @endforeach

    </{{$tag}}>

    @endforeach
@endif