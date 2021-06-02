<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ultrra') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/wieldly.css') }}" rel="stylesheet">
    <style type="text/css">
        /*@font-face{font-family:Montserrat-Regular;src:url(/fonts/montserrat/Montserrat-Regular_1.otf)}*/
        /*body{font-family:Montserrat-Regular;font-weight:600}@page{size:A4;margin:0}*/

        /*@media print*/
        /*{*/
            /*body,html{max-width:181mm;height:297mm;margin-bottom:0}*/
            /*.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:4px;line-height:1.42857143;vertical-align:top;border-top:1px solid #ddd}*/
            /*.default-page{width:1170px;margin:auto}*/
            /*.print-invoice-row{display:none}*/
            /*.table>tbody>tr>td{padding:3px;vertical-align:top;border-top:1px solid #ddd}*/
            /*table{margin-bottom:-25px}*/
            /*.table.table.table-bordered-no{margin-bottom:0;padding:0}*/
            /*address{margin-bottom:0;font-style:normal;line-height:1.42857143}*/
            /*table.table.table-bordered{margin-top:0;margin-bottom:0}*/
            /*img{width:258px!important}td{font-size:10px;padding-top:0}*/
            /*.row.invoice_footer{margin-top:0}*/
            /*.row.invoice_footer{margin-top:0!important}*/
            /*.table.table-bordered-inner>tbody>tr>td{border:0;border-top:1px solid #9e9e9e}*/
            /*a[href]:after{content:none!important}*/
        /*}*/

        /*.row.invoice_footer{margin-top:0!important}*/
        /*.billing{width:60%}.shipping{width:60%}*/
        /*.top-row{padding-top:20px;padding-bottom:20px}*/
        /*.top-left-data{padding-left:23px}*/
        /*.top-center-data{text-align:center}*/
        /*.top-center-data img{width:70%}*/
        /*.top-right-data{text-align:right}*/
        /*footer{display:none}*/
        /*.bottom-text{margin-bottom:30px}*/
        /*.bottom-text p{margin:0;color:#545353}*/
        /*td{padding:8px!important}*/





        /*.table th, .table td*/
        /*{vertical-align: middle;}*/


        /*img*/
        /*{max-width: 100%;}*/
        /*p*/
        /*{margin: 0px;}*/

        /*p,li,th,td,a*/
        /*{color: #000000;font-size: 16px;font-weight: normal;line-height: 25px;}*/

        /*.table th, .table td*/
        /*{padding: 10px !important;}*/

        /*.invoice-thankyou p a*/
        /*{color: #03A9F4;}*/

        /*.invoice-details .table thead tr td*/
        /*{font-weight: 600;background-color: #e8e8e8;}*/

        /*.invoice-section*/
        /*{padding-top: 50px;}*/

        /*.header-top-right-main*/
        /*{padding-bottom: 15px;border-bottom: 5px solid #e8e8e8;}*/

        /*.invoice-p-info*/
        /*{border-bottom: 2px solid #e8e8e8;padding-top: 20px;margin-bottom: 10px;}*/


        /*.invoice-p-info .table th,*/
        /*.invoice-p-info .table td,*/
        /*.invoice-info .table td*/
        /*{border: 0px;}*/

        /*.invoice-detail-bottom*/
        /*{}*/
        /*.invoice-detail-bottom p*/
        /*{margin-bottom: 10px;}*/

        /*.invoice-details .table tfoot th,*/
        /*.invoice-details .table tfoot td*/
        /*{border: 0px !important;}*/

        /*.invoice-thankyou*/
        /*{margin-top: 30px;padding: 15px 0px;}*/

        /*.invoice-bottom*/
        /*{padding: 15px 0px;border-top: 5px solid #e8e8e8;}*/
        /*.invoice-bottom ul*/
        /*{margin: 0px;padding: 0px;display: flex;flex-wrap: wrap;flex-direction: row;justify-content: center;}*/
        /*.invoice-bottom ul li*/
        /*{list-style: none;position: relative;}*/
        /*.invoice-bottom ul li a*/
        /*{font-size: 18px;font-weight: 600;display: block;margin: 0px 50px;position: relative;}*/
        /*.invoice-bottom ul li:after*/
        /*{content: '';width: 2px;height: 100%;position: absolute;top: 0;right: 0;background-color: #000;}*/
        /*.invoice-bottom ul li:last-child:after*/
        /*{display: none;}*/

        @font-face {
            font-family: Montserrat-Regular;
            src: url('/fonts/montserrat/Montserrat-Regular.ttf');
        }
        .main-div
        {
            font-family: Montserrat-Regular !important;
            font-weight: 400 !important;
            font-size: 0.95rem !important;
            color: #545454 !important;
        }
        .main-div b
        {
            font-weight: 500 !important;
        }
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            html, body {
                max-width: 181mm;
                height: 297mm;
                margin-bottom: 0px;
            }

            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
                padding: 4px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid #ddd;
            }

            .default-page {
                width: 1170px;
                margin: auto
            }

            .print-invoice-row {
                display: none
            }

            .table > tbody > tr > td {
                padding: 3px;
                vertical-align: top;
                border-top: 1px solid #ddd;
            }

            table {
                margin-bottom: -25px;
            }

            .table.table.table-bordered-no {
                margin-bottom: 0;
                padding: 0;
            }

            address {
                margin-bottom: 0px;
                font-style: normal;
                line-height: 1.42857143;
            }

            table.table.table-bordered {
                margin-top: 0px;
                margin-bottom: 0px;
            }

            img {
                width: 258px !important;
            }

            td {
                font-size: 10px;
                padding-top: 0px;
            }

            .row.invoice_footer {
                margin-top: 0px;
            }

            /* ... the rest of the rules ... */
            .row.invoice_footer {
                margin-top: 0px !important;
            }

            .table.table-bordered-inner > tbody > tr > td {
                border: 0;
                border-top: 1px solid #9E9E9E;
            }

            a[href]:after {
                content: none !important;
            }
        }

        .row.invoice_footer {
            margin-top: 0px !important;
        }

        .billing {
            width: 60%;
        }

        .shipping {
            width: 60%;
        }

        .top-row {
            padding-top: 20px;
            padding-bottom: 20px
        }

        .top-left-data {
            padding-left: 23px;
        }

        .top-center-data {
            text-align: center;
        }

        .top-center-data img {
            width: 70%
        }

        .top-right-data {
            text-align: right;
        }

        .bottom-text {
            margin-bottom: 30px;
        }

        .bottom-text p {
            margin: 0px;
            color: #545353;
        }
        td
        {
            padding: 8px !important;
        }
        p
        {
            margin-bottom: 0 !important;
        }
    </style>
