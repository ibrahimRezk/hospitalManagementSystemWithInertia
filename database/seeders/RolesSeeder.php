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

        $doctor = Role::create(['name' => 'Doctor']);
        User::find(2)->assignRole($doctor);
        

        $radiologist = Role::create(['name' => 'Radiologist']);
        User::find(3)->assignRole($radiologist);
        

        $laboratorist = Role::create(['name' => 'Laboratorist']);
        User::find(4)->assignRole($laboratorist);

        $patient     = Role::create(['name' =>'Patient']);
        User::find(5)->assignRole($patient);

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

        Permission::create(['name' => 'view receipts module']);
        Permission::create(['name' => 'view receipts list']);
        Permission::create(['name' => 'create receipt']);
        Permission::create(['name' => 'edit receipt']);
        Permission::create(['name' => 'delete receipt']); 

        Permission::create(['name' => 'view payments module']);
        Permission::create(['name' => 'view payments list']);
        Permission::create(['name' => 'create payment']);
        Permission::create(['name' => 'edit payment']);
        Permission::create(['name' => 'delete payment']); 
        
        Permission::create(['name' => 'view radiologists module']);
        Permission::create(['name' => 'view radiologists list']);
        Permission::create(['name' => 'create radiologist']);
        Permission::create(['name' => 'edit radiologist']);
        Permission::create(['name' => 'delete radiologist']); 


        Permission::create(['name' => 'view laboratorists module']);
        Permission::create(['name' => 'view laboratorists list']);
        Permission::create(['name' => 'create laboratorist']);
        Permission::create(['name' => 'edit laboratorist']);
        Permission::create(['name' => 'delete laboratorist']); 
        
        Permission::create(['name' => 'view diagnoses module']);
        Permission::create(['name' => 'view diagnoses list']);
        Permission::create(['name' => 'create diagnose']);
        Permission::create(['name' => 'edit diagnose']);
        Permission::create(['name' => 'delete diagnose']); 

        Permission::create(['name' => 'view invoices module']);
        Permission::create(['name' => 'view invoices list']);
        Permission::create(['name' => 'create invoice']);
        Permission::create(['name' => 'edit invoice']);
        Permission::create(['name' => 'delete invoice']);

        Permission::create(['name' => 'view laboratories module']);
        Permission::create(['name' => 'view laboratories list']);
        Permission::create(['name' => 'create laboratory']);
        Permission::create(['name' => 'edit laboratory']);
        Permission::create(['name' => 'delete laboratory']); 


        Permission::create(['name' => 'view radiologies module']);
        Permission::create(['name' => 'view radiologies list']);
        Permission::create(['name' => 'create radiology']);
        Permission::create(['name' => 'edit radiology']);
        Permission::create(['name' => 'delete radiology']); 
        
        Permission::create(['name' => 'view patientPage']);


        
    }// remember to add main munus witch has submenus
}
