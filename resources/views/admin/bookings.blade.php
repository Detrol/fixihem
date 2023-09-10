@extends('layout.admin')

@section('title', 'Bokningar')

@section('content')

    <div class="section">
        <div class="alert alert-success">
            Netto: {{ number_format($earning) }} kr | Brutto {{ number_format($earning_before) }} kr
        </div>

        <div class="row">
            @foreach ($order_details as $order)
                <div class="col-12 col-md-6 col-lg-3 mb-3">

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
                                <strong>Uppskattat pris:</strong> {{ number_format($order['price']) }} kr
                            </p>
                            <p class="m-0">
                                <strong>Uppskattat netto:</strong> {{ number_format($order['paid']) }} kr
                            </p>
                            @if (!empty($order['discount_code']))
                                <p class="m-0">
                                    <strong>Rabattkod:</strong> {{ $order['discount_code'] }}
                                </p>
                                <p class="m-0">
                                    <strong>Rabatterat:</strong> {{ number_format($order['discounted']) }} kr
                                </p>
                            @endif
                            <p class="m-0">
                                <strong>Distans:</strong> {{ $order['distance_price'] }} kr
                            </p>
                            <p>
                                <strong>Restid:</strong> {{ $order['travel_time'] }}
                            </p>

                            @if ($order['status'] == 3)
                                <p class="m-0">
                                    <strong>Slutpris:</strong> {{ number_format($order['customer_price']) }} kr
                                </p>
                                <p>
                                    <strong>Netto:</strong> {{ number_format($order['net_earnings']) }} kr
                                </p>
                            @endif

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

                            @if ($order['status'] == 1)
                                <div class="d-flex">
                                    <a href="{{ route('order_accept', ['order_id' => $order['order_id']]) }}"
                                       class="btn btn-link text-success d-inline">Godkänn</a>
                                    <a href="{{ route('order_abort', ['order_id' => $order['order_id']]) }}"
                                       class="btn btn-link text-danger d-inline">Neka</a>
                                </div>
                                <a href="{{ route('order_edit', ['order_id' => $order['order_id']]) }}"
                                   class="btn btn-link d-inline">Redigera</a>
                            @else
                                <div class="d-flex">
                                    <div class="d-inline">
                                        <div class="dropdown">
                                            <a class="btn btn-link dropdown-toggle" href="#" role="button"
                                               id="exDropdown"
                                               data-bs-toggle="dropdown" aria-expanded="false">
                                                Alternativ
                                            </a>

                                            <ul class="dropdown-menu" aria-labelledby="exDropdown">
                                                @if (request()->segment(3) === '3' || request()->segment(3) === '4')
                                                    <h6 class="dropdown-header">Faktura</h6>
                                                    <li>
                                                        <a href="#"
                                                           data-href="{{ route('admin.order.invoice-text', $order['order_id']) }}"
                                                           data-ajax-modal-size="modal-lg"
                                                           data-ajax-modal-centered="true"
                                                           data-ajax-modal-callback-function=""
                                                           class="dropdown-item js-ajax-modal">
                                                            Text
                                                        </a>
                                                    </li>
                                                @endif
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
                                                <li>
                                                    <a href="#"
                                                       data-href="{{ route('order_invoiced', ['order_id' => $order['order_id']]) }}"
                                                       class="js-ajax-confirm dropdown-item"

                                                       data-ajax-confirm-mode="regular"

                                                       data-ajax-confirm-size="modal-md"
                                                       data-ajax-confirm-centered="true"

                                                       data-ajax-confirm-title="Bekräfta åtgärd"
                                                       data-ajax-confirm-body="Är du säker på att du vill markera ordern som Fakturerad?"

                                                       data-ajax-confirm-btn-yes-class="btn-primary text-white btn-sm"
                                                       data-ajax-confirm-btn-yes-text="Acceptera"
                                                       data-ajax-confirm-btn-yes-icon="fi fi-check"

                                                       data-ajax-confirm-btn-no-class="btn-light btn-sm"
                                                       data-ajax-confirm-btn-no-text="Avbryt"
                                                       data-ajax-confirm-btn-no-icon="fi fi-close">
                                                        Fakturerad
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                       data-href="{{ route('order_paid', ['order_id' => $order['order_id']]) }}"
                                                       class="js-ajax-confirm dropdown-item"

                                                       data-ajax-confirm-mode="regular"

                                                       data-ajax-confirm-size="modal-md"
                                                       data-ajax-confirm-centered="true"

                                                       data-ajax-confirm-title="Bekräfta åtgärd"
                                                       data-ajax-confirm-body="Är du säker på att du vill markera ordern som Betald?"

                                                       data-ajax-confirm-btn-yes-class="btn-primary text-white btn-sm"
                                                       data-ajax-confirm-btn-yes-text="Acceptera"
                                                       data-ajax-confirm-btn-yes-icon="fi fi-check"

                                                       data-ajax-confirm-btn-no-class="btn-light btn-sm"
                                                       data-ajax-confirm-btn-no-text="Avbryt"
                                                       data-ajax-confirm-btn-no-icon="fi fi-close">
                                                        Betald
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                       data-href="{{ route('order_pause', ['order_id' => $order['order_id']]) }}"
                                                       class="js-ajax-confirm dropdown-item"

                                                       data-ajax-confirm-mode="regular"

                                                       data-ajax-confirm-size="modal-md"
                                                       data-ajax-confirm-centered="true"

                                                       data-ajax-confirm-title="Bekräfta åtgärd"
                                                       data-ajax-confirm-body="Är du säker på att du vill markera ordern som Pausad?"

                                                       data-ajax-confirm-btn-yes-class="btn-primary text-white btn-sm"
                                                       data-ajax-confirm-btn-yes-text="Acceptera"
                                                       data-ajax-confirm-btn-yes-icon="fi fi-check"

                                                       data-ajax-confirm-btn-no-class="btn-light btn-sm"
                                                       data-ajax-confirm-btn-no-text="Avbryt"
                                                       data-ajax-confirm-btn-no-icon="fi fi-close">
                                                        Pausa
                                                    </a>
                                                </li>
                                                <li class="dropdown-divider"></li>
                                                <li>
                                                    <a href="#"
                                                       data-href="{{ route('order_abort', ['order_id' => $order['order_id']]) }}"
                                                       class="js-ajax-confirm dropdown-item"

                                                       data-ajax-confirm-mode="regular"

                                                       data-ajax-confirm-size="modal-md"
                                                       data-ajax-confirm-centered="true"

                                                       data-ajax-confirm-title="Bekräfta åtgärd"
                                                       data-ajax-confirm-body="Är du säker på att du vill markera ordern som Makulerad?"

                                                       data-ajax-confirm-btn-yes-class="btn-primary text-white btn-sm"
                                                       data-ajax-confirm-btn-yes-text="Acceptera"
                                                       data-ajax-confirm-btn-yes-icon="fi fi-check"

                                                       data-ajax-confirm-btn-no-class="btn-light btn-sm"
                                                       data-ajax-confirm-btn-no-text="Avbryt"
                                                       data-ajax-confirm-btn-no-icon="fi fi-close">
                                                        Makulera
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#"
                                                       data-href="{{ route('order_accept', ['order_id' => $order['order_id']]) }}"
                                                       class="js-ajax-confirm dropdown-item"

                                                       data-ajax-confirm-mode="regular"

                                                       data-ajax-confirm-size="modal-md"
                                                       data-ajax-confirm-centered="true"

                                                       data-ajax-confirm-title="Bekräfta åtgärd"
                                                       data-ajax-confirm-body="Är du säker på att du vill återbekräfta ordern?"

                                                       data-ajax-confirm-btn-yes-class="btn-primary text-white btn-sm"
                                                       data-ajax-confirm-btn-yes-text="Acceptera"
                                                       data-ajax-confirm-btn-yes-icon="fi fi-check"

                                                       data-ajax-confirm-btn-no-class="btn-light btn-sm"
                                                       data-ajax-confirm-btn-no-text="Avbryt"
                                                       data-ajax-confirm-btn-no-icon="fi fi-close">
                                                        Återbekräfta
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="d-inline">
                                        <a href="{{ route('order_edit', ['order_id' => $order['order_id']]) }}"
                                           class="btn btn-link">Redigera</a>
                                    </div>
                                </div>
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
