<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // authenticate
    public function authenticate(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::guard('user')->attempt($credentials)) 
         {
           $user =Auth::guard('user')->user();
            return response()->json(['data'=>$user], 200);
         }
         else
         {
            return response()->json(['data'=>'failed',203]);
         }
    }
       public function updatePayment(Request $request)
    {
        Appointment::where("id",$request->id)->update(["amount"=>"100"]);
        return response()->json(["data"=>"done"]);
    }

    public function create(Request $request)
    { 
    $mail=User::where("email",$request->email)->first();
    if($mail!=null){
        return response()->json(["data"=>"email exists"]);
    }
    else{
        User::create(["fullname"=>$request->fullname,"phone"=>$request->phone,"email"=>$request->email,"address"=>$request->address,"password"=>bcrypt($request->password)]);
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
    public function changepassword(Request $request)
    {
        $password= User::where("id",$request->id)->value("password"); 
        if(Hash::check($request->oldpassword,$password)) {
            User::where("id",$request->id)->update(["password"=>bcrypt($request->newpassword)]);
         return response()->json(["data"=>"password changed"],200);
        } else {
            return response()->json(["data"=>"incorrect password"]);
        }
    }

}
