@extends('layout.app')

@section('content')

    <main id="content" role="main">
        <!-- Hero -->
        <div class="container content-space-t-3">

            @if ($hasMixed)
                <div class="alert alert-warning small p-2">
                    Observera! Du har valt blandade tjänster. Du kommer att få separata fakturor baserat på tjänstetypen (RUT, icke RUT, och ROT).
                </div>
            @endif

            <h2>Dina valda tjänster</h2>
            <p>
                Nedan ser du de tjänster du har valt tillsammans med lite kort information.
                Här kan du också lämna en kommentar för varje tjänst om det finns något särskilt rörande den som du tycker att jag bör känna till.
            </p>

            <form action="{{ route('booking.processStep2') }}" method="post">
                @csrf

                <div class="row align-items-lg-start">
                    @foreach ($servicesData as $serviceId => $service)
                        <div class="col-lg-4 mb-4">
                            <!-- Card -->
                            <div class="card card-shadow h-100">
                                <div class="card-body">
                                    <h4 class="mb-0">{{ $service['name'] }}</h4>
                                    @if($service['price'] != 0)
                                        <h6>
                                            {{ $service['quantity'] ? $service['quantity'] . ' st - ' : '' }}
                                            <span
                                                class="text-primary">{{ $service['price'] }} kr{{ $service['quantity'] ? '/st' : '' }}</span>
                                        </h6>
                                    @endif
                                    @if ($service['material_price'])
                                        <span class="fw-medium">Materialkostnad:</span>
                                        @if ($service['has_material'])
                                            <s>{{ $service['material_price'] }} kr/st</s><br/>
                                        @else
                                            {{ $service['material_price'] }} kr/st<br/>
                                        @endif
                                    @endif

                                    @if($service['customer_materials'] === 'yes')
                                        <span
                                            class="fw-medium">Har eget material:</span> {{ $service['has_material'] ? 'Ja' : 'Nej' }}
                                    @endif

                                    @if (count($service['options']) > 0)
                                        <p>
                                            @foreach($service['options'] as $option)
                                                {{ $option->name }}

                                                @if($option->price != 0.0)
                                                    @if($service['has_material'])
                                                        <s>{{ $option->price ? '(' . $option->price . ' kr/st)' : '' }}</s>
                                                    @else
                                                        {{ $option->price ? '(' . $option->price . ' kr/st)' : '' }}
                                                    @endif
                                                @endif

                                                @if ($option->quantity > 1)
                                                    - {{ $option->quantity ?? 1 }} st
                                                @endif
                                                {{ $loop->last ? '' : ', ' }}
                                            @endforeach
                                        </p>
                                    @endif

                                    {{--@if($service['is_rut'])
                                        <span class="d-block fs-6 text-success">RUT-berättigad</span>
                                    @endif
                                    @if($service['is_rot'])
                                        <span class="d-block fs-6 text-warning">ROT-berättigad</span>
                                    @endif--}}

                                    <div class="mt-3">
                                        <label for="comment_{{ $serviceId }}">Kommentar:</label>
                                        <textarea name="comment[{{ $serviceId }}]" id="comment_{{ $serviceId }}"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- End Card -->
                        </div>
                        <!-- End Col -->
                    @endforeach
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary mt-3">Gå vidare</button>
                </div>
            </form>
        </div>
    </main>

    <!--<div id="stickyPopup" class="sticky-popup-visible">
        Preliminärt pris: {{ $totalPrice }} kr
    </div>-->

@endsection

@push('js')
@endpush
