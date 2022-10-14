<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => '55555sssss',
            'name' => 'Super Admin',
        ]);
        

        User::factory()->create([
            'email' => 'doctor@gmail.com',
            'password' => '55555sssss',
            'name' => 'Doctor',
        ]);

        User::factory()->create([
            'email' => 'radiologist@gmail.com',
            'password' => '55555sssss',
            'name' => 'Radiologist',
        ]);
        
        User::factory()->create([
            'email' => 'laboratorist@gmail.com',
            'password' => '55555sssss',
            'name' => 'Laboratorist',
        ]);

        User::factory()->create([
            'email' => 'patient@gmail.com',
            'password' => '55555sssss',
            'name' => 'Patient',
        ]);


        $user =  User::factory()->count(1000)->create();

    }
}
