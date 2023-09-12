<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabelSm">
        Bokningsinformation<br/>
        <span class="font-weight-light h6">{{ $customer_details->address }} -
                                                                            #{{ $order->order_id }}</span>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-12 col-md-6">
            <h5 class="lead">Kunddetaljer</h5>
            <div class="card mb-3">
                <div class="card-body">
                    <strong>Namn:</strong>
                    {{ $customer_details->first_name }}
                    {{ $customer_details->last_name }}<br/>
                    <strong>E-Post:</strong>
                    {{ $customer_details->email }}
                    <br/>
                    <strong>Telefonnummer:</strong>
                    {{ $customer_details->phone }}
                    <br/>
                    <strong>Personnummer:</strong>
                    {{ $customer_details->personal_number }}
                    <br/>
                    <strong>Faktureras via:</strong>
                    {{ $customer_details->billing_method }}<br/>

                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <h5 class="lead">Adressdetaljer</h5>
            <div class="card mb-3">
                <div class="card-body">
                    <strong>Adress:</strong> <a target="_blank"
                                                href="https://google.se/maps/place/{{ $customer_details->address }} {{ $customer_details->city }}">{{ $customer_details->address }}</a>
                    <br/>
                    <strong>Stad:</strong> {{ $customer_details->city }}<br/>
                    <strong>Postkod:</strong>
                    {{ $customer_details->postal_code }}<br/>
                    @if ($customer_details->entry_code)
                        <strong>Portkod:</strong> {{ $customer_details->entry_code ?? '' }}<br/>
                    @endif

                </div>
            </div>
        </div>
        <!--<div class="col-12 col-md-6">
            <h5 class="lead">Aviseringar</h5>
            <div class="card mb-3">
                <div class="card-body">
                    <strong>SMS Påminnelser:</strong> {{ $order->sms_reminders == 1 ? 'Ja' : 'Nej' }}<br/>
                    <strong>E-Post Påminnelser:</strong> {{ $order->email_reminders == 1 ? 'Ja' : 'Nej' }}
        </div>
    </div>
</div>-->
        <div class="col-12">
            <h5 class="lead">Uppdragsdetaljer</h5>
        </div>
        <div class="col-12 col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <strong>Status:</strong> {{ $order->status_name }}<br/>
                    <strong>Bokad:</strong>
                    {{ \Carbon\Carbon::parse($order->order_date)->format('d/m - H:i') }}<br/>
                    <strong>Datum/tid:</strong>
                    {{ \Carbon\Carbon::parse($order->date_time)->format('d/m - H:i') }}<br/>
                    <strong>Uppskattat pris:</strong> {{ number_format($order->expected_time) }} kr
                    @if ($order->discounted)
                        ({{ $order->price }} - {{ $order->discounted }} kr)
                    @endif
                    <br/>
                    <strong>Faktureras:</strong> {{ number_format($order->invoiced) }} kr <br/>
                    <strong>Reseersättning:</strong>
                    {{ $order->to_customer_price }} kr <br/>
                </div>
            </div>
        </div>

        @if (!empty($order->comment))
            <div class="col-12 col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <strong>Bokningskommentar</strong>
                        <p class="article-format pb-0 mb-0">{{ $order->comment }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($order->admin_comment_internal)
            <div class="col-12 col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <strong>Admin kommentar</strong>
                        <p class="article-format pb-0 mb-0">{!! $order->admin_comment_internal !!}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            @foreach ($order->services as $service)
                <div class="col-lg-6 mb-2">
                    <!-- Card -->
                    <div class="card">
                        <div class="card-body small p-3">
                            <h6 class="mb-0">{{ $service->service->name }} - {{ $service->quantity }} st</h6>

                            @if (!empty($service->service_options_array))
                                <p>
                                    <span class="fw-medium">Tillval:</span><br>
                                    @foreach($service->service_options_array as $option)
                                        <span>{{ $option['name'] ?? '' }} - {{ $option['quantity'] ?? 1 }} st</span><br/>
                                    @endforeach
                                </p>
                            @endif

                            @if ($service->service->drive_location === 'recycling')
                                Antal turer: {{ $order->to_location_times }}<br><br>
                            @endif

                            @if ($service->service->customer_materials === 'yes')
                            <span class="fw-medium">Har eget material:</span>
                            {{ $service->has_own_materials ? 'Ja' : 'Nej' }}
                            @endif

                            @if ($service->comments)
                                <br/>
                                <h6 class="fw-medium">Kommentar:</h6>
                                {{ $service->comments }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fi fi-close"></i>
        Stäng
    </button>
</div>

<script>

</script>
