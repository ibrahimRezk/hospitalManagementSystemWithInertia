<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctors =  User::factory()->count(30)->create();
        $role = Role::find(2);
        foreach($doctors as $doctor){
            $doctor->assignRole($role);
        }
        // $Appointments = Appointment::all();

        // foreach ($doctors as $doctor) {
        //     $Appointments = Appointment::all();
        //     $doctor->doctorappointments()->attach($Appointments->random(rand(2, 4))->pluck('id')->toArray());
        // }
    }
}
