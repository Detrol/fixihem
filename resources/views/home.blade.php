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
                        Varje detalj räknas, och inget uppdrag är för litet för mig.
                    </p>
                    <h5>Exempel på tjänster:</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-oven"></i> Rengöring av ugn och spis</li>
                        <li><i class="fas fa-recycle"></i> Köra skräp och kläder till återvinning</li>
                        <li><i class="fas fa-water"></i> Rengöring av avlopp och vattenlås</li>
                        <li><i class="fas fa-shower"></i> Kalkborttagning på duschväggar</li>
                        <li><i class="fas fa-window-maximize"></i> Byta eller installera lister i fönster</li>
                    </ul>

                </div>
                <!-- End Col -->

                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="position-relative">
                            <img class="img-fluid" src="./assets/img/950x950/img1.jpg" alt="Image Description">

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

        {{--<!-- Icon Blocks -->
        <div class="container content-space-t-2 content-space-t-lg-3">
            <div class="row">
                <div class="col-sm-6 col-lg mb-5 mb-lg-0">
                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi-phone fs-1 text-dark"></i>
                        </div>

                        <h5>Responsive</h5>
                        <span class="d-block">Responsive, and mobile-first project on the web</span>
                    </div>
                    <!-- End Icon Block -->
                </div>
                <!-- End Col -->

                <div class="col-sm-6 col-lg mb-5 mb-lg-0">
                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi-toggles2 fs-1 text-dark"></i>
                        </div>

                        <h5>Customizable</h5>
                        <span class="d-block">Components are easily customized</span>
                    </div>
                    <!-- End Icon Block -->
                </div>
                <!-- End Col -->

                <div class="col-sm-6 col-lg mb-5 mb-sm-0">
                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi-file-earmark-text fs-1 text-dark"></i>
                        </div>

                        <h5>Documentation</h5>
                        <span class="d-block">Every component and plugin is well documented</span>
                    </div>
                    <!-- End Icon Block -->
                </div>
                <!-- End Col -->

                <div class="col-sm-6 col-lg">
                    <!-- Icon Block -->
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="bi-chat-right-dots fs-1 text-dark"></i>
                        </div>

                        <h5>24/7 Support</h5>
                        <span class="d-block">Contact us 24 hours a day, 7 days a week.</span>
                    </div>
                    <!-- End Icon Block -->
                </div>
                <!-- End Col -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Icon Blocks -->--}}

        <!-- Features -->
        <div class="overflow-hidden">
            <div class="container content-space-2 content-space-lg-3">
                <h2>Vad önskar du hjälp med?</h2>

                <div class="small pb-4">
                    Innan du bokar mina tjänster, vänligen beakta följande:
                    <ul class="pl-3">
                        <li>Fakturering sker med ett minimibelopp om <strong>300 kr</strong>.</li>
                        <li>Om tjänsten innefattar transport av skräp eller kläder till återbruk, kan reseersättning tillkomma. Denna avgift beror på avståndet till din närmaste servicepunkt eller återvinningsstation. Systemet räknar ut detta automatiskt vid bokningens slut och visar både avstånd och reseersättning.</li>
                        <li>Tänk på att jag kör en kombi när jag utför tjänster som kräver resor till återvinningscentraler. Om du har stora föremål eller mycket skräp att transportera, kan jag vid behov hyra ett släp. Om skräpet är i påsar, vänligen sortera det i förväg. Observera att jag arbetar ensam och därför inte kan hantera för stora och tunga föremål.</li>
                        <li>Alla priser på hemsidan presenteras inklusive RUT eller ROT-avdrag, alternativt utan avdrag. Nedan ser du vilka tjänster som kvalificerar sig för respektive avdrag.</li>
                    </ul>
                </div>

                <form action="{{ route('booking.step1') }}" method="POST">
                    @csrf
                    <div class="row">
                        @foreach($categories as $category)
                            <div class="col-12 col-md-4">
                                <h4>{{ $category->name }}</h4>
                                <p>{{ $category->description }}</p>

                                <div class="servicesContainer">
                                    @foreach($category->services as $service)
                                        <div class="service-wrapper">
                                            <label class="form-check form-check-select" for="service{{ $service->id }}">
                                                <input type="checkbox" class="form-check-input service-checkbox"
                                                       value="{{ $service->id }}"
                                                       name="services[]" id="service{{ $service->id }}"
                                                       data-price="{{ $service->price }}"
                                                       data-material-price="{{ $service->material_price ?? 0 }}"
                                                       data-customer-material="{{ $service->customer_materials }}"
                                                       data-type="{{ $service->type }}">
                                                <span class="form-check-label">
                                                    <span class="fw-medium">{{ $service->name }}</span>
                                                    <span
                                                        class="d-block fs-6 text-muted">{{ $service->description }}</span>
                                                    @if($service->is_rut)
                                                        <span class="d-block fs-6 text-success">RUT-berättigad</span>
                                                    @endif
                                                    @if($service->is_rot)
                                                        <span class="d-block fs-6 text-warning">ROT-berättigad</span>
                                                    @endif
                                                </span>
                                                <span class="form-check-stretched-bg"></span>

                                                <div class="service-options" style="display:none;">
                                                    @if($service->service_options)
                                                        @foreach($service->service_options as $option)
                                                            <div class="form-check" style="z-index: 2;">
                                                                <input type="checkbox" id="option{{ $option->id }}"
                                                                       class="form-check-input" name="options[]"
                                                                       data-price="{{ $option->price ?? 0 }}"
                                                                       value="{{ $option->id }}">
                                                                <label class="form-check-label"
                                                                       for="option{{ $option->id }}">{{ $option->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                    @if($service->type === 'quantity')
                                                        <div class="form-group col-3 mt-2">
                                                            <input type="number"
                                                                   name="service_quantity[{{ $service->id }}]"
                                                                   value="1"
                                                                   placeholder="Antal"
                                                                   class="form-control form-control-sm">
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
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            Gå vidare
                            <i class="fa fa-angle-right ms-2"></i>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <div id="stickyPopup" class="sticky-popup">
            <span id="rutText"></span> <span id="totalPrice" data-current-price="0">0.00</span> kr
        </div>

        <!-- Post a Comment -->
        <div class="container pb-10">
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

@endsection

@push('js')
    <script src="/assets/js/home.js"></script>
@endpush
