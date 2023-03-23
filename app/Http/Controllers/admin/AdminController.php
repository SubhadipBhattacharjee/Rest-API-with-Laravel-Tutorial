<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;

use App\Http\Resources\AdminResource;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    
    public function register(Request $req)
    {
        //validation
        $req->validate([
            'name'=>'required',
            'email'=>'required|email|unique:admins',
            'phone_no'=>'required',
            'password'=>'required|confirmed',
        ]);


        //create admin + save
        $admin= new Admin();

        $admin->name= $req->name;
        $admin->email= $req->email;
        $admin->phone_no= $req->phone_no;
        $admin->password= bcrypt($req->password);

        $admin->save();


        //send response
        return response()->json([
            'status'=>1,
            'message'=>'Admin registered Successfully'
        ],Response::HTTP_OK);

    }


    public function login(Request $req)
    {
        //validation
        $req->validate([       
            'email'=>'required|email',
            'password'=>'required' 
        ]);


        //verify admin
        if (!$token = auth()->guard('admin_api')->attempt(['email'=>$req->email,'password'=>$req->password]) ) 
        {
            return response()->json([
                'status' =>0,
                'message'=>'Invalid Credentials'
            ],Response::HTTP_UNAUTHORIZED);
        }

        
        //send response
        return response()->json([
            'status'=>1,
            'message'=>'Admin logged in successfully',
            'access_token'=>$token
        ],Response::HTTP_ACCEPTED);

    }


    public function profile()
    {

        $admin_data= auth()->guard('admin_api')->user();

        return response()->json([

            'status'=>1,
            'message'=>'Admin Profile Data',
            'data'=>new AdminResource($admin_data)

        ],Response::HTTP_CREATED);

       // return new AdminResource($admin_data);

    }

    
    public function logout()
    {

        auth()->guard('admin_api')->logout();

        return response()->json([
            'status'=>1,
            'message'=>'Admin successfully logged out'
        ],Response::HTTP_OK);

    }


    
}
