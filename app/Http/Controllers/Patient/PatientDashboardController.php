<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PatientDashboardController extends Controller
{
    public function __invoke(Request $request)
    // {
    //     // dd($request);
    //     return Inertia::render('Patient/Dashboard');
    // }
{
    $invoices = Invoice::query()
    ->select([
        'id',
        'invoice_type',
        'patient_id',
        'doctor_id',
        'service_id',
        'invoice_status',
        'group_id',
        'price',
        'discount_value',
        'tax_rate',
        'tax_value',
        'total_with_tax',
        'price',
        'created_at',
        ])

        ->where('patient_id',  Auth::user()->id) 
        ->with('diagnose')

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
        ->paginate(10);
        
        // 'invoices' => InvoiceResource::collection($patient_invoices),
// dd($invoices);

// $diagnose = Diagnose::find()
    

        return Inertia::render('Patient/Dashboard', [
            // 'title' => 'Patient Details',

            'items' => InvoiceResource::collection($invoices),                
            'title'=> 'latest invoices',
            'headers' => [
                
                [
                    'label' => 'Service Name',
                    'name' => 'service_name',
                ],
                [
                    'label' => 'Date',
                    'name' => 'date',
                ],

                
                [
                    'label' => 'Total With Tax',
                    'name' => 'total_with_tax',
                ],
             
                [
                    'label' => 'Doctor',
                    'name' => 'doctor',
                ],
                [
                    'label' => 'Invoice Status',
                    'name' => 'invoice_status',
                ],
              
               
            ],
            
            'filters' => (object) $request->all(),
        // 'routeResourceName' => $this->routeResourceName,
        // 'can' => [
        //     'create' => $request->user()->can('create diagnose'),
        // ],
        'method'=> 'index',
        ]);

}

}
