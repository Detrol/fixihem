@extends('layout.app')

@section('content')
    <main id="content" role="main">
        <!-- Hero -->
        <div class="container content-space-t-3">
            <div class="row justify-content-lg-between align-items-lg-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h2><span class="text-primary">FIX</span><span>i</span><span
                            class="text-primary">HEM</span> – För allt det där du skjuter upp!</h2>
                    <p>
                        Varje hem har dem: små men viktiga uppgifter som ständigt skjuts upp. Även om de kanske inte verkar mycket för världen,
                        kan de göra en stor skillnad i ditt vardagsliv.
                        Från att rengöra ventilationssystem till att byta batterier i brandvarnare – dessa tillsynes obetydliga detaljer kan påverka din hemmiljö avsevärt.
                    </p>
                    <p>
                        Med Fixihem behöver du inte längre oroa dig för dessa uppgifter. Jag tar hand om dem åt dig, så att ditt hem inte bara blir skinande rent,
                        utan också mer funktionellt och bekvämt.
                        Varje detalj räknas.
                    </p>
                    <p>
                        Alla tjänster jag erbjuder är <strong>RUT-berättigade</strong>. Priset är
                        <strong>249 kr/timma</strong>, inklusive RUT-avdrag. Det gör det både prisvärt och enkelt för dig.
                    </p>
                    <p>
                        Jag utför arbete i och runt <strong>Karlstads</strong> kommun.
                    </p>
                    <h5>Exempel på tjänster:</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-oven"></i> Rengöring av ugn och spis</li>
                        <li><i class="fas fa-recycle"></i> Köra skräp och kläder till återvinning</li>
                        <li><i class="fas fa-water"></i> Rengöring av avlopp och vattenlås</li>
                        <li><i class="fas fa-shower"></i> Kalkborttagning på duschväggar</li>
                        <li><i class="fas fa-computer-classic"></i> Felsökning och problemlösning på din dator</li>
                    </ul>
                </div>

                <!-- End Col -->

                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="position-relative">
                            <img class="img-fluid" src="./assets/img/jag1.jpg" alt="Image Description">

                            <div class="position-absolute bottom-0 end-0">
                                <img class="w-100" src="./assets/svg/illustrations/cubics.svg" alt="SVG"
                                     style="max-width: 30rem;">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Hero -->


        <!-- Features -->
        <div class="overflow-hidden">
            <div class="container content-space-2 py-8">
                <h2 class="mb-0">Vad önskar du hjälp med?</h2>
                <p class="text-muted">Om du önskar hjälp med något som inte finns i listan nedan, kontakta mig gärna via bokningsformuläret längst ned på sidan.</p>

                <div class="small pb-4">
                    Innan du bokar mina tjänster, vänligen beakta följande:
                    <ul class="pl-3">
                        <li>Minsta fakturerade belopp: <strong>200 kr</strong>.</li>
                        <li>Jag kör en kombi för transport. Om du har mycket skräp kan ett släp hyras, välj då till Släp(nätgrind) nedan. Vänligen sortera skräp i förväg. Observera att jag inte kan hantera mycket tunga eller stora föremål på egen hand.</li>
                        <li>I nästa steg kan du lägga till en kommentar för varje vald tjänst, om du har specifika önskemål eller information att dela med dig av.</li>
                        <li>Reseersättning på 5 kr/km (tur/retur) tillkommer om det totala beloppet för reseersättning överstiger 100 kr.</li>
                    </ul>
                </div>

                <form action="{{ route('booking.step1') }}" method="POST" id="bookingForm">
                    @csrf
                    <div class="row">
                        @foreach($categories as $category)
                            <div class="col-12 col-md-4">
                                <h4>{{ $category->name }}</h4>
                                <p>{{ $category->description }}</p>

                                <div class="servicesContainer">
                                    @foreach($category->services->where('active', 1) as $service)
                                        <div class="service-wrapper form-check form-check-select">
                                            <input type="checkbox" class="form-check-input service-checkbox"
                                                   value="{{ $service->id }}"
                                                   name="services[]" id="service{{ $service->id }}"
                                                   data-price="{{ $service->price }}"
                                                   data-material-price="{{ $service->material_price ?? 0 }}"
                                                   data-customer-material="{{ $service->customer_materials }}"
                                                   data-type="{{ $service->type }}"
                                                   data-estimated-minutes="{{ $service->estimated_minutes }}">


                                            <span class="form-check-label">
                                                <span class="fw-medium service-name"
                                                      data-service-id="{{ $service->id }}">{{ $service->name }}</span>
                                                <span
                                                    class="d-block fs-6 text-muted">{{ $service->description }}</span>
                                                {{--@if($service->is_rut)
                                                    <span class="d-block fs-6 text-success">RUT-berättigad</span>
                                                @endif
                                                @if($service->is_rot)
                                                    <span class="d-block fs-6 text-warning">ROT-berättigad</span>
                                                @endif --}}
                                            </span>
                                            <span class="form-check-stretched-bg"></span>

                                            <div class="service-options" style="display:none;">
                                                @if($service->service_options)
                                                    @foreach($service->service_options as $option)
                                                        <div class="form-check" style="z-index: 2;">
                                                            <input type="checkbox" id="option{{ $option->id }}"
                                                                   class="form-check-input {{ $option->is_required ? 'required-option' : '' }}"
                                                                   name="options[]"
                                                                   data-price="{{ $option->price ?? 0 }}"
                                                                   data-estimated-minutes="{{ $option->estimated_minutes }}"
                                                                   data-has-quantity="{{ $option->has_quantity }}"
                                                                   value="{{ $option->id }}">
                                                            <label class="form-check-label"
                                                                   for="option{{ $option->id }}">{{ $option->name }}</label>

                                                            @if($option->has_quantity)
                                                                <div class="option-quantity-wrapper"
                                                                     style="display: none;">
                                                                    <input type="number"
                                                                           name="option_quantity[{{ $option->id }}]"
                                                                           value="1"
                                                                           placeholder="Antal"
                                                                           class="form-control form-control-sm mt-2"
                                                                           style="width: 100px;">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endif

                                                @if($service->type === 'quantity')
                                                    <div class="form-group col-3 mt-2">
                                                        <input type="number"
                                                               name="service_quantity[{{ $service->id }}]"
                                                               value="1"
                                                               placeholder="Antal"
                                                               class="form-control form-control-sm service-quantity">
                                                    </div>
                                                @endif

                                                @if($service->customer_materials === 'yes')
                                                    <div class="has-material-check">
                                                        <input type="checkbox" class="form-check-input has-material"
                                                               id="hasMaterial{{ $service->id }}"
                                                               name="has_material[{{ $service->id }}]"
                                                               value="{{ $service->id }}">
                                                        <label
                                                            for="hasMaterial{{ $service->id }}">Jag har eget material</label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                </form>

            </div>
        </div>

        <section class="py-lg-10">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="bg-light px-xl-0 rounded-4 text-dark">
                            <div class="row align-items-center">
                                <div class="col-xl-6 col-md-6 col-12 p-6">
                                    <div>
                                        <h2 class="mb-3">Är du intresserad av fönsterputs?</h2>
                                        <p class="fs-4">Besök gärna min andra verksamhet som jag kör parallelt med denna!</p>
                                        <a href="https://putsikarlstad.se" target="_blank" class="btn btn-dark">Besök här</a>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-12 px-6 pe-lg-10 pb-6 pb-lg-0">
                                    <div class="text-center">
                                        <img src="{{ asset('assets/img/putsikarlstad.png') }}" alt="learning" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--<div id="stickyPopup" class="sticky-popup bg-dark text-white rounded align-items-center justify-content-center">
            <span id="rutText" class="me-2"></span>
            <span id="totalPrice" data-current-price="0" class="me-2">0.00</span> kr
            <button type="submit" form="bookingForm" class="btn btn-primary btn-sm ms-3 go-forward-btn">
                <span>Gå vidare</span>
                <i class="fa fa-angle-right ms-lg-2"></i>
            </button>
        </div>-->


        <!-- Post a Comment -->
        <div class="container content-space-1">
            <!-- Heading -->
            <div class="w-lg-65 text-center mx-lg-auto mb-7">
                <h3>Vill du kontakta mig?</h3>
                <p>
                    Använd formuläret nedan för att kontakta mig. Saknar du någon tjänst eller behöver hjälp med något annat?
                    Hör av dig, så ser vi om jag kan vara till hjälp.
                </p>
            </div>
            <!-- End Heading -->

            <div class="row justify-content-lg-center">
                <div class="col-lg-8">
                    <!-- Card -->
                    <div class="card card-lg card-bordered shadow-none">
                        <div class="card-body">
                            <form method="post" action="{{ route('form_mail') }}">
                                @csrf
                                <input type="hidden" name="action" value="contact_form_submit" tabindex="-1">
                                <input type="text" name="norobot" value="" class="d-none" tabindex="-1">

                                <div class="d-grid gap-4">
                                    <!-- Name Field -->
                                    <span class="d-block">
                                        <label class="form-label" for="name">Namn</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                               value="{{ old('name') }}"
                                               id="name" placeholder="Svea Svensson"
                                               aria-label="Namn" required>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </span>

                                    <!-- Email Field -->
                                    <span class="d-block">
                                        <label class="form-label" for="email">Din e-post</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}"
                                               id="email" placeholder="epost@sida.com"
                                               aria-label="Din e-post" required>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </span>

                                    <!-- Message Field -->
                                    <span class="d-block">
                                        <label class="form-label" for="message">Meddelande</label>
                                        <textarea class="form-control @error('message') is-invalid @enderror" id="message"
                                                  name="message"
                                                  placeholder="Lämna din kommentar här..."
                                                  aria-label="Meddelande" rows="5" required>{{ old('message') }}</textarea>
                                        @error('message')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </span>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Skicka in</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Post a Comment -->
    </main>

    <div id="stickyPopup" class="sticky-popup bg-dark text-white rounded align-items-center justify-content-center">
        <span id="rutText" class="me-2"></span>
        <span id="totalTime" data-current-time="0" class="me-2">0</span>
        <button id="submitForm" type="submit" form="bookingForm" class="btn btn-primary btn-sm ms-3 go-forward-btn">
            <span>Gå vidare</span>
            <i class="fa fa-angle-right ms-lg-2"></i>
        </button>
    </div>

@endsection

@push('js')
    <script src="/assets/js/home.js"></script>
    <script>
        $(document).ready(function () {
            $('.service-wrapper').on('click', function (event) {
                const checkbox = $(this).find('.service-checkbox');

                // Om klick-eventet kom från kryssrutan själv eller från tjänstens namn, låt det passera igenom normalt
                if ($(event.target).hasClass('service-checkbox') || $(event.target).hasClass('service-name')) {
                    checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
                    return;
                }

                // Om tjänsten inte är vald, tillåt valet
                if (!checkbox.prop('checked')) {
                    checkbox.prop('checked', true).trigger('change');
                }
            });

            $('.service-name').off('click'); // Tar bort tidigare 'click'-händelse för att förhindra dubbelt klickbeteende
        });
    </script>
@endpush
