<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('doctor_id')->nullable();
            $table->string('fullname');
            $table->string('email');
            $table->string('idnumber')->nullable();
            $table->string('phonenumber');
            $table->string('status')->nullable();
            $table->string('appointdate');
            $table->string('doctorvisited');
            $table->string('appointtime');
            $table->string('reason')->nullable();
            $table->string('refnumber');
            $table->string('amount');
            $table->string('pin');
            $table->string('doccomment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
