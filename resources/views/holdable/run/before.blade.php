@if(isset($_HOLD_RUN->currentUser->before))
    @foreach($_HOLD_RUN->currentUser->before as $tag => $d)

    <{{$tag}}>

        @if($tag == 'style')
            ._before_user{{$_HOLD_RUN->currentUser->id}}{
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