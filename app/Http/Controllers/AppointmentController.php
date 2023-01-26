<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //create appointment
    public function create(Request $request)
    {
        $letters =Str::random(6);
        $random = mt_rand(1000000000, 9999999999);
        $passs= str_shuffle(strtoupper($letters).$random);
        $referencenumbe=str_pad( 1, 8, $passs, STR_PAD_LEFT);
        //doctorID
        $pin = str_pad(1, 4,rand(1000, 9999), STR_PAD_LEFT);
        $id=Appointment::create(['doc_comment',"doctor_id"=>"1","doctorvisited"=>"0","fullname"=>$request->fullname,"email"=>$request->email,"idnumber"=>$request->idnumber,'phonenumber'=>$request->phonenumber,"status"=>"pending","reason"=>$request->reason,"user_id"=>$request->user_id,"refnumber"=>$referencenumbe,'pin'=>$pin,"appointdate"=>$request->dates,'appointtime'=>$request->times,'amount'=>"0"]);
        $referencenumber=$id."-".str_pad( 1, 12, $passs, STR_PAD_LEFT);
        Appointment::where("id",$id)->update(["refnumber"=>$referencenumber]);
        $refnumber=$referencenumber;
        $em=$request->email;
        $fn=$request->fullname;
        $doctor=Doctor::where("id","1")->first();
        $doctoremail=$doctor->email;
        Mail::send([], array('token'=>'SAMPLE'), function($message) use($fn,$em,$refnumber,$pin)
        {
        $message->from( 'musodzatatenda@gmail.com')
             ->to($em, 'Name')
             ->subject("noreplay@appointment.com")
             ->html('<p>
             <h3>Appointment Booking System</h3>
             <p>Hi '.$fn.', You have successfully created an appointment and your check in pin number is <b>'.$pin.'</b></p>
             <p>Thank You !!!</p>
           </p>', 'html');
         });
        Mail::send([], array('token'=>'SAMPLE'), function($message) use($fn,$em,$refnumber,$pin,$doctor,$request)
        {
        $message->from( 'musodzatatenda@gmail.com')
             ->to($doctor->email, 'Name')
             ->subject("noreplay@appointment.com")
             ->html('<p>
             <h3>Appointment Booking System</h3>
             <p>Hi '.$doctor->fullname.', You have an appointment with'.$fn.' on '.$request->dates.' at'.$request->times.'</p>
             <p>Thank You !!!</p>
           </p>', 'html');
         });


        return response()->json(["data"=>"created","appid"=>$id],200);
    }
    public function userAppointments(Request $request)
    {
        $appointments=Appointment::where("user_id",$request->id)->get();
        if($appointments!=null){
            return response()->json(["data"=>$appointments],200);
        }
        else{
            return response()->json(["data"=>"none"]);
        }
    }
    public function usertotalAppontments(Request $request)
    {
        $appointments=Appointment::where("user_id",$request->id)->get();
        $booked=Appointment::where(["user_id"=>$request->id,"status"=>"completed"])->get();
        $unbookedappointments=Appointment::where(["user_id"=>$request->id,"status"=>"pending"])->get();
        if($appointments!=null){
            return response()->json(["data"=>count($appointments),"unbooked"=>count($unbookedappointments),"booked"=>count($booked)],200);
        }
        else{
            return response()->json(["data"=>"none"]);
        }
    }
    public function doctortotalAppontments(Request $request)
    {
        $appointments=Appointment::where("doctor_id",$request->id)->get();
        $booked=Appointment::where(["doctor_id"=>$request->id,"status"=>"completed"])->get();
        $unbookedappointments=Appointment::where(["doctor_id"=>$request->id,"status"=>"pending"])->get();
        if($appointments!=null){
            return response()->json(["data"=>count($appointments),"unbooked"=>count($unbookedappointments),"booked"=>count($booked)],200);
        }
        else{
            return response()->json(["data"=>"none"]);
        }
    }
    public function getDoctors(){
        $doctors=Doctor::get();
        if($doctors==null){
            return response()->json(["data"=>"none"]);
        }
        else{
        return response()->json(["data"=>$doctors]);
        }
    }
    public function getPatients(){
        $doctors=User::get();
        if($doctors==null){
            return response()->json(["data"=>"none"]);
        }
        else{
        return response()->json(["data"=>$doctors]);
        }
    }
    public function admintotalAppontments(Request $request)
    {
        $appointments=Appointment::get();
        $booked=Appointment::where(["status"=>"pending"])->get();
        $user=User::get();
        $doctorc=Doctor::get();
        if($appointments!=null){
            return response()->json(["data"=>count($appointments),"user"=>count($user),"booked"=>count($booked),"doctor"=>count($doctorc)],200);
        }
        else{
            return response()->json(["data"=>"none"]);
        }
    }
    public function usergetunBooked(Request $request)
    {
        $appointments=Appointment::where(["user_id"=>$request->id,"status"=>"pending"])->get();
        if($appointments!=null){
            return response()->json(["data"=>$appointments],200);
        }
        else{
            return response()->json(["data"=>"no response"]);
        }
    }
    public function usergetBooked(Request $request)
    {
        $appointments=Appointment::where(["user_id"=>$request->id,"status"=>"completed"])->get();
        if($appointments!=null){
            return response()->json(["data"=>$appointments],200);
        }
        else{
            return response()->json(["data"=>"no response"]);
        }
    }

//doctor
public function doctorAllAppointments(Request $request)
{
    $appointments=Appointment::where("doctor_id",$request->id)->get();
    if($appointments!=null){
        return response()->json(["data"=>$appointments],200);
    }
    else{
        return response()->json(["data"=>"no appointments found"]);
    }
}
public function doctorpendingAppointments(Request $request)
{
    $appointments=Appointment::where(["doctor_id"=>$request->id,"status"=>"pending","doctorvisited"=>"0"])->get();
    if($appointments!=null){
        return response()->json(["data"=>$appointments],200);
    }
    else{
        return response()->json(["data"=>"no appointments"]);
    }
}
public function doctorvisitedAppointments(Request $request)
{
    $appointments=Appointment::where(["doctor_id"=>$request->id,"status"=>"completed","doctorvisited"=>"1"])->get();
    if($appointments!=null){
        return response()->json(["data"=>$appointments],200);
    }
    else{
        return response()->json(["data"=>"no appointments"]);
    }
}

public function doctorAcceptVisit(Request $request)
{
    $appointment=Appointment::where(["doctor_id"=>$request->id,"pin"=>$request->pin])->first();
    if($appointment==null){
        return response()->json(["data"=>"failed"]);
    }
    else{
    $appointment=Appointment::where(["doctor_id"=>$request->id,"pin"=>$request->pin])->update(["doctorvisited"=>"1","status"=>"completed","doccomment"=>$request->doccomment]);
        return response()->json(["data"=>"done"],200);
    }
}

    //admin

    public function adminAppointments()
    {
        $appointments=Appointment::get();
        if($appointments!=null){
            return response()->json(["data"=>$appointments]);
        }
        else{
            return response()->json(["data"=>"no response"]);
        }
    }
    public function adminWaitingDateAppointments()
    {
        $appointments=Appointment::where("status","pending")->get();
        if($appointments!=null){
            return response()->json(["data"=>$appointments]);
        }
        else{
            return response()->json(["data"=>"no response"]);
        }
    }
    public function adminVisitedDateAppointments()
    {
        $appointments=Appointment::where(["doctorvisited"=>"1"])->get();
        if($appointments!=null){
            return response()->json(["data"=>$appointments]);
        }
        else{
            return response()->json(["data"=>"no appointment"]);
        }
    }
    public function adminAcceptVisit(Request $request)
    {
        $appointment=Appointment::where(["pin"=>$request->pin])->update(["adminvisited"=>"1"]);
            return response()->json(["data"=>"done"],200);
    }

    public function adminAssignDate(Request $request)
    {
        $appointment=Appointment::where("id",$request->id)->update(["status"=>"completed","appointdate"=>$request->appointdate,"appointtime"=>$request->appointtime]);
        $app= Appointment::where("id",$request->id)->first();
       $doctor=Doctor::where("id",$app->doctor_id)->get();
       $user=User::where("id",$app->user_id)->get();
       $ur=$user->email;
        // Mail::send([], array('token'=>'SAMPLE'), function($message) use($request,$user)
        // {
        // $message->from( 'musodzatatenda@gmail.com')
        //      ->to($user->email, 'Name')
        //      ->subject("noreplay@appointment.com")
        //      ->setBody('<p>
        //      <h3>Buse Innovation Hub</h3>
        //      <p>Hi '.$user->fullname.', You have booked for an appointment and you can come on <b>'.$request->appointdate.'</b> at <b>'.$request->appointtime.'</b></p>
        //      <p>Thank You !!!</p>
        //    </p>', 'text/html');
        //  });
        // //  Mail::send([], array('token'=>'SAMPLE'), function($message) use($request,$doctor,$user)
        // //  {
        // //  $message->from( 'musodzatatenda@gmail.com')
        // //       ->to($doctor->email, 'Name')
        // //       ->subject("noreplay@appointment.com")
        // //       ->setBody('<p>
        // //       <h3>Buse Innovation Hub</h3>
        // //       <p>Hi '.$doctor->fullname.','.$user->fullname.' Have booked for an appointment on <b>'.$request->appointdate.'</b> at <b>'.$request->appointtime.'</b></p>
        // //       <p>Thank You !!!</p>
        // //     </p>', 'text/html');
        //   });
            return response()->json(["data"=>$ur]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAppointmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppointmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAppointmentRequest  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
