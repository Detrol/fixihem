@extends('layout.app')

@section('content')

    <main id="content" role="main">
        {{--@foreach (session()->all() as $key => $value)
             <p><strong>{{ $key }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</p>
         @endforeach--}}
        <!-- Card Grid -->
        <div class="container content-space-t-3">
            <div class="row justify-content-lg-between sidebar-parent">
                <div class="col-lg-8 mb-10 mb-lg-0">

                    <h2 class="mb-4">Bokningsuppgifter</h2>

                    <div id="address-section">
                        <h4>Angiv adress för tjänsten</h4>
                        <form id="address-form">
                            <div class="form-group">
                                <label for="address">Adress</label>
                                <input type="text" id="address" name="address" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="city">Ort</label>
                                <input type="text" id="city" name="city" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="postal_code">Postnummer</label>
                                <input type="text" id="postal_code" name="postal_code" class="form-control" required>
                            </div>
                            <button type="button" id="check-address" class="btn btn-primary mt-3">Kontrollera adress
                            </button>
                        </form>
                    </div>

                    <div id="loading" class="invisible justify-content-center align-items-center"
                         style="height: 100vh; position: fixed; top: 0; left: 0; right: 0; z-index: 2000; background-color: rgba(255, 255, 255, 0.8); display: flex;">
                        <div class="text-center mx-auto">
                            <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                            <div>Laddar... Vänligen vänta.</div>
                        </div>
                    </div>

                    <div id="recycling-message" class="alert alert-light border-1 border-opacity-10 border-dark mb-4"
                         style="display: none;">
                        <strong><span class="drive-location-name"></span></strong>
                        <p>Detta är den närmsta återvinningscentral till din adress som vi kunde hitta.</p>
                        <ul class="list-unstyled pl-3">
                            <li><i class="fas fa-road mr-2"></i> Kilometer dit från din adress: <span
                                    class="recycling-distance"></span> km
                            </li>
                            <li><i class="fas fa-coins mr-2"></i> Reseersättning för en tur: <span
                                    id="single-trip-recycling-price"></span> kr
                            </li>
                        </ul>
                        <p class="mb-0">Kom ihåg att det är ditt ansvar att säkerställa att det är öppet den dag och tid du har bokat hjälp.</p>
                    </div>

                    <form id="book" method="post" action="{{ route('booking.store') }}">
                        @csrf
                        <div id="personal-details-section" style="display: none">
                            <h4>Välj datum och tid</h4>
                            <div class="row mb-4">
                                <div class="col-12 col-md-6">

                                    <div class="form-group">
                                        <div id="datepicker"
                                             data-date="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"></div>
                                        <input type="hidden" name="date" id="my_hidden_input" required>
                                    </div>

                                    <div class="form-floating col-12 col-md-8 mb-3 mt-3">
                                        <select name="time" class="form-select" id="time" aria-label="Önskad tid"
                                                required>
                                            <option selected>Välj datum först</option>
                                        </select>
                                        <label for="time">Önskad tid</label>
                                    </div>

                                </div>

                                <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
                                    <img class="img-fluid m-auto" src="{{ asset('assets/img/calendar2.png') }}"
                                         alt="">
                                </div>

                            </div>

                            <h4>Fyll i dina personuppgifter</h4>

                            <div class="row mb-4">
                                <!-- Förnamn & Efternamn -->
                                <div class="col-md-6 form-group">
                                    <label for="first_name">Förnamn</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="last_name">Efternamn</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                                </div>

                                <!-- Personnummer & E-post -->
                                <div class="col-md-6 form-group">
                                    <label for="personal_number">Personnummer</label>
                                    <input type="text" id="personal_number" name="personal_number" class="form-control"
                                           required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="email">E-post</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>

                                <!-- Telefonnummer & Portkod -->
                                <div class="col-md-6 form-group">
                                    <label for="phone">Telefonnummer</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="door_code">Portkod</label>
                                    <input type="text" id="door_code" name="door_code" class="form-control">
                                </div>

                                <!-- Fakturametod -->
                                <div class="col-12 mt-3 mb-3 form-group">
                                    <p class="lead">Fakturametod:</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="billing_method" id="sms"
                                               value="SMS" required checked>
                                        <label class="form-check-label" for="sms">SMS</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="billing_method"
                                               id="email-billing" value="E-Post" required>
                                        <label class="form-check-label" for="email-billing">E-Post</label>
                                    </div>
                                </div>
                            </div>

                            <h6 class="lead m-0">Påminnelser</h6>
                            <p class="text-muted small">Få påminnelse en dag i förväg</p>

                            <div class="form-check">
                                <input type="checkbox" id="email_reminders" class="form-check-input"
                                       name="email_reminder" value="1">
                                <label class="form-check-label" for="email_reminders">E-Post</label>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" id="sms_reminders" class="form-check-input" name="sms_reminder"
                                       value="1">
                                <label class="form-check-label" for="sms_reminders">SMS</label>
                            </div>

                            <div class="mb-3">
                                <p class="lead">Valfri kommentar</p>
                                <textarea id="comment" class="form-control" name="comment" placeholder="Kommentar" rows="4"></textarea>
                            </div>

                        </div>

                    </form>

                    <div id="price-info" style="display: none">

                        <h2 class="mb-4">Prisuppgifter</h2>

                        <div class="row">

                            <!-- Kundinformation kolumn -->
                            <div class="col-md-6" id="distance-info" style="display: none;">
                                <h4 class="mb-3">Kundinformation</h4>
                                <ul class="list-group mb-4">
                                    <li class="list-group-item">
                                        <i class="fa fa-user mr-2"></i>
                                        Avstånd till din adress: <strong id="calculated-distance"></strong> km
                                    </li>
                                </ul>
                            </div>

                            <!-- Återvinningsinformation kolumn -->
                            <div class="col-md-6 mb-3" id="drive-location-info" style="display: none;">
                                <h4 class="mb-3">Återvinningsinformation</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <i class="fas fa-recycle mr-2"></i>
                                        Avstånd till återvinning: <strong
                                            class="recycling-distance"></strong> km
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-car mr-2"></i>
                                        Reseersättning till återvinning: <strong
                                            class="recycling-price" id="recycling-price"></strong> kr
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fa fa-clock mr-2"></i>
                                        Researbetskostnad: <strong id="time-price"></strong> kr
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fa fa-repeat mr-2"></i>
                                        Antal turer: <strong id="number-of-trips"></strong> (x2)
                                        <i class="fa fa-info-circle ml-2" data-toggle="tooltip" title="Antalet turer dubblas med det antal du angav i
föregående steg, eftersom vi räknar med tur/retur här, det gäller också priserna ovan."></i>
                                    </li>
                                </ul>
                            </div>

                            <!-- Prisinformation kolumn -->
                            <div id="price-container" class="col-md-6 mb-4" data-total-price="{{ $totalPrice }}">
                                <h4 class="mb-3">Prisinformation</h4>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <i class="fa fa-car mr-2"></i>
                                        Reseersättning till dig: <strong id="travel-price"></strong> kr
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fa fa-tag mr-2"></i>
                                        Uppskattat pris: <strong id="preliminary-price">{{ $totalPrice }} kr</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="alert alert-warning small p-3">
                            <strong>Observera:</strong>
                            <ul class="mt-2 mb-0 pl-1 text-dark">
                                <li>Priserna ovan är endast lösa uppskattningar baserat på tiden och kan variera beroende på olika faktorer. Det slutgiltiga priset kan påverkas av olika omständigheter som uppstår under arbetets gång.</li>
                                <li>Min taxa är 299 kr/timme (inklusive RUT), räknat från det att jag anländer till det att jag lämnar platsen.</li>
                                <li>Reseersättning och eventuell hyrning av släp kan inte faktureras tillsammans med RUT. Därför kommer du att få en separat faktura för dessa.</li>
                                <li>Betalningsvillkor är 7 dagar.</li>
                            </ul>
                        </div>

                    </div>


                    <div class="form-check mt-3" id="terms-container" style="display: none;">
                        <input class="form-check-input check" type="checkbox" value="1" id="terms" required>
                        <label class="form-check-label" for="terms">Jag accepterar de <a href="{{ route('terms') }}"
                                                                                         target="_blank">allmänna villkoren</a>.</label>
                    </div>
                    <div class="form-check" id="privacy-container" style="display: none;">
                        <input class="form-check-input check" type="checkbox" value="1" id="privacy" required>
                        <label class="form-check-label" for="privacy">Jag accepterar <a href="{{ route('privacy') }}"
                                                                                        target="_blank">sekretesspolicyn</a>.</label>
                    </div>

                    <button type="submit" form="book" class="btn btn-primary mt-3 submitButton" id="confirm-button"
                            style="display: none;">
                        Bekräfta och boka
                        <i class="fa fa-check ms-1"></i>
                    </button>

                </div>
                <!-- End Col -->

                <div class="col-lg-3" id="sidebar">
                    <h3>Valda tjänster</h3>
                    @foreach ($servicesData as $serviceId => $service)
                        <div class="col-lg-12 mb-2">
                            <!-- Card -->
                            <div class="card">
                                <div class="card-body p-3">
                                    <h5 class="mb-0">{{ $service['name'] }}</h5>
                                    @if($service['price'] != 0.0)
                                        <h6>{{ $service['quantity'] ? $service['quantity'] . ' st - ' : '' }}<span
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

                                    {{--
                                    @if($service['is_rut'])
                                        <span class="d-block fs-6 text-success">RUT-berättigad</span>
                                    @endif

                                    @if($service['is_rot'])
                                        <span class="d-block fs-6 text-warning">ROT-berättigad</span>
                                    @endif
                                    --}}

                                    @if (count($service['options']) > 0)
                                        <p>
                                            @foreach($service['options'] as $option)
                                                {{ $option->name }}

                                                @if($option->price != 0)
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

                                    @if($service['customer_materials'] === 'yes')
                                        <span
                                            class="fw-medium">Har eget material:</span> {{ $service['has_material'] ? 'Ja' : 'Nej' }}
                                    @endif
                                </div>
                            </div>
                            <!-- End Card -->
                        </div>
                        <!-- End Col -->
                    @endforeach
                    <!-- End Col -->
                </div>
                <!-- End Row -->
            </div>
            <!-- End Card Grid -->
        </div>
    </main>

    <div id="numberOfTrips" data-travels="{{ session('travels') }}"></div>

@endsection

@push('js')
    <!-- JS Implementing Plugins -->
    <script src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.sv.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="/assets/js/address_logic.js"></script>
    <script src="/assets/js/sticky.js"></script>
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sticky-sidebar/3.3.1/jquery.sticky-sidebar.min.js"></script>


    <!-- Storing service drive location data -->
    <script type="application/json" id="serviceDriveLocationData">
        {!! json_encode(array_column($servicesData, 'drive_location')) !!}
    </script>

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#personal_number').mask('00000000-0000', {placeholder: "_________-____"});
        $('#postal_code').mask('000 00', {placeholder: "___ __"});

        $("#book").submit(function (e) {
            e.preventDefault(); // Förhindra omedelbar inskickning av formuläret

            $(".submitButton").prop("disabled", true).removeClass('fa fa-check').html(
                'Skapar bokning <i class="fa fa-circle-notch fa-spin"></i>'
            );

            setTimeout(function() {
                $("#book").off("submit").submit(); // Ta bort eventlyssnaren och skicka in formuläret
            }, 3000); // Fördröj med 3 sekunder (3000 millisekunder)
        });

        $('.submitButton').prop('disabled', true).prop('title', 'Kryssa i nödvändiga boxarna');

        $(document).ready(function () {
            $('.check').click(function () {
                if (allChecked()) {
                    $(".submitButton").prop('disabled', false).prop('title', '');
                } else {
                    $(".submitButton").prop('disabled', true);
                }
            });

            $('.click').each(function () {
                $(this).click(function () {
                    $(this).find('i.caret').toggleClass("rotate");
                });
            });
        });

        function allChecked() {
            let checkboxes = $('.check'),
                allChecked = true;
            checkboxes.each(function (i, e) {
                allChecked = allChecked && $(e).is(":checked");
            });
            return allChecked;
        }

        $('#datepicker').datepicker({
            startView: 0,
            todayBtn: false,
            keyboardNavigation: false,
            forceParse: true,
            autoclose: false,
            format: "yyyy-mm-dd",
            language: 'sv',
            calendarWeeks: true,
            daysOfWeekDisabled: '6,0',
            todayHighlight: true,
            datesDisabled: '',
            startDate: '{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}',
            endDate: '{{ $endDate }}',
            updateViewDate: false
        }).on('changeDate', function () {
            $('#my_hidden_input').val(
                $('#datepicker').datepicker('getFormattedDate')
            );

            $.ajax({
                type: 'GET',
                url: '/check-date',
                dataType: "json",
                data: {
                    date: $('#my_hidden_input').val(),
                    _token: token,
                },
                success: function (data) {
                    $("#time").prop('disabled', false);

                    let len = data.length;

                    $("#time").empty();
                    for (let i = 0; i < len; i++) {
                        let name = data[i];
                        //alert(name);

                        $("#time").append("<option value='" + name + "'>" + name + "</option>");
                    }
                }
            });
        });
    </script>
@endpush

