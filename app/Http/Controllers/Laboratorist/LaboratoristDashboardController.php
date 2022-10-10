<?php

namespace App\Http\Controllers\Laboratorist;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaboratoryResource;
use App\Models\Laboratory;
use App\Models\Radiology;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaboratoristDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request);

        $laboratories = Laboratory::query()
        ->select([
            'id',
            'description',
            'invoice_id',
            'patient_id',
            'doctor_id',
            'status',
            'created_at',
            ])


            // ->with(['service'])
            // ->with(['patient'])

            // ->when( 
            //     $request->patient_id,
            //     fn (Builder $builder, $patient_id) =>  $builder->where( 'patient_id', $patient_id)
            //     )

            // ->when( 
            //     $request->invoice_status,
            //     fn (Builder $builder, $invoice_status) =>  $builder->where( 'invoice_status', $invoice_status)
            //     )
            
            ->latest('id')
            ->take(5)
            ->paginate(5);

        return Inertia::render('Laboratorist/Dashboard', [

            'total_number'=> Laboratory::where('employee_id' , auth()->user()->id)->count(),
            'total_underProccessing'=> Laboratory::where('status',0)->where('employee_id' , auth()->user()->id)->count(),
            'total_completed'=> Laboratory::where('status',1)->where('employee_id' , auth()->user()->id)->count(),
            
            'title'=> 'latest invoices on system',

            'items' => LaboratoryResource::collection($laboratories),

    
            'headers' => [
             
                [
                    'label' => 'Patient name',
                    'name' => 'patient_name',
                ],
                [
                    'label' => 'Description',
                    'name' => 'description',
                ],
                [
                    'label' => 'Doctor',
                    'name' => 'doctor',
                ],
                [
                    'label' => 'Created At',
                    'name' => 'created_at',
                ],

                [
                    'label' => 'Status',
                    'name' => 'status',
                ],

            ],

        'can' => [
            'create' => $request->user()->can('create laboratory'),
        ],
        ]);
    }
}
