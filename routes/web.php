<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('lang/{locale}', 'LanguageController@lang');

Route::group(['middleware' => 'language'], function () {
    Route::get('/', function () {
        return view('home');
    });

    Route::get('/{username?}', function ($username = null) {
        if (!is_null($username) && $username != 'www')
        {
            return redirect('/?username=' . $username);
        }
        return redirect('/');
    });
    Route::get('r/{username?}', function ($username = null) {
        if (!is_null($username) && $username != 'www')
        {
            return redirect('www/products/?username=' . $username . '&usertype=rc');
        }
        return redirect('/');
    });
    Route::get('d/{username?}', function ($username = null) {
        if (!is_null($username) && $username != 'www')
        {
            return redirect('www/enrollment/?username=' . $username);
        }
        return redirect('/');
    });
    Route::group(['prefix' => 'www'], function () {
        Auth::routes();
        Route::get('/invoice/{id}', 'HomeController@invoice');
        Route::get('/about', function () {
            return view('about');
        });
        Route::get('/opportunity', function () {
            return view('opportunity');
        });
        Route::get('/testimonial', function () {
            return view('testimonial');
        });
        Route::get('/research', function () {
            return view('research');
        });
        Route::get('/contact', function () {
            return view('contact');
        });
        Route::post('/contact-us', 'HomeController@contactUsSubmit');
        Route::get('/nutritional', function () {
            return view('nutritional');
        });
        Route::get('/beverage', function () {
            return view('beverage');
        });
        Route::get('/rare-oil/{id}', function ($id) {
            return view('rare-oil', compact('id'));
        });
        Route::get('/element', function () {
            return view('element');
        });
        Route::get('/supplements/{product_id}', function ($product_id) {
            return view('product-details2', compact('product_id'));
        });
        Route::get('/oils/{product_id}', function ($product_id) {
            return view('product-details3', compact('product_id'));
        });
        Route::get('/create-account', function () {
            if (auth()->check())
            {
                return redirect()->back();
            }
            return view('cart.create-account');
        });
        Route::get('/enrollment', function () {
            auth()->logout();
            return view('enrollment');
        });
        Route::get('/products', function () {
            return view('cart.products');
        });
        Route::get('/shipping-address', function () {
            return view('cart.shipping-address');
        })->middleware('auth');
        Route::get('/review', function () {
            return view('cart.review');
        });
        Route::get('/post-login/{token}', function ($token) {
            return view('post-login', compact('token'));
        });
        Route::get('/login-by-id/{id}', function ($id) {
            auth()->loginUsingId($id);
            if (!request()->ajax())
            {
                return redirect('/www/products');
            }
        });
        Route::get('/check-username/{id?}', function ($id = null) {
            $request = request()->all();

            $users = \App\User::where('username', $request['user']['username'])->where(function ($q) use ($id) {
                if (!is_null($id))
                {
                    $q->where('id', '!=', $id);
                }
            })->count();
            return $users == 0 ? '"true"' : '"Username has been already taken"';
        });
        Route::get('/check-email/{id?}', function ($id = null) {
            $request = request()->all();

            $users = \App\User::where('email', $request['user']['email'])->where(function ($q) use ($id) {
                if (!is_null($id))
                {
                    $q->where('id', '!=', $id);
                }
            })->count();
            return $users == 0 ? '"true"' : '"Email has been already taken"';
        });
    });

    Route::get('/home', 'HomeController@index')->name('home');
});