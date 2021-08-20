<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;


class CustomerController extends Controller
{

    public function getCustomerByNumber(Request $request)
    {

        try {

//            $items = $request->get('items');

            $items = $request->query("items");
            $customers = User::orderBy('name', 'asc')->paginate($items);
            $countCustomers = User::count();

            return response()->json([
                'error' => false,
                'customers' => $customers,
                'customerNum' => $countCustomers
            ], 201);
        } catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured"
            ], 500);
        }
    }


    public function retrieve(Request $request)
    {
        try {

            // call Model's Filter method
            $user = User::filter($request)->paginate(20);

            return response()->json([
                'error' => false,
                'users' => $user
            ], 201);
        } catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured"
            ], 500);
        }
    }


    public function getUsersAfterDate(Request $request)
    {
        try {

            // we will use carbon to manipulate the date at which the user was created and use query search strings to find these uses at the specified date

            $minusDay = Carbon::now()->subDay();
            $minusWeek = Carbon::now()->subWeekdays(4);
            $minusMonth = Carbon::now()->subMonth();
            $minus3Months = Carbon::now()->subMonths(3);
            $minusYear = Carbon::now()->subYear();


            switch ($request->query("duration")) {

                case "d1":

                    $countUsers = User::where('created_at', '>=', $minusDay)->count();
                    $average = $countUsers / $minusDay->diffInHours();
                    return response()->json([
                        'error' => false,
                        'message' => 'The Customers has been retrieved successfully',
                        'users' => 'average of ' . round($average, 6) . ' users in the last 24 hours'
                    ], 201);
                    break;

                case "w1":

                    $countUsers = User::where('created_at', '>=', $minusWeek)->count();
                    $average = $countUsers / $minusDay->diffInHours();
                    return response()->json([
                        'error' => false,
                        'message' => 'The Customers has been retrieved successfully',
                        'users' => 'average of ' . round($average, 6) . ' users since last week '
                    ], 201);
                    break;

                case "m1":

                    $countUsers = User::where('created_at', '>=', $minusMonth)->count();
                    $average = $countUsers / $minusDay->diffInHours();
                    return response()->json([
                        'error' => false,
                        'message' => 'The Customers has been retrieved successfully',
                        'users' => 'average of ' . round($average, 6) . ' users since last month'
                    ], 201);
                    break;

                case "m3":

                    $countUsers = User::where('created_at', '>=', $minus3Months)->count();
                    $average = $countUsers / $minusDay->diffInHours();
                    return response()->json([
                        'error' => false,
                        'message' => 'The Customers has been retrieved successfully',
                        'users' => 'average of ' . round($average, 6) . ' since the last 3 months'
                    ], 201);
                    break;

                case "y1":

                    $countUsers = User::where('created_at', '>=', $minusYear)->count();
                    $average = $countUsers / $minusDay->diffInHours();
                    return response()->json([
                        'error' => false,
                        'message' => 'The Customers has been retrieved successfully',
                        'users' => 'average of ' . round($average, 6) . ' since last year'
                    ], 201);
                    break;
            }

        } catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            return response()->json([
                'error' => true,
                'message' => "Internal error occured"
            ], 500);
        }
    }


    public function update(Request $request,$id){
       try{
           $user = User::where('id', '=', $id)->first();
           $user->name = $request->name;
           $user->email = $request->email;
           $user->password = $request->password;

           $user->save();
           return response()->json([
            'error'=>false,
            'message'=>'The CustomerController has been updated successfully',
            'X'=>$user
           ],201);
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


