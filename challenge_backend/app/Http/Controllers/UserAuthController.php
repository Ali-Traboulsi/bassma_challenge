<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\False_;

class UserAuthController extends Controller
{

    protected function retrieveToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('users')->factory()->getTTL() * 60
        ];

    }

    public function register(RegisterAuthRequest $request) {

        try {

            // if a spammer tries to send data then we catch and return false
            if ($request->get("name") || $request->get('email') || $request->get("password")) {
                return false;
            }

            $data = [
                'name' => $request->nameadmin,
                'email' => $request->emailadmin,
                'password' => $request->passwordadmin,
            ];

            $user = new User();
            $user->fill($data);
            $user->save();


            Auth::guard('users')->login($user, true);

            return response()->json([
                'success' => true,
                'message' => 'User Registered Successfully',
                'data' => $user,
//                'token' => $token
            ], 201);

        }  catch(\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured"
            ], 500);

        }
    }

    public function login(Request $request) {

        $input = $request->only('email', 'password');

        try{

            if (Auth::guard('users')->check()) {
                return response()->json([
                    'message' => 'User already logged In'
                ]);
            }

            if (! $token = auth('users')->attempt($input)){
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid Credentials: email or password'
                ]);
            }

            $user = Auth::guard('users')->user();

            return response()->json([
                'success' => true,
                'message' => 'User Sign in Success',
                'admin' => $user,
                'token' => $this->retrieveToken($token),
            ]);

        }catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured",
                'errormessage' => $errorInfo
            ],500);
        }
    }

    public function logout(Request $request) {
        try {

            $this->validate($request, [
                'token' => 'required'
            ]);

            Auth::guard('users')->logout();

            return response()->json([
                'success' => true,
                'message' => 'User Logged out Success',
            ]);

        } catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured",
                'errormessage' => $errorInfo
            ],500);
        }
    }

}