</head>
<body style="overflow-y: scroll">
<div id="dt-root">
    <!-- Grid -->


    {{--<div class="invoice-section">--}}
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-12">--}}

                    {{--<div class="invoice-top">--}}

                        {{--<div class="row">--}}
                            {{--<div class="col-lg-3">--}}
                                {{--<div class="header-top-left-main">--}}
                                    {{--<div class="header-top-logo">--}}
                                        {{--<img src="{{asset('images/logo.png')}}" alt="Ultrra">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-lg-9">--}}
                                {{--<div class="header-top-right-main">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-6">--}}
                                            {{--<div class="header-top-left">--}}
                                                {{--<p><b>Sponsor Username: {{$order->sponsor->username}}</b></p>--}}
                                                {{--<p>Sponsor Name: {{$order->sponsor->name}}</p>--}}
                                                {{--<p>Sponsor Phone: {{$order->sponsor->phone}}</p>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-6">--}}
                                            {{--<div class="header-top-right text-right">--}}
                                                {{--<p><b>Order Type: {{$order->user->usertype}}</b></p>--}}
                                                {{--<p><b>Order #: {{$order->id}}</b></p>--}}
                                                {{--<p>Order Date: {{date('m/d/Y H:i:s', strtotime($order->created_at))}}</p>--}}
                                                {{--<p>Preferred Language: English</p>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                    {{--</div>--}}


                    {{--<div class="invoice-p-info">--}}
                        {{--<table class="table" style="margin-bottom: 0 !important;">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th width="10%">Account Info</th>--}}
                                {{--<th style="padding-bottom: 0 !important;">{{$order->billing_firstname}}</th>--}}
                                {{--<th width="10%">Ship To</th>--}}
                                {{--<th style="padding-bottom: 0 !important;">{{$order->shipping_firstname}}</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                                {{--<td></td>--}}
                                {{--<td style="padding-top: 0 !important;">--}}
                                    {{--{{$order->billing_address_1}}<br/>--}}
                                    {{--{{$order->billing_address_2}}<br/>--}}
                                    {{--{{$order->billing_city}}, {{$order->billingState->name}}, {{$order->billing_postcode}}<br/>--}}
                                    {{--{{$order->billingCountry->name}}<br/>--}}
                                    {{--{{$order->mobile}}<br/>--}}
                                    {{--{{$order->email}}<br/>--}}
                                {{--</td>--}}
                                {{--<td></td>--}}
                                {{--<td style="padding-top: 0 !important;">--}}
                                    {{--{{$order->shipping_address_1}}<br/>--}}
                                    {{--{{$order->shipping_address_2}}<br/>--}}
                                    {{--{{$order->shipping_city}}, {{$order->shippingState->name}}, {{$order->shipping_postcode}}<br/>--}}
                                    {{--{{$order->shippingCountry->name}}<br/>--}}
                                    {{--{{$order->shipping_mobile}}<br/>--}}
                                    {{--{{$order->shipping_email}}<br/>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}


                    {{--<div class="invoice-info">--}}
                        {{--<table class="table" style="margin-bottom: 0 !important;">--}}
                            {{--<tbody>--}}
                            {{--<tr>--}}
                                {{--<td style="margin-bottom: 0 !important;"><b>Username:</b> {{$order->user->username}}</td>--}}
                                {{--<td style="margin-bottom: 0 !important;"><b>QV:</b> {{$order->total_qv}}</td>--}}
                                {{--<td class="text-right" style="margin-bottom: 0 !important;"><b>Shipping Method:</b> {{$order->shipping_method}}</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                                {{--<td><b>First Order:</b> {{$order->is_first_order == 1 ? 'Yes' : 'No'}}</td>--}}
                                {{--<td><b>BV:</b> {{$order->orderProducts->sum('bv')}}</td>--}}
                                {{--<td class="text-right"><b>Order Status:</b> {{$order->orderStatus->name}}</td>--}}
                            {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}

                    {{--<div class="invoice-details">--}}
                        {{--<table class="table">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<td width="40%" class="text-left">Description</td>--}}
                                {{--<td width="15%" class="text-center">Qty</td>--}}
                                {{--<td width="15%" class="text-center">QV</td>--}}
                                {{--<td width="15%" class="text-center">Unit Price</td>--}}
                                {{--<td width="15%" class="text-center">Total</td>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody style="border-bottom: 1px solid lightgray">--}}
                            {{--@foreach($order->orderProducts as $order_product)--}}
                                {{--<tr>--}}
                                    {{--<td>--}}
                                        {{--<b>{{$order_product->name}}</b>--}}
                                        {{--<br/>--}}
                                        {{--<small>--}}
                                            {{--@if($order_product->orderProductComponents->count() > 0)--}}
                                                {{--@foreach($order_product->orderProductComponents as $order_product_component)--}}
                                                    {{--{{ $order_product_component->quantity.' '.$order_product_component->name }} <br>--}}
                                                {{--@endforeach--}}
                                            {{--@endif--}}
                                        {{--</small>--}}
                                    {{--</td>--}}
                                    {{--<td class="text-center">{{$order_product->quantity}}</td>--}}
                                    {{--<td class="text-center">{{$order_product->qv}}</td>--}}
                                    {{--<td class="text-center">${{$order_product->price}}</td>--}}
                                    {{--<td class="text-center">${{$order_product->total}}</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--</tbody>--}}
                        {{--<!-- <tfoot>--}}
                        {{--<tr>--}}
                            {{--<th colspan="2"><b>Notes:</b>{{$order->note}}</th>--}}
                            {{--<td colspan="2" class="text-right">Subtotal</td>--}}
                            {{--<td class="text-right">${{$order->sub_total}} USD</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th colspan="2">&nbsp;</th>--}}
                            {{--<td colspan="2" class="text-right">Shipping</td>--}}
                            {{--<td class="text-right">${{$order->shipping_price}} USD</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th colspan="2">&nbsp;</th>--}}
                            {{--<td colspan="2" class="text-right">Handling</td>--}}
                            {{--<td class="text-right">${{$order->handling_charges}} USD</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th colspan="2">&nbsp;</th>--}}
                            {{--<td colspan="2" class="text-right">Tax (X.XX%)</td>--}}
                            {{--<td class="text-right">${{$order->tax}} USD</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th colspan="2">--}}
                                {{--<b>--}}
                                    {{--Payment Method:--}}
                                    {{--@foreach($order->paymentMethods as $payment_method)--}}
                            {{--{{implode(' ', explode('_', $payment_method->payment_method))}} @if (!is_null($payment_method->card_number) && !empty($payment_method->card_number)) ({{$payment_method->card_number}}) @endif--}}
                        {{--@endforeach--}}
                                {{--</b>--}}
                            {{--</th>--}}
                            {{--<td colspan="2" class="text-right">Total</td>--}}
                            {{--<td class="text-right">${{$order->total}} USD</td>--}}
                        {{--</tr>--}}
                        {{--</tfoot> -->--}}
                        {{--</table>--}}
                        {{--<table class="table" style="border-bottom: 1px solid #e8e8e8;">--}}
                            {{--<tr>--}}
                                {{--<td width="70%">--}}
                                    {{--<table class="table">--}}
                                        {{--<tr>--}}
                                            {{--<td style="border: none;"><b>Notes: </b>{{$order->note}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td style="border: none;">&nbsp;</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td style="border: none;">&nbsp;</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td style="border: none;">&nbsp;</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td style="border: none;">--}}
                                                {{--<b>--}}
                                                    {{--Payment Method:--}}
                                                    {{--@foreach($order->paymentMethods as $payment_method)--}}
                                                        {{--{{implode(' ', explode('_', $payment_method->payment_method))}} @if (!is_null($payment_method->card_number) && !empty($payment_method->card_number)) ({{$payment_method->card_number}}) @endif--}}
                                                    {{--@endforeach--}}
                                                {{--</b>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                    {{--</table>--}}
                                {{--</td>--}}
                                {{--<td width="30%">--}}
                                    {{--<table class="table table-bordered table-bordered-inner text-right">--}}
                                        {{--<tr>--}}
                                            {{--<td><b>Subtotal</b></td>--}}
                                            {{--<td>${{$order->sub_total}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td><b>Shipping</b></td>--}}
                                            {{--<td>${{number_format((float)$order->shipping_price, 2, '.', '')}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td><b>Handling</b></td>--}}
                                            {{--<td>${{$order->handling_charges}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td>--}}
                                                {{--<b>--}}
                                                    {{--@if($order->sub_total == 0)--}}
                                                        {{--Tax ({{round(($order->tax)*100, 2)}}%)--}}
                                                    {{--@else--}}
                                                        {{--Tax ({{round(($order->tax/$order->sub_total)*100, 2)}}%)--}}
                                                    {{--@endif--}}
                                                {{--</b></td>--}}
                                            {{--<td>${{$order->tax}}</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td><b>Total</b></td>--}}
                                            {{--<td>${{$order->total}}</td>--}}
                                        {{--</tr>--}}
                                    {{--</table>--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                        {{--</table>--}}
                    {{--</div>--}}

                    {{--<div class="invoice-detail-bottom text-right">--}}
                        {{--<p>Order packed by: _________</p>--}}
                        {{--<p>Contents verified by: _________</p>--}}
                    {{--</div>--}}

                    {{--<div class="invoice-thankyou text-center">--}}
                        {{--<p>Thank you for your order!</p>--}}
                        {{--<p>Visit us on facebook and share your experience with us!</p>--}}
                        {{--<p><a href="https://www.facebook.com/ultrraofficial" target="_blank">www.facebook.com/ultrraofficial</a></p>--}}
                    {{--</div>--}}

                    {{--<div class="invoice-bottom">--}}
                        {{--<ul>--}}
                            {{--<li><a href="https://ultrra.com/">www.ultrra.com</a></li>--}}
                            {{--<li><a href="mailto:cc@ultrra.com">cc@ultrra.com</a></li>--}}
                            {{--<li><a href="tel:888.981.1711">888.981.1711</a></li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}

                    {{--<div class="row print-invoice-row">--}}
                        {{--<div class="col-md-6">--}}
                            {{--<a href="http://office.ultrra.com" class="btn btn-primary" style="float: left; margin-bottom: 20px;"><i class="fa fa-sign-in"></i>Click to login</a>--}}
                        {{--</div>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<button class="btn btn-primary" style="float: right; margin-bottom: 20px;" onclick="printInvoice()"><i class="fa fa-print"></i> Print Invoice</button>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}







    <!--------------------------------------------------------------------------------------------------------------------------->
    <!--OLD Invoice Template Starts-->
    <!--------------------------------------------------------------------------------------------------------------------------->


    <div class="container text-left">

        <div class="row">

            <!-- Grid Item -->
            <div class="col-12" style="margin-top: 70px">
                <br><br>
                <div class="row top-row">
                    <div class="col-4 top-left-data">
                        ORDER STATUS: {{ $order_status }}
                        <br><br>
                        ORDER # {{ $order->id }}
                        <br>
                        Date: {{ $order->created_at }}
                    </div>
                    <div class="col-4 top-center-data">
                        <img src="{{asset('images/logo.png')}}" alt="Ultrra" width="252px">
                    </div>
                    <div class="col-4 top-right-data">
                        SHIPPING STATUS: {{ $order->shippingStatus->name }}
                        <br><br>
                        Usertype: {{ $order->user ? $order->user->usertype : ($order->meta->user ? $order->meta->user->usertype : '')}}
                        <br>
                        Language : English
                    </div>
                </div>

                <table class="table table-bordered-no">
                    <thead>
                    <tr>
                        <td style="width: 50%;color: #03A9F4; border-top: none; text-align: left"><b>ACCOUNT INFORMATION</b></td>
                        <td style="width: 50%;color: #03A9F4; border-top: none; text-align: left"><b>SHIP TO</b></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <address class="billing text-left">
                                {{$order->firstname}} {{$order->lastname}}
                                <br/>
                                {{$order->address_1}}
                                <br/>
                                {{$order->address_2}}
                                <br/>
                                {{$order->city}}, {{$order->state ? $order->state->name : ''}}, {{$order->postcode}}
                                , {{$order->country ? $order->country->name : ''}}
                                <br/>
                                Telephone: {{$order->mobile}}
                                <br/>
                                Email: {{$order->email}}
                            </address>
                        </td>
                        <td>
                            <address class="shipping text-left">
                                {{$order->shipping_firstname}} {{$order->shipping_lastname}}
                                <br/>
                                {{$order->shipping_address_1}}
                                <br/>
                                {{$order->shipping_address_2}}
                                <br/>
                                {{$order->shipping_city}}, {{$order->shippingState ? $order->shippingState->name : ''}}
                                , {{$order->shipping_postcode}}
                                , {{$order->shippingCountry ? $order->shippingCountry->name : ''}}
                                <br/>
                                Telephone:
                                @if($order->shipping_mobile == '')
                                    {{$order->mobile}}
                                @else
                                    {{$order->shipping_mobile}}
                                @endif
                                <br/>
                                Email:
                                @if($order->shipping_email=='')
                                    {{$order->email}}
                                @else
                                    {{$order->shipping_email}}
                                @endif
                            </address>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td class="text-left"><b>Username : {{ $order->user ? $order->user->username : ($order->meta->user ? $order->meta->user->username : '')}}</b></td>
                        <td class="text-left"><b>Order QV : </b> {{ $order->total_qv }} </td>
                        <td class="text-left"><b>Shipping Method : </b> <span></span>{{ $order->shipping_method }}
                        </td>
                        <td class="text-left"><b>Sponsor Username : </b> {{ $order->sponsor ? $order->sponsor->username : '' }}
                        </td>
                    </tr>

                    <tr>
                        <td class="text-left"><b>First Order : </b> @if($order->is_first_order == 1) Yes @else No @endif</td>
                        <td class="text-left"><b>Order BV
                                : </b> @if($order->is_first_order == 1) {{ $order->total_qv/2 }} @else {{ $order->total_qv }} @endif
                        </td>
                        <td class="text-left"><b>Sponsor Name : </b> {{ $order->sponsor ? $order->sponsor->name : '' }}</td>
                        <td class="text-left"><b>Sponsor Phone : </b> {{ $order->sponsor ? $order->sponsor->phone : '' }}</td>
                    </tr>

                    </tbody>

                </table>
                <table class="table table-striped" style="border:1px solid lightgrey; word-break: break-all;">
                    <thead>
                    <tr>
                        <td class="text-left"><b>Product</b></td>
                        <!-- <td><b>Model</b></td> -->
                        <td class="text-center"><b>Quantity</b></td>
                        <td class="text-center"><b>Components</b></td>
                        <td class="text-right"><b>QV</b></td>
                        <td class="text-right"><b>Unit Price</b></td>
                        <td class="text-right"><b>Total</b></td>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    @foreach($products as $product)
                        <tr>
                            <td class="text-left">{{ $product['name'] }}</td>
                            <td class="text-center">{{ $product->quantity }}</td>
                            <td class="text-left">
                                @if($product->orderProductComponents->count() > 0)
                                    @foreach($product->orderProductComponents as $order_product_component)
                                        {{ $order_product_component->quantity.' '.$order_product_component->name }} <br>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-right">{{ $product->qv }}</td>
                            <td class="text-right">$ {{number_format((float)$product->price, 2, '.', '')}} USD</td>
                            <td class="text-right">
                                $ {{number_format((float)$product->price * $product->quantity, 2, '.', '')}} USD
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td colspan="3">
                            <table class="table table-bordered-inner">
                                <tr>
                                    <td style="border-left: none; border-right: none; text-align: left"><b>Comment</b></td>
                                    <td colspan="5" style="border-left: none; border-right: none; text-align: left"> {{$order->note}} </td>
                                </tr>

                                <tr>
                                    <td style="border-left: none; border-right: none; text-align: left;"><b>Payment Method:</b></td>
                                    <td class="text-capitalize" colspan="5" id="shipping_method" style="border-left: none; border-right: none; text-align: left;">
                                        @foreach($order->paymentMethods as $payment_method)
                                            {{implode(' ', explode('_', $payment_method->payment_method))}} @if (!is_null($payment_method->card_number) && !empty($payment_method->card_number))({{$payment_method->card_number}}) @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-left: none; border-right: none; text-align: left;">
                                        <br><br><br>
                                        <b>Order packed by: _________ <br>Contents verified by: _________</b>
                                    </td>
                                    <td colspan="5" style="border-left: none; border-right: none; text-align: left"> {{$order->comment}} </td>
                                </tr>
                            </table>
                        </td>

                        <td colspan="3">
                            <table class="table table-bordered  table-bordered-inner">
                                <tr>
                                    <td class="text-right" colspan="5"><b>Sub Total</b></td>
                                    <td class="text-right sub_total">
                                        $ {{ number_format((float)$order->sub_total, 2, '.', '') }} USD
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"><b>Direct Ship Rate</b>
                                    </td>
                                    <td class="text-right">
                                        $ {{ number_format((float)$order->shipping_price, 2, '.', '') }} USD
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"><b>Handling</b></td>
                                    <td class="text-right">
                                        $ {{ number_format((float)$order->handling_charges, 2, '.', '') }} USD
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"><b>Tax</b></td>
                                    <td class="text-right">$ {{ number_format((float)$order->tax, 2, '.', '') }}USD
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5"><b>Total</b></td>
                                    <td class="text-right sub_total">
                                        $ {{ number_format((float)$order->total, 2, '.', '') }} USD
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="row print-invoice-row">
                    <div class="col-md-6">
                        <a href="http://office.ultrra.com" class="btn btn-primary"
                           style="float: left; margin-bottom: 20px;"><i class="fa fa-sign-in"></i>Click to login</a>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" style="float: right; margin-bottom: 20px;"
                                onclick="printInvoice()"><i class="fa fa-print"></i> Print Invoice
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center bottom-text">
                        <p>Thank you for your order!</p>
                        <p>Be sure to visit us on facebook and share your experience with us!</p>
                        <p><a target="_blank"
                              href="https://facebook.com/ultrraofficial">www.facebook.com/ultrraofficial</a></p>
                        <p>
                            <a target="_blank" href="https://ultrra.com">www.ultrra.com</a> |
                            <a target="_blank" href="cc@ultrra.com">cc@ultrra.com</a> |
                            <a target="_blank" href="8889811711">888.981.1711</a>
                        </p>
                    </div>
                </div>

            </div>
            <!-- /grid item -->

        </div>
    </div>

<!-- /grid -->
</div>
</body>
<script>
    function printInvoice() {
        window.print();
    }
</script>
</html>
