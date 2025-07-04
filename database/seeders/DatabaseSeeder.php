<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        // \App\Models\User::factory()->create([
            //     'name' => 'Test User',
            //     'email' => 'test@example.com',
        // ]);

     
        
        // \App\Models\User::factory(10)->create();
        
        $this->call([
            SectionTableSeeder::class,
            UserSeeder::class,
            RolesSeeder::class,
            DoctorSeeder::class,
            AppointmentSeeder::class,
            PatientSeeder::class,
            
        ]);
        
    }
}
