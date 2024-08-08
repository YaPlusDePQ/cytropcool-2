
@if( !isset($_INVENTORY_RUN) )
    <h1 style="font-weight:bold;color:red;">ERR: MISSING _INVENTORY_RUN VARIABLE. USE HoldableController::displayInventory <h1>
@else

    @if( !isset($_item) )
        <h1 style="font-weight:bold;color:red;">ERR: MISSING item VARIABLE. ADD  ['_item' => *your data*] WHEN CALLING INCLUDE <h1>
    @else
        @php($_INVENTORY_RUN->toDisplay = $_item)
        @include('holdable.inventory.'.$_item->position)
    @endif
@endif