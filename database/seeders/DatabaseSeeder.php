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

        User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => '55555sssss',
            'name' => 'Super Admin',
        ]);
        
        User::factory()->create([
            'email' => 'editor@gmail.com',
            'password' => '55555sssss',
            'name' => 'Editor',
        ]);
        
        User::factory()->create([
            'email' => 'doctor@gmail.com',
            'password' => '55555sssss',
            'name' => 'Doctor',
        ]);
        
        \App\Models\User::factory(10)->create();
        
        $this->call([
            RolesSeeder::class,
            SectionTableSeeder::class,

        ]);

    }
}
