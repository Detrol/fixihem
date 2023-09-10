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

    Order #{{ $order->order_id }}<br>
    Datum: {{ Carbon\Carbon::parse($order->date_time)->format('d/m-y - H:i') }}<br>
    Adress: {{ $order->customer->address }}<br>
    Pris: {{ $totalServices }} kr<br>
    Faktureras via: {{ $order->customer->billing_method }}<br><br>

    # Pris<br>
    Timpris: 299 kr/timme<br><br>

    # Tid<br>
    Start: {{ $order->start_time }}<br>
    Slut: {{ $order->end_time }}<br>
    Total tid: {{ $totalTime }}<br><br>

    # Tjänster<br>
    @foreach ($services as $service)
        {{ $service->service->name }} ({{ $service->quantity }} st) {{ $service->service->price * $service->quantity != 0 ? ': ' . $service->service->price . ' kr' : '' }}<br>
        @foreach($service->service->service_options as $option)
            @if(!$option->not_rut) <!-- Exkludera tjänstalternativ med non_rut här -->
    -- {{ $option->name }} {{ $option->price != 0 ? '- ' . $option->price . ' kr' : '' }}<br>
            @endif
        @endforeach
    @endforeach
    <br>

    -----------------------------------<br>
    <strong>Totalt belopp:</strong> {{ $totalServices }} kr<br>
    <strong>Faktureras:</strong> {{ $invoicedServices }} kr<br><br>

    ================<br>
    <strong>Resefaktura för Order #{{ $order->order_id }}</strong><br>
    ================<br>

    Order #{{ $order->order_id }}<br>
    Datum: {{ Carbon\Carbon::parse($order->date_time)->format('d/m-y - H:i') }}<br>
    Adress: {{ $order->customer->address }}<br>
    Faktureras via: {{ $order->customer->billing_method }}<br><br>

    @if ($travelToCustomer['distance'])
    # Reseersättning till kund<br>
    Avstånd: {{ $travelToCustomer['distance'] }} km<br>
    Tid: {{ $travelToCustomer['time'] }} min<br>
    Pris: {{ $travelToCustomer['price'] }} kr<br><br>
    @endif

    @if($travelToLocation)
    # Reseersättning till återvinning<br>
    Avstånd: {{ $travelToLocation['distance'] }} km<br>
    Tid: {{ $travelToLocation['time'] }} min<br>
    Pris (enkel resa): {{ $travelToLocation['single_trip_price'] }} kr<br>
    Antal resor: {{ $travelToLocation['trips_count'] }}<br>
    Totalt pris (flera resor): {{ $travelToLocation['total_price'] }} kr<br><br>
    @endif

    @foreach ($nonRutOptions as $option)
        {{ $option->name }} {{ $option->price != 0 ? '- ' . $option->price . ' kr' : '' }}<br><br>
    @endforeach

    # Totalt Pris<br>
    Totalt: {{ $totalNonRutAndTravel }} kr<br><br>

    Avser reseersättning för tidigare fakturerat uppdrag med ovan Boknings-ID.<br><br>

    -----------------------------------<br>
    <strong>Totalt belopp:</strong> {{ $totalNonRutAndTravel }} kr<br>
    <strong>Faktureras:</strong> {{ $invoicedNonRutAndTravel }} kr<br><br>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fi fi-close"></i>
        Stäng
    </button>
</div>
