<style>
    ._user{{$_HOLD_RUN_DATA[$_HOLD_RUN_CURRENT_USER]->userId}}{
    @foreach($_HOLD_RUN_DATA[$_HOLD_RUN_CURRENT_USER]->style_name as $_style)
            {{$_style->data}};
    @endforeach
    }

</style>