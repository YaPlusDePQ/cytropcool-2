@if(isset($_HOLD_RUN->currentUser->name))
    @foreach($_HOLD_RUN->currentUser->name as $tag => $d)

    <{{$tag}}>

        @if($tag == 'style')
            ._user{{$_HOLD_RUN->currentUser->id}}{
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