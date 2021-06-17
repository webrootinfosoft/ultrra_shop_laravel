@extends('layouts.app')

@section('content')
    <div style="background: #8ec41a">
        <div class="col-md-10 mr-auto ml-auto" style="padding: 27px 0;">
            <h1 class="text-white" style="text-align: center; letter-spacing: 6px; font-size: 55px; font-weight: 700; margin-bottom: 0">REAL PEOPLE, REAL RESULTS</h1>
        </div>
    </div>
    <div>
        <div class="col-md-10 mr-auto ml-auto">
            <br/>
            <br/>
            <div class="btn-group float-left" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-light" onclick="displayImage('all')">All</button>
                <button type="button" class="btn btn-light" onclick="displayImage('detox')">Detox</button>
                <button type="button" class="btn btn-light" onclick="displayImage('fitness')">Fitness</button>
                <button type="button" class="btn btn-light" onclick="displayImage('weightloss')">Weight Loss</button>
            </div>

            <div>
                <br/>
                <br/>
                <br/>
                <br/>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-56.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-55.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-54.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-53.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-39.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-37.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-34.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-31.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-27.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-19.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-18.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-16.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-15.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-14.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-13.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-8.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-5.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-3.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-2.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-customer-1.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-pedro.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-nicole.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-hanz-dad.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-hanh.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-araceli-s.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image detox">
                    <img src="{{asset('/images/ultrra-araceli-f.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-51.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-47.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-45.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-44.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-40.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-38.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-28.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-25.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-customer-24.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-yuli.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-shoushig.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-ramon.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-paola.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-neetha.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-leonardo.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-king.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-jonathan.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-joel.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-joel-f.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-jeff.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-hanz.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-emily.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-elmo.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-devon.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-deniz.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-bill.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-ben.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-abigail.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness weightloss">
                    <img src="{{asset('/images/ultrra-ana.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-52.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-46.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-36.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-35.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-33.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-32.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-30.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-29.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-21.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-20.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-12.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-11.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-9.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-10.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-7.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-customer-6.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-stephanie.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-freyda.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image fitness">
                    <img src="{{asset('/images/ultrra-freyda.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-50.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-49.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-48.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-49.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-43.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-42.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-41.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-23.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-22.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-17.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-customer-4.jpg')}}" width="400" height="284"/>
                </div>
                <div class="testimonial-tabcontent-image weightloss">
                    <img src="{{asset('/images/ultrra-sana.jpg')}}" width="400" height="284"/>
                </div>
            </div>
        </div>
    </div>
    <div style="background: #3a3a3a">
        <div class="col-md-10 mr-auto ml-auto" style="padding: 27px 0;">
            <h1 class="text-white" style="text-align: center; letter-spacing: 6px; font-size: 55px; font-weight: 700; margin-bottom: 0"><strong>TRY THE 15/15 CHALLENGE TODAY!</strong></h1>
        </div>
    </div>
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
        function displayImage(tag)
        {
            if (tag == 'all')
            {
                $('.detox').css('display', 'inline-block');
                $('.fitness').css('display', 'inline-block');
                $('.weightloss').css('display', 'inline-block');
            }
            else
            {
                $('.testimonial-tabcontent-image').each(function (index, element) {
                    if ($(element).hasClass(tag)) {
                        console.log('if');
                        $(element).css('display', 'inline-block');
                    }
                    else {
                        console.log('else');
                        $(element).css('display', 'none');
                    }
                })
            }
        }
    </script>
@endpush