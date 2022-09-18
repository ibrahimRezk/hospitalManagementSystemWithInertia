<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnoseResource;
use App\Http\Resources\LaboratoryResource;
use App\Http\Resources\RadiologyResource;
use App\Models\Diagnose;
use App\Models\Laboratory;
use App\Models\Radiology;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientDetailsController extends Controller 
{
    public function index($id){ 

        $patient = User::find($id);
        $patient_records = Diagnose::where('patient_id',$id)->latest()->get();
        $patient_radiologies = Radiology::where('patient_id',$id)->latest()->paginate(10);
        $patient_laboratories  = Laboratory::where('patient_id',$id)->latest()->paginate(10);

        return Inertia::render('Doctor/Patient/Details', [
            // 'title' => 'Patient Details',

            'patient' => $patient,
            'patient_records' => DiagnoseResource::collection($patient_records) ,
            'patient_radiologies' =>  RadiologyResource::collection($patient_radiologies),  // resource collection here is important to get meta field in props.patient_radiology
            'patient_laboratories' => LaboratoryResource::collection($patient_laboratories) ,


            'headers' => [
                [
                    'label' => 'Patient Information',
                    'name' => 'patient_information',
                ],

                [
                    'label' => 'Radiology',
                    'name' => 'radiology',
                ],

                [
                    'label' => 'Laboratory',
                    'name' => 'laboratory',
                ],
            ],
     
        ]);
    }


}
