<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('appointments')->delete();

        $ar_appointments  = [ 
            [['name' => 'السبت'],  ['name' => 'saturday']],
            [['name' => 'الأحد'],   ['name' => 'sunday']],
            [['name' => 'الاثنين'], ['name' => 'monday']],
            [['name' => 'الثلاثاء'],['name' => 'tuesday']],
            [['name' => 'الاربعاء'],['name' => 'wednesday']],
            [['name' => 'الخميس'], ['name' => 'Thursday']],
            [['name' => 'الجمعة'], ['name' => 'friday']],
        ];

        foreach ($ar_appointments as $appointment) {
            $data['ar'] = $appointment[0];
            $data['en'] =$appointment[1];
            Appointment::create($data);
        }

    }
}
