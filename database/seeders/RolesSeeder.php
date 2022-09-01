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

        $patient = Role::create(['name'=>'Patient']);

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


        Permission::create(['name' => 'view services module']);
        Permission::create(['name' => 'view services list']);
        Permission::create(['name' => 'create service']);
        Permission::create(['name' => 'edit service']);
        Permission::create(['name' => 'delete service']); 

        Permission::create(['name' => 'view insurances module']);
        Permission::create(['name' => 'view insurances list']);
        Permission::create(['name' => 'create insurance']);
        Permission::create(['name' => 'edit insurance']);
        Permission::create(['name' => 'delete insurance']); 

        Permission::create(['name' => 'view ambulances module']);
        Permission::create(['name' => 'view ambulances list']);
        Permission::create(['name' => 'create ambulance']);
        Permission::create(['name' => 'edit ambulance']);
        Permission::create(['name' => 'delete ambulance']); 

        Permission::create(['name' => 'view patients module']);
        Permission::create(['name' => 'view patients list']);
        Permission::create(['name' => 'create patient']);
        Permission::create(['name' => 'edit patient']);
        Permission::create(['name' => 'delete patient']); 
    }
}
