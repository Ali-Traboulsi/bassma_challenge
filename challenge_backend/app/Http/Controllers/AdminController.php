<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\RegisterAuthRequest;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{

    public function __construct(){
        $this->middleware('assign.guard:admins');
    }

    public $loginAfterRegistration = true;


    protected function retrieveToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('admins')->factory()->getTTL() * 60
        ];

    }

    public function register(RegisterAuthRequest $request) {

        try {

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|min:6',
                'g-recaptcha-response' => 'required|captcha'
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];



            $admin = new Admin();
            $admin->fill($data);
            $admin->save();


           Auth::guard('admins')->login($admin, true);

            return response()->json([
                'success' => true,
                'message' => 'Admin Registered Successfully',
                'data' => $admin,
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


            if (Auth::guard('admins')->check()) {
                return response()->json([
                    'message' => 'Admin already logged In'
                ]);
            }

            if (! $token = auth('admins')->attempt($input)){
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid Credentials: email or password'
                ]);
            }

            $admin = Auth::guard('admins')->user();

            return response()->json([
                'success' => true,
                'message' => 'Admin Sign in Success',
                'admin' => $admin,
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

            Auth::guard('admins')->logout();

            return response()->json([
                'success' => true,
                'message' => 'Admin Logged out Success',
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

     public function create(Request $request){

        try{

        AdminController::create(array(
            //
        ));

        return response()->json([
            'error' => false,
            'message' => "The AdminController has been added successfully"
        ],201);

    }catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured",
                'errormessage' => $errorInfo
            ],500);
        }
    }


    public function retrieve(Request $request){
      try{
          $X = AdminController::paginate();
          return response()->json([
              'error'=>false,
              'X'=>$X
          ],200);
      }
      catch(\Illuminate\Database\QueryException $exception){
        $errorInfo = $exception->errorInfo;
        return response()->json([
            'error' => true,
            'message' => "Internal error occured"
        ],500);
      }

    }

    public function update(Request $request,$id){

       try{
           $X = AdminController::where('id', '=', $id)->first();
           //$X->name = $request['name'];
           $X->save();
           return response()->json([
            'error'=>false,
            'message'=>'The AdminController has been updated successfully',
            'X'=>$X
           ],200);
       }
      catch(\Illuminate\Database\QueryException $exception){
        $errorInfo = $exception->errorInfo;
        return response()->json([
            'error' => true,
            'message' => "Internal error occured"
        ],500);
       }
    }



}


