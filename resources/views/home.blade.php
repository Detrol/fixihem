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
                        Alla tjänster jag erbjuder är <strong>RUT-berättigade</strong>. Priset är <strong>299 kr/timma</strong>, inklusive RUT-avdrag. Det gör det både prisvärt och enkelt för dig.
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
            <div class="container content-space-2 content-space-lg-3">
                <h2>Vad önskar du hjälp med?</h2>

                <div class="small pb-4">
                    Innan du bokar mina tjänster, vänligen beakta följande:
                    <ul class="pl-3">
                        <li>Fakturering: Minimibelopp <strong>300 kr</strong>.</li>
                        <li>Transport av skräp/kläder kan medföra extra kostnad. Tiden och avgiften baseras på avståndet till närmaste återvinningsstation. Detta räknas automatiskt ut i sista bokningssteget.</li>
                        <li>Jag kör en kombi för transport. Om du har mycket skräp kan ett släp hyras, välj då till Släp(nätgrind) nedan. Vänligen sortera skräp i förväg. Observera att jag inte kan hantera mycket tunga eller stora föremål på egen hand.</li>
                        <li>I nästa steg kan du lägga till en kommentar för varje vald tjänst, om du har specifika önskemål eller information att dela med dig av.</li>
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
                                                <span class="fw-medium service-name" data-service-id="{{ $service->id }}">{{ $service->name }}</span>
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
                                                                   class="form-check-input {{ $option->is_required ? 'required-option' : '' }}" name="options[]"
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

        <!-- CTA -->
        <div class="content-space-1 bg-light">
            <div class="w-lg-75 text-center mx-lg-auto">
                <h3 class="mb-4">Är du intresserad av fönsterputs?</h3>
                <figure class="mb-4">
                    <img class="img-fluid" src="{{ asset('assets/img/putsikarlstad.png') }}" alt="Image Description" style="height: 4rem;">
                </figure>
                <a class="link link-pointer" href="https://putsikarlstad.se" target="_blank">Klicka här för att besöka min andra verksamhet!</a>
            </div>
        </div>
        <!-- End CTA -->

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
                <h3>Saknar du någon tjänst?</h3>
                <p>
                    Ser du inte den tjänsten du önskar hjälp med i listan?
                    Skicka in något du önskar att jag kan hjälpa till med, så kanske det hamnar i listan ovan.
                </p>
            </div>
            <!-- End Heading -->

            <div class="row justify-content-lg-center">
                <div class="col-lg-8">
                    <!-- Card -->
                    <div class="card card-lg card-bordered shadow-none">
                        <div class="card-body">
                            <form>
                                <div class="d-grid gap-4">
                                    <!-- Form -->
                                    <span class="d-block">
                                        <label class="form-label" for="blogContactsFormEmail">Din e-post</label>
                                        <input type="email" class="form-control" name="blogContactsEmailName"
                                               id="blogContactsFormEmail" placeholder="email@site.com"
                                               aria-label="email@site.com">
                                    </span>
                                    <!-- End Form -->

                                    <!-- Form -->
                                    <span class="d-block">
                                        <label class="form-label" for="blogContactsFormComment">Kommentar</label>
                                        <textarea class="form-control" id="blogContactsFormComment"
                                                  name="blogContactsCommentName"
                                                  placeholder="Lämna din kommentar här..."
                                                  aria-label="Lämna din kommentar här..." rows="5"></textarea>
                                    </span>
                                    <!-- End Form -->

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
        $(document).ready(function() {
            $('.service-wrapper').on('click', function(event) {
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
