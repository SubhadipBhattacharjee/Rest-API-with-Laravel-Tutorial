<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\user\CustomerController;
use App\Http\Controllers\user\CourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//-------------------------------------------------Admin-Section--------------------------------------------------//

Route::post('admin/registration',[AdminController::Class,'register']);
Route::post('admin/login',[AdminController::Class,'login']);


Route::group([
    'prefix' => 'admin/',
    'middleware' => ['auth:admin_api'] 
], function () {

    Route::get('logout',[AdminController::Class,'logout']);

    Route::get('all-course',[CourseController::Class,'all_courses']);
    Route::get('profile',[AdminController::Class,'profile']);

});


//---------------------------------------------------User-Section-------------------------------------------------//

Route::post('user/registration',[CustomerController::Class,'register']);
Route::post('user/login',[CustomerController::Class,'login']);

Route::group(['prefix' => 'user/','middleware' => ['auth:customer_api'] ], function () {

    Route::get('logout',[CustomerController::Class,'logout']);

    Route::get('profile/{id}',[CustomerController::Class,'profile']);
    Route::post('course-enrollment',[CourseController::Class,'enrollment']);
    Route::put('update-course/{id}',[CourseController::Class,'update_course']);
    Route::delete('delete-course/{id}',[CourseController::Class,'delete_course']);
    
});