@extends('layouts.app')

@section('content')
    <div class="col-12">

        <div class="container enroll-main">

            <div class="enroll-main-title text-center">
                <h2>Select Enrollment Type</h2>
            </div>

            <div class="row">

                <div class="col-lg-4 text-left">
                    <div class="enroll-box">
                        <h5>Enroll as</h5>
                        <h6>RETAIL CUSTOMER</h6>
                        <br/>
                        <p>Manage your orders, shipping statuses and keep track of your products by creating a free account!</p>
                        <div class="inner-content">
                        </div>

                        <div class="login-actions">
                            <button class="btn button btn-dark btn-large" onclick="enroll('rc')">Get Started</button>
                        </div>
                    </div>

                </div>


                <div class="col-lg-4 text-left">
                    <div class="enroll-box">
                        <h5>Enroll as</h5>
                        <h6>PREFERRED CUSTOMER</h6>
                        <br/>
                        <p>Creating an account is easy, and as an Ultrra preferred customer you’ll enjoy many benefits including:</p>
                        <div class="inner-content">
                            <ul>
                                <li>Lifetime access to Ultrra products at wholesale pricing.</li>
                                <li>Personal retail site for shopping and tracking orders.</li>
                                <li>Get FREE Ultrra products through the Ultrra Member Loyalty promotions.</li>
                            </ul>
                        </div>

                        <div class="login-actions">
                            <button class="btn button btn-primary btn-large" onclick="enroll('pc')">Get Started</button>
                        </div>

                    </div>

                </div>


                <div class="col-lg-4 text-left">

                    <div class="enroll-box">
                        <h5>Enroll as</h5>
                        <h6>DISTRIBUTOR</h6>
                        <br/>
                        <p>Creating an account is easy, and as an Ultrra distributor you’ll enjoy many benefits including:</p>
                        <div class="inner-content">
                            <ul>
                                <li>Build an online business and work from home on your own time.</li>
                                <li>Your own personalized replicated business website for customers.</li>
                                <li>Earn income on all orders and reorders of customers who purchase through your personalized website.</li>
                                <li>The Ultrra OS (Office System) for managing your business, tracking customers, and managing  earned profits.</li>
                            </ul>
                        </div>

                        <div class="login-actions">
                            <button class="btn button btn-success btn-large" onclick="enroll('dc')">Get Started</button>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    </div>
    <style>
        h2
        {
            font-size: 2rem !important;
        }
        .enroll-box h5
        {
            font-size: 15px !important;
        }
        .enroll-box h6
        {
            font-size: 18px !important;
        }
        .enroll-box p
        {
            font-size: 15px !important;
            line-height: 18px !important;
            color: #717171 !important;
            text-align: center !important;
        }
        .enroll-box .login-actions button
        {
            font-family: "Montserrat-Regular";
            font-size: 17px;
            text-transform: uppercase;
            padding: 10px 25px;
            border: 0!important;
        }
        ul li:before
        {
            background: transparent !important;
        }
        .enroll-box .login-actions .btn-success
        {
            background-image: linear-gradient(-90deg,#dad12e,#76be1e);
        }
        p
        {
            margin-bottom: 1rem !important;
        }
    </style>
@endsection
@push('js')
    <script>
        function enroll(usertype)
        {
            localStorage.setItem('usertype', usertype);
            localStorage.setItem('country', 233);
            axios.post('/cart', {new_cart: true, usertype:usertype}).then((response) => {
                localStorage.setItem('cart', JSON.stringify(response.data.data));
                window.location.href = '{{url("/www/products")}}' + window.location.search;
            });
        }
    </script>
@endpush