<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'fullname',
        'phone',
        'email',
        'expertin',
        'password',
    ];
    public function appointments()
    {
    	return $this->hasMany(Appointments::class,"doctor_id","id");
    }
    protected $guard = 'doctor';
}
