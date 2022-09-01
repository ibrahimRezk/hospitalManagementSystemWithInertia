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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable(); 
            
            $table->string('phone')->nullable();
            // make phone unique
            $table->boolean('status')->nullable()->default(1);
            $table->string('profile_photo_path', 2048)->nullable();
            
            $table->string('birth_date')->nullable()->date();
            $table->string('gender')->nullable();
            $table->string('blood_group')->nullable();

            
            $table->foreignId('current_team_id')->nullable();
            $table->foreignId('section_id')->nullable()->constrained()->cascadeOnDelete();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
