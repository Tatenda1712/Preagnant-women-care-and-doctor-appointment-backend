<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable=['doctorvisited','doctor_id','fullname','email','idnumber','phonenumber','status','reason',"doctor_id",'refnumber','pin','user_id','appointdate','appointtime','amount','doccomment'];
}