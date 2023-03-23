<?php

namespace App\Http\Controllers\user;

use App\Models\Course;

use App\Http\Resources\CourseResouce;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CourseController extends Controller
{

    //POST Course-Enrollment API
    public function enrollment(Request $req)
    {
        //validation
        $req->validate([
            'user_id'=>'required',
            'title'=>'required', 
            'fees'=>'required',
            'duration'=>'required'
        ]);


        //create course and save
        $course= new Course();

        $course->user_id=$req->user_id;
        $course->title=$req->title;
        $course->description=$req->description;
        $course->fees=$req->fees;
        $course->duration=$req->duration;

        $course->save();


        //send response
        return response()->json([
            'status'=>1,
            'message'=>'Course enrolled successfully'
        ],200);

    }


    //GET All Course API
    public function all_courses()
    {
        
        $courses = Course::leftJoin('customers','courses.user_id','=','customers.id')
                         ->get(['courses.*','customers.name']);


        return response()->json([
            'status'=>1,
            'messega'=>'List of all enrolled courses',
            'data'=>CourseResouce::collection($courses)
        ],200);

        //using API Resource to customize the response Data                 
        //return CourseResouce::collection($courses); 

    }


    //UPDATE Course  API
    public function update_course(Request $req,$id)
    {

        $cust_id = auth()->guard('customer_api')->user()->id;

        if(Course::where(['id'=>$id,'user_id'=>$cust_id])->exists())
        {

            $course=Course::find($id);

            $course->title= isset($req->title) ? $req->title : $course->title;
            $course->description= isset($req->description) ? $req->description : $course->description;
            $course->fees= isset($req->fees) ? $req->fees : $course->fees;
            $course->duration= isset($req->duration) ? $req->duration : $course->duration;

            $course->save();

            return response()->json([
                'status'=> true,
                'message'=>'Course updated successfully'
            ],200);

        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'User Course does not exist'
            ],Response::HTTP_UNAUTHORIZED);

        }

    }


    //DELETE Course API
    public function delete_course($id)
    {

        $cust_id = auth()->guard('customer_api')->user()->id;

        if(Course::where(['id'=>$id,'user_id'=>$cust_id])->exists())
        {

            $course= Course::find($id);
            $course->delete();

            return response()->json([
                'status'=> true,
                'message'=>'Course deleted successfully'
            ],200);

        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'Course not found in our Database'
            ],Response::HTTP_UNAUTHORIZED);

        }

    }



}
