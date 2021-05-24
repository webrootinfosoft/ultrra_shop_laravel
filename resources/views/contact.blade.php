@extends('layouts.app')

@section('content')
    <div class="subheader normal-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-6 align-self-center p-relative">
                    <div class="subheader-text">
                        <div class="page-title">
                            <h1 class="text-custom-white fw-600 text-left">Contact Us</h1>
                            <ul class="custom-flex breadcrumb">
                                <li>
                                    <a href="{{url('/')}}" class="td-none text-custom-white">Home</a>
                                </li>
                                <li class="text-custom-white active">
                                    Contact Us
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="section-padding contact-sec bg-custom-white">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section-header">
                        <div class="section-heading">
                            <h3 class="text-brown fw-700"><span class="fw-100 text-purple">Contact</span> Us</h3>
                            <p class="fs-16">Don’t hesitate to contact us if you have any questions or comments. Any feedback is a plus for us!</p>
                        </div>
                    </div>
                    <div class="contact-info-wrap section-padding">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="contact-info-box bg-light-blue-gr section-padding">
                                    <div class="contact-info-text text-center">
                                        <h5 class=" fw-700 text-custom-white">HOUSTON</h5>
                                        <p class="text-custom-white no-margin">10101 Southwest Freeway 4th Floor Houston, TX 77074 U.S.A.<br/>
                                            Tel: +1.888.981.1711<br/>
                                            Email: cc@ultrra.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="contact-info-box section-padding">
                                    <div class="contact-info-text text-center">
                                        <h5 class="fw-700">DUBAI</h5>
                                        <p class="no-margin">Jafza One Tower Jebel Ali Free Zone Dubai 18 United Arab Emirates<br/>
                                            Tel: +1.888.981.1711<br/>
                                            Email: cc@ultrra.com</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="contact-info-box section-padding">
                                    <div class="contact-info-text text-center">
                                        <h5 class="fw-700">MONTERREY</h5>
                                        <p class="no-margin">Cumbres 2 Sector Monterrey, Nuevo Leon 64610 Mexico<br/>
                                            Tel: +52.81.2723.0640<br/>
                                            Email: mx@ultrra.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form class="form-style-2 style-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control form-control-custom" placeholder="Name *" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-custom" placeholder="Email *" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="number" name="phone" class="form-control form-control-custom" placeholder="Phone No." />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="subject" class="form-control form-control-custom" placeholder="Subject" />
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea name="description" class="form-control form-control-custom" placeholder="Message" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-right">
                                        <button type="button" class="theme-btn btn-style-3" onClick={this.submit}><span class="btn-text">Submit</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        h1
        {
            font-size: 2.5rem !important;
            margin: 0 0 20px !important;
            font-family: "Lato",sans-serif !important;
        }
    </style>
@endsection
@push('js')
    <script>

    </script>
@endpush