<?php

namespace App\Http\Controllers\Radiologist;

use App\Http\Controllers\Controller;
use App\Http\Resources\RadiologistResource;
use App\Http\Resources\RadiologyResource;
use App\Models\Radiology;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RadiologistDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request);

        $radiologies = Radiology::query()
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

        return Inertia::render('Radiologist/Dashboard', [
            'total_number'=> $radiologies->count(),
            'total_underProccessing'=> Radiology::where('status',0)->count(),
            'total_completed'=> Radiology::where('status',1)->count(),

            'title'=> 'latest invoices on system',
            'items' => RadiologyResource::collection($radiologies),

    
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
            'create' => $request->user()->can('create radiology'),
        ],
        ]);
    }
}
