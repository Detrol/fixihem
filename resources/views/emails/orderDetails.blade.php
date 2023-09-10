<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>

<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">

<!-- start preheader -->
<div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    Vi har nu tagit emot din order
</div>
<!-- end preheader -->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">

            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" valign="top" style="font-size:0; padding: 35px;" bgcolor="#ffffff">

                        <div style="display:inline-block; min-width:100px; vertical-align:top; width:100%;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="max-width:300px;">
                                <tr>
                                    <td align="center" valign="top"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;"
                                        class="mobile-center">
                                        @if (settings('logo'))
                                            <img src="{{ url('/assets/images/uploads/logo/logo.png') }}" width="420px" style="display: block; border: 0px;" alt=""/>
                                        @else
                                            <img src="{{ url('/assets/images/putsamer_large.png') }}" width="420px" style="display: block; border: 0px;" alt="...">
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 0px 35px 20px 35px; background-color: #ffffff;"
                        bgcolor="#ffffff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="max-width:600px;">
                            <tr>
                                <td align="center"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 0px;">
                                    <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" alt=""
                                         width="125" height="120" style="display: block; border: 0px;"/><br>
                                    <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                        Tack för din order!
                                    </h2>
                                </td>
                            </tr>
                            <tr>
                                <td align="left"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                    <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                        Nedan kommer en sammanställning av det du bokat.<br/>
                                        Du kommer också få en bekräftelse via SMS när ordern setts över, alternativt bli
                                        kontaktad för komplettering av uppgifter.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="padding-top: 20px;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td width="75%" align="left" bgcolor="#eeeeee"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                                Order-ID #
                                            </td>
                                            <td width="25%" align="left" bgcolor="#eeeeee"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;">
                                                {{ $mailData['order_id'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="75%" align="left"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 15px;">
                                                Reseersättning
                                            </td>
                                            <td width="25%" align="left"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 24px; padding-top: 15px;">
                                                {{ $mailData['distance'] }} kr
                                            </td>
                                        </tr>

                                        @if ($mailData['discounted'] > 0)
                                            <tr>
                                                <td width="75%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 15px;">
                                                    Rabatt
                                                </td>
                                                <td width="25%" align="left"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 24px; padding-top: 15px;">
                                                    {{ $mailData['discounted'] }} kr
                                                </td>
                                            </tr>
                                        @endif

                                        @foreach ($mailData['categories'] as $category)
                                            @if ($category->customer_services)
                                                @foreach ($category->customer_services->where('order_id', $mailData['order_id'])->groupBy('category') as $customer_services)
                                                    <h1 style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 400; line-height: 24px;">{{ $category->display_name }}</h1>
                                                    @foreach ($mailData['u_categories'] as $u_category)
                                                        @if ($u_category->customer_services->where('order_id', $mailData['order_id'])->where('category', $category->id))
                                                            @foreach ($u_category->customer_services->where('order_id', $mailData['order_id'])->where('category', $category->id)->groupBy('u_category') as $services)
                                                                <h2 style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-weight: 400; line-height: 24px;">{{ $u_category->display_name }}</h2>
                                                                @foreach ($services as $service)
                                                                    <tr>
                                                                        <td width="75%" align="left"
                                                                            style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 16px;">
                                                                            {{ $service->data_service->display_name }}
                                                                        </td>
                                                                        <td width="25%" align="left"
                                                                            style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 16px;">
                                                                            {{ $service->quantity }} st
                                                                                                     (à {{ request()->get_order->customer_type === 1 ? $service->price : $service->price_full }}
                                                                                                     kr)
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="padding-top: 20px;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td width="75%" align="left"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                SUMMA
                                            </td>
                                            <td width="25%" align="left"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                {{ $mailData['sum'] }} kr
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <tr>
                    <td align="center" height="100%" valign="top" width="100%"
                        style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="max-width:660px;">
                            <tr>
                                <td align="center" valign="top" style="font-size:0;">
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">

                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                               style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Adress</p>
                                                    <p>{{ $mailData['address'] }}<br>{{ $mailData['city'] }}</p>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                               style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Datum och tid</p>
                                                    <p>{{ $mailData['date'] }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="max-width:600px;">
                            <tr>
                                <td align="center">
                                    <a href="https://putsamer.se">
                                        <img src="{{ url('/assets/images/putsamer.png') }}" alt=""
                                             style="display: block; border: 0px; max-height: 50px; max-width: 100px"/>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>

</html>
