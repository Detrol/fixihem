<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabelSm">
        Bokningsinformation<br/>
        <span class="font-weight-light h6">{{ $order->customer->address }} - #{{ $order->order_id }}</span>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <strong>Faktura för Order #{{ $order->order_id }}</strong><br>
    -----------------------------------<br>
    <strong>Faktureras via:</strong> {{ $order->customer->billing_method }}<br><br>

    @if($rutServices)
    # <strong>RUT tjänster</strong><br>
        @foreach ($rutServices as $service)
            {{ $service->service->name }} ({{ $service->quantity }} st) - {{ $service->service->price * $service->quantity }} kr<br>
            @if(is_array($service->service_options_array) && !empty($service->service_options_array))
                @foreach($service->service_options_array as $option)
    -- {{ $option['name'] }}: {{ $option['price'] }} kr<br>
                @endforeach
            @endif
        @endforeach
        <br>
    @endif

    @if($rotServices)
    # <strong>ROT tjänster</strong><br>
        @foreach ($rotServices as $service)
            {{ $service->service->name }} ({{ $service->quantity }} st) - {{ $service->service->price * $service->quantity }} kr<br>
            @if(is_array($service->service_options_array) && !empty($service->service_options_array))
                @foreach($service->service_options_array as $option)
    -- {{ $option['name'] }}: {{ $option['price'] }} kr<br>
                @endforeach
            @endif
        @endforeach
        <br>
    @endif

    @if($otherServices)
    # <strong>Andra tjänster (utan RUT/ROT)</strong><br>
        @foreach ($otherServices as $service)
            {{ $service->service->name }} ({{ $service->quantity }} st) - {{ $service->service->price * $service->quantity }} kr<br>
            @if(is_array($service->service_options_array) && !empty($service->service_options_array))
                @foreach($service->service_options_array as $option)
    -- {{ $option['name'] }}: {{ $option['price'] }} kr<br>
                @endforeach
            @endif
        @endforeach
        <br>
    @endif

    # Reseersättning<br>
    - Till kunden:<br>
    Avstånd: {{ $travelToCustomer['distance'] }} km<br>
    Tid: {{ $travelToCustomer['time'] }} min<br>
    Pris: {{ $travelToCustomer['price'] }} kr<br><br>

    @if($travelToLocation)
    - Till återvinning<br>
    Avstånd: {{ $travelToLocation['distance'] }} km<br>
    Tid: {{ $travelToLocation['time'] }} min<br>
    Pris (enkel resa): {{ $travelToLocation['single_trip_price'] }} kr<br>
    Antal resor: {{ $travelToLocation['trips_count'] }}<br>
    Totalt pris (flera resor): {{ $travelToLocation['total_price'] }} kr<br><br>
    @endif

    -----------------------------------<br>
    <strong>Totalt belopp:</strong> {{ $total }} kr
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fi fi-close"></i>
        Stäng
    </button>
</div>
