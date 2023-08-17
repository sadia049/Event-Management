<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\JWT_TOKEN;
use App\Mail\OTPmail;
use Error;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Password;
use PHPUnit\Event\Code\Throwable;

class UserController extends Controller
{
    function registration(Request $request)
    {

        try {

           // request validation

            $validated = Validator::make(
                $request->all(),
                [
                    'firstName' => 'alpha:ascii',
                    'lastName' => 'alpha:ascii',
                    'email' => 'required|email|unique:App\Models\User,email',
                    'password' => 'required|min:8'
                ],
                [
                    'firstName'=>'Only Aplphabet allowed',
                    'email.unique' => 'Already Have an account',
                    'password' => 'Minimum 8 character required'
                ]
            );

            if ($validated->fails()) {
                
                return response()->json(['status' => 'Failed', 'message' => $validated->errors()], 403);
            }
           
           
            
            

           
            echo 'No error till now';
             User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password')

            ]);
           // DB::table('users')->insert($request->input());

            return response()->json([
                "status" => "successfull",
                "message" => "Your response has been submitted"
            ], 200);
        } catch (Exception $e) {
            
            return response()->json([
                "status" =>   $e,
                "message" => "Registartion Failed"
            ], 400);
        }
    }

    //User Login method
    function Login(Request $request)
    {

        try {

            $count = User::where('email', '=', $request->input('email'))
                ->where('password', '=', $request->input('password'))
                ->select('id')->first();


            if ($count !== null) {
                $token = JWT_TOKEN::create_token($request->input('email'),$count->id);
                return response()->json([
                    'status' => 'successfull',
                    'message' => 'Login Successfull',
                    'token' => $token
                ])->cookie('token',$token,60*24*30);
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => 'Failed',
                'message' => 'Either the email or password is incorrect'
            ], 401);
        }
    }


   

}
