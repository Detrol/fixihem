@extends('layout.admin')

@section('title', 'Specialbokning')

@section('content')
    <div class="section">
        <!-- Felmeddelanden -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('save-time') }}" method="POST">
            @csrf
            <h4>Välj tid för expected_time</h4>
            <div class="row">
                <!-- Timmar -->
                <div class="col-auto">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Timmar:</div>
                        </div>
                        <input type="number" class="form-control" id="timmar" placeholder="00" name="hours" value="{{ $hours }}">
                    </div>
                </div>

                <!-- Minuter -->
                <div class="col-auto">
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Minuter:</div>
                        </div>
                        <input type="number" class="form-control" id="minuter" placeholder="00" name="minutes" value="{{ $minutes }}">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Spara tid</button>
        </form>

        @if (session()->has('expected_time'))
        <div id="address-section" class="mt-4">
            <h4>Angiv adress för tjänsten</h4>
            <form action="{{ route('save-address') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="address">Adress</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{ session('address') }}" required>
                </div>
                <div class="form-group">
                    <label for="city">Ort</label>
                    <input type="text" id="city" name="city" class="form-control" value="{{ session('city') }}" required>
                </div>
                <div class="form-group">
                    <label for="postal_code">Postnummer</label>
                    <input type="text" id="postal_code" name="postal_code" class="form-control" value="{{ session('postal_code') }}" required>
                </div>
                <div class="form-check mb-3 mt-3">
                    <input type="checkbox" id="formCheck11" class="form-check-input" name="recycling">
                    <label class="form-check-label" for="formCheck11">Koppla återvinning</label>
                </div>
                <div class="form-group">
                    <label for="toLocationTimes">Antal resor till återvinningscentral:</label>
                    <input type="number" id="toLocationTimes" name="to_location_times" class="form-control"
                           value="{{ session('travels') ?? 1 }}" required>
                </div>
                <button type="submit" id="check-address" class="btn btn-primary mt-3">Kontrollera adress
                </button>
            </form>
        </div>
        @endif

        @if (session()->has('location_name'))
        <div id="recycling-message" class="alert alert-light border-1 border-opacity-10 border-dark mb-4 mt-4">
            <strong>{{ session('location_name') }}</strong>
            <p>Detta är den närmsta återvinningscentral till din adress som vi kunde hitta.</p>
            <ul class="list-unstyled pl-3">
                <li><i class="fas fa-road mr-2"></i> Kilometer dit från din adress: {{ session('location_distance') }} km
                </li>
                <li><i class="fas fa-coins mr-2"></i> Reseersättning för en tur: {{ session('location_price') }} kr
                </li>
            </ul>
        </div>
        @endif

        @if (session()->has('travel_duration'))
            <form class="mt-4" method="post" action="{{ route('special-post') }}">
                @csrf
                <div id="personal-details-section">
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
                            <input type="number" id="phone" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="door_code">Portkod</label>
                            <input type="text" id="door_code" name="door_code" class="form-control">
                        </div>

                        <!-- Fakturametod -->
                        <div class="col-12 mt-3 form-group">
                            <p class="lead">Fakturametod:</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="billing_method" id="sms"
                                       value="SMS" required checked>
                                <label class="form-check-label" for="sms">SMS</label>
                                <div class="text-muted small">Du kommer få ett SMS med en länk som startar en
                                    Swish-betalning.
                                </div>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="billing_method"
                                       id="email-billing" value="E-Post" required>
                                <label class="form-check-label" for="email-billing">E-Post</label>
                                <div class="text-muted small">Du får en faktura skickad till din mail i form av en
                                    fil.
                                </div>
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
                        <textarea id="comment" class="form-control" name="comment" placeholder="Kommentar"
                                  rows="4"></textarea>
                    </div>

                    <hr />

                    <div class="mb-3">
                        <p class="lead">Tjänstetext för specialbokning</p>
                        <textarea id="special_text" class="form-control" name="special_text"
                                  rows="4"></textarea>
                    </div>

                    <hr />

                    <div class="row mt-4">
                        @foreach($categories as $category)
                            <div class="col-12 col-md-4">
                                <h4>{{ $category->name }}</h4>
                                <p>{{ $category->description }}</p>
                                <div class="servicesContainer">
                                    @foreach($category->services->where('active', 1) as $service)
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="service-wrapper form-check form-check-select">
                                                        <!-- Checkbox to add service -->
                                                        <input type="checkbox" class="form-check-input service-checkbox"
                                                               value="{{ $service->id }}"
                                                               name="add_services[]" id="serviceAdd{{ $service->id }}">
                                                        <label class="form-check-label" for="serviceAdd{{ $service->id }}">
                                                    <span class="fw-medium service-name"
                                                          data-service-id="{{ $service->id }}">{{ $service->name }}</span>
                                                            <span
                                                                class="d-block fs-6 text-muted">{{ $service->description }}</span>
                                                        </label>

                                                        <!-- Quantity input for services with type 'quantity' -->
                                                        @if($service->type === 'quantity')
                                                            <div class="form-group col-3 mt-2">
                                                                <input type="number" name="service_quantity[{{ $service->id }}]"
                                                                       value="1" placeholder="Antal"
                                                                       class="form-control form-control-sm service-quantity">
                                                            </div>
                                                        @endif

                                                        <!-- Service options -->
                                                        <div class="service-options">
                                                            @if($service->service_options)
                                                                @foreach($service->service_options as $option)
                                                                    <div class="form-check">
                                                                        <input type="checkbox" id="optionAdd{{ $option->id }}"
                                                                               class="form-check-input" name="add_options[]"
                                                                               value="{{ $option->id }}">
                                                                        <label class="form-check-label"
                                                                               for="optionAdd{{ $option->id }}">{{ $option->name }}</label>

                                                                        @if($option->has_quantity)
                                                                            <div class="option-quantity-wrapper">
                                                                                <input type="number"
                                                                                       name="add_option_quantity[{{ $option->id }}]"
                                                                                       value="1" placeholder="Antal"
                                                                                       class="form-control form-control-sm mt-2"
                                                                                       style="width: 100px;">
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>

                                                        @if($service->customer_materials === 'yes')
                                                            <div class="mt-2">
                                                                <input type="checkbox" id="hasOwnMaterials{{ $service->id }}"
                                                                       class="form-check-input"
                                                                       name="has_own_materials[{{ $service->id }}]" value="1">
                                                                <label class="form-check-label"
                                                                       for="hasOwnMaterials{{ $service->id }}">Har egna material</label>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach

                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>

                <button type="submit" class="btn btn-primary btn-sm">Spara bokning</button>

            </form>
        @endif
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.sv.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="/assets/js/address_logic.js"></script>
    <script src="/assets/js/sticky.js"></script>
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js') }}"></script>

    <script>
        $('#personal_number').mask('00000000-0000', {placeholder: "_________-____"});
        $('#postal_code').mask('000 00', {placeholder: "___ __"});

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
            endDate: '',
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
@endsection
