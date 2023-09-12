@extends('layout.app')

@section('content')
    <main id="content" role="main">
        <div class="container content-space-t-3">
            <div class="container pt-4">

                <h1 class="display-5 fw-medium mb-5">
                    Cookies
                </h1>

                <!--
                        .article-format class will add some slightly formattings for a good text visuals.
                        This is because most editors are not ready formatted for bootstrap
                        The content should come inside this container, as it is from database!
                        src/scss/_core/base/_typography.scss
                    -->
                <div class="article-format">

                    <h1 class="cookie-policy-h1">Cookiepolicy</h1>
                    <p>
                        Ikraftträdande datum: 19-Mar-2022 <br>
                        Senast uppdaterad: 19-Mar-2022
                    </p>

                    &nbsp;
                    <h5>Vad är cookies?</h5>
                    <div class="cookie-policy-p"><p>Denna cookiepolicy förklarar vad cookies är och hur vi använder dem, vilka typer
                                                    av cookies vi använder, dvs informationen vi samlar in med cookies och hur den informationen används,
                                                    och hur man hanterar cookieinställningarna.</p>
                        <p>Cookies är små textfiler som används för att lagra små bitar av information. De förvaras på
                           din enhet när webbplatsen laddas i din webbläsare. Dessa cookies hjälper oss att göra webbplatsen
                           fungerar korrekt, gör det säkrare, ger en bättre användarupplevelse och förstår hur
                           webbplatsen utför och analysera vad som fungerar och var den behöver förbättras.</p></div>

                    &nbsp;
                    <h5>Hur använder vi cookies?</h5>
                    <div class="cookie-policy-p"><p>Som de flesta av onlinetjänsterna använder vår webbplats första part och tredje part
                                                    cookies för flera ändamål. Förstapartscookies är oftast nödvändiga för att webbplatsen ska fungera
                                                    på rätt sätt, och de samlar inte in någon av dina personligt identifierbara uppgifter.</p>
                        <p>De tredjepartscookies som används på vår webbplats är främst för att förstå hur webbplatsen fungerar,
                           hur du interagerar med vår webbplats, håller våra tjänster säkra, tillhandahåller annonser som är
                           relevant för dig, och allt som allt ger dig en bättre och förbättrad användarupplevelse och hjälp
                           påskynda dina framtida interaktioner med vår webbplats.</p></div>

                    &nbsp;
                    <h5>Typ av cookies vi använder</h5>

                    <div class="cky-audit-table-element"></div>

                    &nbsp;
                    <h5 style="margin-bottom:20px;">Hantera cookie-preferenser</h5>

                    <a class="cky-banner-element btn btn-primary btn-sm mb-3 text-white">Cookie-inställningar</a> <br/>

                    <div><p>Du kan ändra dina cookie-preferenser när som helst genom att klicka på knappen ovan. Detta låter dig
                            besöka bannern för samtycke för cookies igen och ändra dina inställningar eller dra tillbaka ditt
                            samtycke direkt.</p>
                        <p>Utöver detta erbjuder olika webbläsare olika metoder för att blockera och radera cookies som används
                           av webbplatser. Du kan ändra inställningarna i din webbläsare för att blockera/radera cookies. Nedan
                           listas länkarna till supportdokumenten om hur man hanterar och raderar cookies från de stora
                           webbläsarna.</p>
                        <p>Chrome: <a target="_blank" rel="noopener noreferrer"
                                      href="https://support.google.com/accounts/answer/32050">https://support.google.com/accounts/answer/32050</a>
                        </p>
                        <p>Safari: <a target="_blank" rel="noopener noreferrer"
                                      href="https://support.apple.com/en-in/guide/safari/sfri11471/mac">https://support.apple.com/en-in/guide/safari/sfri11471/mac</a>
                        </p>
                        <p>Firefox: <a target="_blank" rel="noopener noreferrer"
                                       href="https://support.mozilla.org/en-US/kb/clear-cookies-and-site-data-firefox?redirectslug=delete-cookies-remove-info-websites-stored&amp;redirectlocale=en-US">https://support.mozilla.org/en-US/kb/clear-cookies-and-site-data-firefox?redirectslug=delete-cookies-remove-info-websites-stored&amp;redirectlocale=en-US</a>
                        </p>
                        <p>Internet Explorer: <a target="_blank" rel="noopener noreferrer"
                                                 href="https://support.microsoft.com/en-us/topic/how-to-delete-cookie-files-in-internet-explorer-bca9446f-d873-78de-77ba-d42645fa52fc">https://support.microsoft.com/en-us/topic/how-to-delete-cookie-files-in-internet-explorer-bca9446f-d873-78de-77ba-d42645fa52fc</a>
                        </p>
                        <p>Om du använder någon annan webbläsare, besök din webbläsares officiella supportdokument.</p></div>


                    &nbsp;
                    <p class="cookie-policy-p">
                        Cookie Policy Generated By <a target="_blank"
                                                      href="https://www.cookieyes.com/?utm_source=CP&utm_medium=footer&utm_campaign=UW">CookieYes
                                                                                                                                        - Cookie Policy Generator</a>.
                    </p>

                </div>
            </div>
        </div>
    </main>
@endsection
@push('js')

@endpush
