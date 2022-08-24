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
            'email' => 'editor@gmail.com',
            'password' => '55555sssss',
            'name' => 'Editor',
        ]);
        
        User::factory()->create([
            'email' => 'doctor@gmail.com',
            'password' => '55555sssss',
            'name' => 'Doctor',
        ]);
        $user =  User::factory()->count(10)->create();

    }
}
