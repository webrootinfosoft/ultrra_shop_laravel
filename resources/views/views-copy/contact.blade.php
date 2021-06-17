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
                            <form id="contact-us-form" class="form-style-2 style-2" method="POST" action="{{url('/www/contact-us')}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" id="name" name="name" class="form-control form-control-custom" placeholder="Name *" value="{{old('name')}}" required />
                                            @if ($errors->has('name'))
                                                <label class="error" for="name">{{ $errors->first('name') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" id="email" name="email" class="form-control form-control-custom" placeholder="Email *" value="{{old('email')}}" required />
                                            @if ($errors->has('email'))
                                                <label class="error" for="email">{{ $errors->first('email') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="number" name="phone" class="form-control form-control-custom" placeholder="Phone No. *" value="{{old('phone')}}" required />
                                            @if ($errors->has('phone'))
                                                <label class="error" for="phone">{{ $errors->first('phone') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="subject" class="form-control form-control-custom" placeholder="Subject *" value="{{old('subject')}}" required />
                                            @if ($errors->has('subject'))
                                                <label class="error" for="subject">{{ $errors->first('subject') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control form-control-custom" placeholder="Message *" rows="5" required >{{old('message')}}</textarea>
                                            @if ($errors->has('message'))
                                                <label class="error" for="message">{{ $errors->first('message') }}</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-right">
                                        <button type="submit" class="theme-btn btn-style-3"><span class="btn-text">Submit</span></button>
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
        window.addEventListener('load', function() {
            $.validator.addMethod("alpha", function(value, element) {
                return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
            }, 'Should only contain letters and spaces');

            $('#contact-us-form').validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                rules: {
                    "name": {
                        required: true,
                        alpha: true,
                    },
                    "email": {
                        required: true,
                        email: true
                    },
                    "phone": {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    "subject": {
                        required: true,
                    },
                    "message": {
                        required: true,
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.attr("type") == "checkbox" || element.attr("type") == "radio")
                    {
                        if (element.parent().siblings().length > 0)
                        {
                            error.insertAfter(element.parent().siblings().last());
                        }
                        else
                        {
                            error.insertAfter(element.parent());
                        }
                    }
                    else
                    {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form, event) {
                    form.submit();
                }
            });

            if ('{{session()->has('message')}}' == 1)
            {
                console.log('{{session()->get('message')}}');
                Swal.fire({icon: 'success', text: '{{session()->get('message')}}'});
            }
        });

    </script>
@endpush