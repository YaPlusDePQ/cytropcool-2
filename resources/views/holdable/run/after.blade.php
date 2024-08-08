@if(isset($_HOLD_RUN->currentUser->after))
    @foreach($_HOLD_RUN->currentUser->after as $tag => $d)

    <{{$tag}}>

        @if($tag == 'style')
            ._after_user{{$_HOLD_RUN->currentUser->id}}{
        @endif

        @foreach($_HOLD_RUN->currentUser->after[$tag] as $item)
                {!!$item->data!!}
        @endforeach

        @if($tag == 'style')
            }
        @endif
    </{{$tag}}>

    @endforeach
@endif