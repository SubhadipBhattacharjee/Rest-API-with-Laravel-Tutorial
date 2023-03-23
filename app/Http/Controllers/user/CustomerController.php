<?php

namespace App\Http\Controllers\user;

use App\Models\Customer;

use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    
    public function register(Request $req)
    {
        //validation
        $req->validate([
            'name'=>'required',
            'email'=>'required|email|unique:customers',
            'phone_no'=>'required',
            'password'=>'required|confirmed'
        ]);

        //Create user + save
        $user= new Customer();

        $user->name= $req->name;
        $user->email= $req->email;
        $user->phone_no= $req->phone_no;
        $user->password= bcrypt($req->password);

        $user->save();

        //Send Response
        return response()->json([
            'status'=>1,
            'message'=>'user registered Successfully'
        ], 200);

    }



    public function login(Request $req)
    {
        //validation
        $req->validate([           
            'email'=>'required|email',
            'password'=>'required' 
        ]);

        //verify admin
        if (!$token = auth()->guard('customer_api')->attempt(['email'=>$req->email,'password'=>$req->password]) ) 
        {
            return response()->json([
                'status' =>0,
                'message'=>'Invalid Credentials'
            ],Response::HTTP_UNAUTHORIZED);
        }

        //send response
        return response()->json([
            'status'=>1,
            'message'=>'User logged in successfully',
            'access_token'=>$token
        ],200);

    }



    public function profile($id)
    {
        $admin_data= Customer::findOrFail($id);

        return response()->json([
            'status'=>1,
            'message'=>'Customer Profile Data',
            'data'=>new UserResource($admin_data)
        ],200);
    }


    public function logout()
    {
        auth()->guard('customer_api')->logout();

        return response()->json([
            'status'=>1, 
            'message'=>'User successfully logged out'
        ],200);
    }

    
}
