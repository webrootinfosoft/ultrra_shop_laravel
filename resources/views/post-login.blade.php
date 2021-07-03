<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="text-center" style="padding-top: 22.5%">
    <i class="fa fa-spinner fa-spin fa-4x"></i>
</div>
</body>
<script>
    let encryptedString = '{{$token}}';
    console.log(encryptedString)
    let token = atob(encryptedString);
    console.log(token);
    localStorage.setItem('access_token', token);
    localStorage.setItem('login', true);
    window.addEventListener('load', function() {
        axios.defaults.headers.common['authorization'] = "Bearer " + JSON.parse(localStorage.getItem('access_token'));
        axios.post('/auth/me').then(response => {
            if (response.data.status_code === 200 && response.data.user !== null)
            {
                localStorage.setItem('user', JSON.stringify(response.data.user));
                localStorage.setItem('usertype', response.data.user.usertype);
                localStorage.setItem('country', JSON.stringify(response.data.user.country_id));
                localStorage.setItem('products_country', JSON.stringify(response.data.user.country_id));
                axios.get('/get-placement-info/' + response.data.user.id).then(response => {
                    if (response.data.data !== null)
                    {
                        localStorage.setItem('placement_info', JSON.stringify(response.data.data));
                    }
                });
                axios.get('/user-addresses').then(response => {
                    if (response.data.data.length > 0)
                    {
                        localStorage.setItem('address', JSON.stringify(response.data.data[0]));
                    }
                });
                axios.get('/user-orders', {params: {key: 1, column: 'id'}}).then(response => {
                    if (response.data.data.total > 0)
                    {
                        let user_data = JSON.parse(localStorage.getItem('user'));
                        axios.get('/user-cart/' + user_data.id).then(response => {
                            if (response.data.data !== null)
                            {
                                localStorage.setItem('cart', JSON.stringify(response.data.data));
                                window.location.href = '/www/login-by-id/' + user_data.id;
                            }
                            axios.post('/cart', {user_id: user_data.id, create_cart: 1}).then(response => {
                                axios.get('/user-cart/' + user_data.id).then(response => {
                                    if (response.data.data)
                                    {
                                        localStorage.setItem('cart', JSON.stringify(response.data.data));
                                        window.location.href = '/www/login-by-id/' + user_data.id;
                                    }
                                }).catch((error) => {
                                    if (error.response.status === 401)
                                    {
                                        localStorage.clear();
                                        window.location.reload();
                                    }
                                });
                            }).catch((error) => {
                                if (error.response.status === 401)
                                {
                                    localStorage.clear();
                                    window.location.reload();
                                }
                            });
                        }).catch((error) => {
                            if (error.response.status === 401)
                            {
                                localStorage.clear();
                                window.location.reload();
                            }
                        });
                    }
                    else
                    {
                        let user_data = JSON.parse(localStorage.getItem('user'));
                        window.location.href = '/www/login-by-id/' + user_data.id;
                    }
                });
            }
        });
    });

</script>
</html>