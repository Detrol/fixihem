@extends('layout.admin')

@section('title', 'Dagens Pass')

@section('content')
    <div class="section">


        <div class="row">
            @foreach ($order_details as $order)
                <div class="col-12 col-md-3 mb-3">

                    <div class="card">
                        <div class="card-body small">
                            <a href="#"
                               data-href="{{ route('order_details', ['order_id' => $order['order_id']]) }}"
                               data-ajax-modal-size="modal-lg" data-ajax-modal-centered="true"
                               data-ajax-modal-callback-function=""
                               class="font-weight-medium m-0 text-decoration-none js-ajax-modal">{{ $order['address'] }}</a>
                            <p class="m-0">
                                {{ $order['name'] }}
                            </p>
                            <p class="m-0">
                                <strong>Datum:</strong> {{ $order['date'] }}
                            </p>
                            <p class="m-0">
                                <strong>Pris:</strong> {{ number_format($order['price']) }} kr
                            </p>
                            <p class="m-0">
                                <strong>Netto:</strong> {{ number_format($order['paid']) }} kr
                            </p>
                            @if (!empty($mission->discount_code))
                                <p class="m-0">
                                    <strong>Rabattkod:</strong> {{ $order['discount_code'] }}
                                </p>
                                <p class="m-0">
                                    <strong>Rabatterat:</strong> {{ number_format($order['discounted']) }}
                                </p>
                            @endif
                            <p class="m-0">
                                <strong>Distans:</strong> {{ $order['distance_price'] }} kr
                            </p>
                            <p>
                                <strong>Restid:</strong> {{ $order['travel_time'] }}
                            </p>
                            <p class="m-0">
                                <strong>Förväntad tid:</strong> {{ $order['expected_time'] }}
                            </p>

                            @if ($order['start'] !== $order['stop'] && $order['stop'] !== null)
                                <p class="m-0">
                                    <strong>Arbetad tid:</strong>
                                    {{ $order['worked_time'] }}
                                </p>

                                <p class="m-0">
                                    {{ number_format($order['hourlyRate']) }}
                                    kr/h
                                    (netto
                                    {{ number_format($order['hourlyRateNet']) }}
                                    kr/h)
                                </p>
                            @endif

                            <div class="dropdown mb-3 mt-2">
                                <a class="btn btn-xs btn-primary text-white dropdown-toggle" href="#"
                                   role="button"
                                   id="exDropdown"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    Alternativ
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="exDropdown">
                                    <h6 class="dropdown-header">Markera</h6>
                                    <li>
                                        <a href="#"
                                           data-href="{{ route('order_completed', ['order_id' => $order['order_id']]) }}"
                                           class="js-ajax-confirm dropdown-item"

                                           data-ajax-confirm-mode="regular"

                                           data-ajax-confirm-size="modal-md"
                                           data-ajax-confirm-centered="true"

                                           data-ajax-confirm-title="Bekräfta åtgärd"
                                           data-ajax-confirm-body="Är du säker på att du vill markera ordern som Utförd?"

                                           data-ajax-confirm-btn-yes-class="btn-primary text-white btn-sm"
                                           data-ajax-confirm-btn-yes-text="Acceptera"
                                           data-ajax-confirm-btn-yes-icon="fi fi-check"

                                           data-ajax-confirm-btn-no-class="btn-light btn-sm"
                                           data-ajax-confirm-btn-no-text="Avbryt"
                                           data-ajax-confirm-btn-no-icon="fi fi-close">
                                            Utförd
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ route('order_start_time', ['order_id' => $order['order_id']]) }}"
                               class="btn btn-xs btn-success text-white">Start</a>
                            <a href="{{ route('order_stop_time', ['order_id' => $order['order_id']]) }}"
                               class="btn btn-xs btn-danger text-white">Stop</a>
                            <a href="#"
                               class="btn btn-xs btn-primary text-white">Redigera</a>

                            @if ($order['start'] !== null && $order['stop'] === null)
                                <div class="text-muted mt-2">Startad!</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('script')

@endsection
