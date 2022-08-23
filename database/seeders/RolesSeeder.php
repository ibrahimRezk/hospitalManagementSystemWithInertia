<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::create(['name' => 'Super Admin']); 

        User::find(1)->assignRole($superAdmin);



        $editor = Role::create(['name' => 'Editor']);

        $editor = User::find(2)->assignRole($editor);


        $doctor = Role::create(['name' => 'Doctor']);
        $doctor = User::find(3)->assignRole($doctor);

        Permission::create(['name' => 'view permissions module']);
        Permission::create(['name' => 'view permissions list']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view roles module']);
        Permission::create(['name' => 'view roles list']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']); 

        Permission::create(['name' => 'view users module']);
        Permission::create(['name' => 'view users list']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']); 
        
        
        Permission::create(['name' => 'view sections module']);
        Permission::create(['name' => 'view sections list']);
        Permission::create(['name' => 'create section']);
        Permission::create(['name' => 'edit section']);
        Permission::create(['name' => 'delete section']); 


        Permission::create(['name' => 'view doctors module']);
        Permission::create(['name' => 'view doctors list']);
        Permission::create(['name' => 'create doctor']);
        Permission::create(['name' => 'edit doctor']);
        Permission::create(['name' => 'delete doctor']); 
    }
}
