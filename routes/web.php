<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminController;
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

Route::get('/', function () {
    return view('welcome');
});

//Users
Route::post('user/auth',  [UserController::class, 'authenticate']);
Route::post('user/register',  [UserController::class, 'create']);
Route::post('user/createappointment',  [AppointmentController::class, 'create']);
Route::post('user/getallappointments',  [AppointmentController::class, 'userAppointments']);
Route::post('user/getunbookedappointments',  [AppointmentController::class, 'usergetunBooked']);
Route::post('user/getbookedappointments',  [AppointmentController::class, 'usergetBooked']);
Route::post('user/totalappointments',  [AppointmentController::class, 'usertotalAppontments']);
Route::post('user/changepassword',  [UserController::class, 'changepassword']);
Route::post('user/updatepayment',  [UserController::class, 'updatePayment']);


//Doctors   
Route::post('doctor/auth',  [DoctorController::class, 'authenticate']);
Route::post('doctor/register',  [DoctorController::class, 'create']);
Route::post('doctor/getallapointments',  [AppointmentController::class, 'doctorAllAppointments']);
Route::post('doctor/getpendingappointments',  [AppointmentController::class, 'doctorpendingAppointments']);
Route::post('doctor/getvisitedappointments',  [AppointmentController::class, 'doctorvisitedAppointments']);
Route::post('doctor/acceptuser',  [AppointmentController::class, 'doctorAcceptVisit']);
Route::post('doctor/totalappointments',  [AppointmentController::class, 'doctortotalAppontments']);
Route::post('doctor/notifiation',  [DoctorController::class, 'sentNotification']);

//Admin
Route::post('admin/auth',  [AdminController::class, 'authenticate']);
Route::post('admin/register',  [AdminController::class, 'create']);
Route::post('admin/allappointments',  [AppointmentController::class, 'adminAppointments']);
Route::post('admin/pendingdate',  [AppointmentController::class, 'adminWaitingDateAppointments']);
Route::post('admin/visited',  [AppointmentController::class, 'adminVisitedDateAppointments']);
Route::post('admin/acceptvisit',  [AppointmentController::class, 'adminAcceptVisit']);
Route::post('admin/assigndate',  [AppointmentController::class, 'adminAssignDate']);
Route::post('admin/totalappointments',  [AppointmentController::class, 'admintotalAppontments']);
Route::post('admin/doctors',  [AppointmentController::class, 'getDoctors']);
Route::post('admin/patients',  [AppointmentController::class, 'getPatients']);
Route::post('admin/notifiation',  [AdminController::class, 'sentNotification']);


//