<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            if (User::where('email', $request->email)->first())
            {
                return response()->json([
                    'status_code' => 200,
                    'user' => null,
                    'message' => 'Email already exist',
                    'result' => false,
                ]);
            }

            $data = $request->all();
            $data['password'] = Hash::make($request->password);

            $user = User::create($data);
            $user = User::find($user->id);

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status_code' => 200,
                'token' => [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ],
                'user' => $user,
                'result' => true,
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
                'result' => false
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);

            if (filter_var($request->username, FILTER_VALIDATE_EMAIL))
            {
                $credentials = [
                    'email' => $request->username,
                    'password' => $request->password
                ];
            }
            else
            {
                $credentials = request(['username', 'password']);
            }

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized',
                    'error' => 'Wrong Credentials'
                ]);
            }
            $user = User::with('sponsor', 'addresses', 'carts')->where(function ($q) use ($request) {
                if (filter_var($request->username, FILTER_VALIDATE_EMAIL))
                {
                    $q->where('email', $request->username);
                }
                else
                {
                    $q->where('username', $request->username);
                }
            })->first();

            if ( ! Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status_code' => 200,
                'token' => [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ],
                'user' => $user,
                'result' => true,
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
                'result' => false
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->where('id', auth()->user()->currentAccessToken()->id)->delete();
            return response()->json(['status_code' => 200, 'message' => 'Logout', 'result' => true]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
                'result' => false
            ]);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = User::with('sponsor', 'addresses', 'carts', 'country', 'state')->find(auth()->id());
            return response()->json(['status_code' => 200, 'user' => $user, 'message' => 'Success', 'result' => true]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
                'result' => false
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();
        if (Hash::check($request->old_password, $user->password))
        {
            User::find($user->id)->update(['password' => Hash::make($request->new_password)]);
        }

        return response()->json(['data' => [], 'status' => 200, 'message' => 'password changed']);
    }

    public function changeUsername(Request $request)
    {
//        $user = auth()->user();
//        if (!User::where('username', $request->new_username)->first())
//        {
//            User::find($user->id)->update(['username' => $request->new_username]);
//            return response()->json(['data' => ['code' => 1, 'username' => $request->new_username], 'message' => 'Username Changed', 'status' => 200]);
//        }
//        else
//        {
//            return response()->json(['data' => ['code' => 0], 'message' => 'Username Already Exist', 'status' => 200]);
//        }
    }

    public function loginById($id)
    {
        if (auth()->loginUsingId($id))
        {
            $user = auth()->user();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status_code' => 200,
                'token' => [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                ],
                'user' => $user,
                'result' => true,
            ]);
        }
        else
        {
            return response()->json([
                'status_code' => 500,
                'result' => false,
                'message' => 'error in login'
            ]);
        }

    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $user = User::find($id)->update(['password' => Hash::make($request->password)]);

        return response()->json(['data' => $user, 'status' => 200]);
    }
}
