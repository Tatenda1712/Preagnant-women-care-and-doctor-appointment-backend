<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\User;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminController extends Controller
{
  public function sentNotification(Request $request){
    $mail= User::get();
    foreach($mail as $user){
    Mail::send([], array('token'=>'SAMPLE'), function($message) use($request,$user)
    {
    $message->from( 'musodzatatenda@gmail.com')
         ->to($user->email, 'Name')
         ->subject("noreplay@appointment.com")
         ->html('<p>
         <h3>Appointment Booking System</h3>
         <p>'.$request->message.'</p>
         <p>Thank You !!!</p>
       </p>', 'html');
     });
    }
    return response()->json(["data"=>"done"]);
  }
   // authenticate
   public function authenticate(Request $request){
    $credentials = $request->only('email', 'password');

    if (Auth::guard('admin')->attempt($credentials)) 
     {
       $user =Auth::guard('admin')->user();
        return response()->json(['data'=>$user], 200);
     }
     else
     {
        return response()->json(['data'=>'failed',203]);
     }
}

public function create(Request $request)
{ 
$mail=Admin::where("email",$request->email)->first();
if($mail!=null){
    return response()->json(["data"=>"email exists"]);
}
else{
    Admin::create(["fullname"=>$request->fullname,"email"=>$request->email,"password"=>bcrypt($request->password)]);
    Mail::send([], array('token'=>'SAMPLE'), function($message) use($request)
    {
    $message->from( 'musodzatatenda@gmail.com')
         ->to($request->email, 'Name')
         ->subject("noreplay@appointment.com")
         ->html('<p>
         <h3>Appointment Booking System</h3>
         <p>Hi '.$request->fullname.', You have successfully created an account on Appointment Booking System.</p>
         <p>Thank You !!!</p>
       </p>', 'html');
     });
        return response()->json(['data'=>'registered'],200);
    }
}
}
