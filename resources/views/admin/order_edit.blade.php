@extends('layout.admin')

@section('content')

    <div class="section">
        <label id="date_time" class="lead">Datum och tid</label>

        <div class="bg-light border-1 border-gray-300 p-3 mb-3 rounded">
            <strong>Datum och
                    tid:</strong> {{ \Carbon\Carbon::parse($order->date_time)->format('d/m-y - H:i') }}
            <br/>
        </div>

        <hr/>

        <form method="post" action="{{ route('order_edit_submit', ['order_id' => $order->order_id]) }}">
            @csrf
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

            <div class="row">
                <div class="col-12 col-md-6">
                    <h5>Kunduppgifter</h5>

                    <label class="lead">Kundkommentar</label>
                    <div class="form-label-group mb-3">
                        <textarea placeholder="Kommentar" name="comment" id="description" class="form-control"
                                  rows="2">{{ $order->comment }}</textarea>
                        <label for="description"></label>
                    </div>

                    <hr/>

                    <h6 class="lead m-0">Påminnelser</h6>
                    <div class="form-label-group form-check-inline mb-3">
                        <label class="form-switch form-switch-primary d-block">
                            <input type="checkbox" value="1"
                                   name="email_reminders" {{ $order->email_reminders ? 'checked' : '' }}>
                            <i data-on="&#10004;" data-off="&#10005;"></i>
                            <span>E-Post</span>
                        </label>
                    </div>

                    <div class="form-label-group form-check-inline mb-3">
                        <label class="form-switch form-switch-primary d-block">
                            <input type="checkbox" value="1"
                                   name="sms_reminders" {{ $order->sms_reminders ? 'checked' : '' }}>
                            <i data-on="&#10004;" data-off="&#10005;"></i>
                            <span>SMS</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="driveLocation">Välj återvinningscentral:</label>
                        <select name="drive_location_id" id="driveLocation" class="form-control">
                            <option value="">-- Välj en återvinningscentral --</option>
                            @foreach ($driveLocations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="toLocationTimes">Antal resor till återvinningscentral:</label>
                        <input type="number" id="toLocationTimes" name="to_location_times" class="form-control"
                               value="{{ $order->to_location_times }}" required>
                    </div>

                    <hr/>

                    <input type="hidden" id="disabled_dates" class="d-none" value="">
                </div>

                <div class="col-12 col-md-6">
                    <h5>Administrativt</h5>

                    <label class="lead">Tillägg</label>
                    <div class="form-label-group mb-3">
                        <input id="addition_kr" name="addition_kr"
                               type="text" class="form-control" autocomplete="off"
                               value="{{ $order->addition_kr }}">
                        <label for="addition_kr">Tillägg Kronor</label>
                    </div>

                    <div class="form-label-group mb-3">
                        <input id="addition_percent" name="addition_percent"
                               type="text" class="form-control" autocomplete="off"
                               value="{{ $order->addition_percent }}">
                        <label for="addition_percent">Tillägg Procent</label>
                    </div>

                    <div class="form-label-group mb-3">
                                <textarea placeholder="Tillägg kommentar" id="addition_comment" name="addition_comment"
                                          class="form-control">{{ $order->addition_comment }}</textarea>
                        <label for="addition_comment">Tillägg kommentar</label>
                    </div>

                    <label class="lead">Avdrag</label>
                    <div class="form-label-group mb-3">
                        <input id="deduction_kr" name="deduction_kr"
                               type="text" class="form-control" autocomplete="off"
                               value="{{ $order->deduction_kr }}">
                        <label for="deduction_kr">Avdrag Kronor</label>
                    </div>

                    <div class="form-label-group mb-3">
                        <input id="deduction_percent" name="deduction_percent"
                               type="text" class="form-control" autocomplete="off"
                               value="{{ $order->deduction_percent }}">
                        <label for="deduction_percent">Avdrag Procent</label>
                    </div>

                    <div class="form-label-group mb-3">
                                <textarea placeholder="Tillägg kommentar" id="deduction_comment"
                                          name="deduction_comment"
                                          class="form-control">{{ $order->deduction_comment }}</textarea>
                        <label for="deduction_comment">Avdrag kommentar</label>
                    </div>

                </div>
            </div>

            <hr/>

            <h4>Valda tjänster</h4>

            <div class="row">
                @foreach ($order->services as $service)
                    <div class="col-lg-6 mb-2">
                        <div class="card">
                            <div class="card-body small p-3">
                                <h6 class="mb-0">
                                    {{ $service->service->name }}
                                </h6>

                                @if($service->service->type === 'quantity')
                                    <div class="form-group">
                                        <label for="service[{{ $service->id }}][quantity]">Antal</label>
                                        <input type="number" name="service[{{ $service->id }}][quantity]"
                                               value="{{ $service->quantity }}" min="1" class="form-control">
                                    </div>
                                @endif

                                @if (count($service->service->service_options) > 0)
                                    <label class="fw-medium">Tillval:</label>
                                    @foreach($service->service->service_options as $option)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   name="service[{{ $service->id }}][options][]"
                                                   value="{{ $option->id }}"
                                                   class="custom-control-input"
                                                   id="option-{{ $option->id }}"
                                                {{ (in_array($option->id, $service->selected_option_ids)) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="option-{{ $option->id }}">
                                                {{ $option->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($service->service->customer_materials === 'yes')
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               name="service[{{ $service->id }}][has_own_materials]"
                                               value="1"
                                               class="custom-control-input"
                                               id="material-{{ $service->id }}"
                                            {{ $service->has_own_materials ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="material-{{ $service->id }}">
                                            Har eget material
                                        </label>
                                    </div>
                                @endif

                                <div class="form-group mt-3">
                                    <label for="service[{{ $service->id }}][comments]">Kommentar:</label>
                                    <textarea name="service[{{ $service->id }}][comments]"
                                              class="form-control">{{ $service->comments }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr/>

            <div class="row mt-4">
                @foreach($categories as $category)
                    <div class="col-12 col-md-4">
                        <h4>{{ $category->name }}</h4>
                        <p>{{ $category->description }}</p>
                        <div class="servicesContainer">
                            @foreach($category->services->where('active', 1) as $service)
                                @if(!in_array($service->id, $order->services->pluck('service_id')->toArray()))
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
                                @endif
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>

            <div class="row mt-5">
                <h4>Ta bort valda tjänster:</h4>
                @foreach($order->services as $service)
                    <div class="col-12 col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body small p-3">
                                <h6 class="mb-0">
                                    {{ $service->service->name }}
                                    <!-- Checkbox for removing service -->
                                    <input type="checkbox" name="remove_services[]" value="{{ $service->id }}"> Ta bort
                                </h6>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="row">
                <div class="position-relative">
                    <button type="submit" id="confirm_order"
                            class="btn btn-block btn-sm btn-success bg-gradient-success text-white float-end submitButton">
                        <i class="fi fi-check"></i>
                        Spara ändringar
                    </button>
                </div>
            </div>

        </form>
    </div>

@endsection

@section('script')
    <script src="{{ asset('/assets/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/assets/js/bootstrap-datepicker.sv.min.js') }}"></script>
    <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js') }}"></script>

    <script>
        $('#personal_number').mask('00000000-0000', {placeholder: "_________-____"});
        $('#organisation_number').mask('000000-0000', {placeholder: "_______-____"});
        $('#postal_code').mask('000 00', {placeholder: "___ __"});

        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var disabled_dates = $('#disabled_dates').val();

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
            startDate: '',
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
                    order_id: '{{ $order->id }}',
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
