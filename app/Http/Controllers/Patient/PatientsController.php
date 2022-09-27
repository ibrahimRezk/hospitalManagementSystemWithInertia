<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\LaboratoryResource;
use App\Http\Resources\RadiologyResource;
use App\Models\Invoice;
use App\Models\Laboratory;
use App\Models\Radiology;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PatientsController extends Controller
{
    private string $routeResourceName = 'patients';

    public function __construct()
    {
        $this->middleware('can:view patientPage')->only(['invoices','laboratories' ,'labResult' ,'radiologies' ,'radiologyResult']);
        // $this->middleware('can:create diagnoses')->only(['create', 'store']);
        // $this->middleware('can:edit diagnoses')->only(['edit', 'update']);
        // $this->middleware('can:delete diagnoses')->only('destroy');
    }


    public function invoices(Request $request)
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
        

            return Inertia::render('Patient/Invoices', [
                // 'title' => 'Patient Details',
    
                'items' => InvoiceResource::collection($invoices),                
    
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
                        'label' => 'Price',
                        'name' => 'price',
                    ],
                    [
                        'label' => 'Discount Value',
                        'name' => 'discount_value',
                    ],
                    [
                        'label' => 'Tax Rate',
                        'name' => 'tax_rate',
                    ],
                    [
                        'label' => 'Tax Value',
                        'name' => 'tax_value',
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
                  
                    [
                        'label' => 'Actions',
                        'name' => 'actions',
                    ],
                ],
                
                'filters' => (object) $request->all(),
            'routeResourceName' => $this->routeResourceName,
            'can' => [
                'create' => $request->user()->can('create diagnose'),
            ],
            'method'=> 'index',
            ]);

    }





    public function laboratories(Request $request)
    {
        $laboratories = Laboratory::query()
        ->select([
            'id',
            'doctor_id',
            'employee_id',
            'description',
            'employee_description',
            'patient_id',
            'created_at',
            ])

            ->where('patient_id',  Auth::user()->id) 
            ->with(['doctor','employee'])

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
        
// dd($laboratories);
            return Inertia::render('Patient/Laboratories', [
                // 'title' => 'Patient Details',
                
                'items' => LaboratoryResource::collection($laboratories),                
                
                'headers' => [
                    [
                        'label' => 'created at',
                        'name' => 'created_at',
                    ],
                    
                
                    [
                        'label' => 'doctor',
                        'name' => 'doctor',
                    ],
                    [
                        'label' => 'description',
                        'name' => 'description',
                    ],
    
                    [
                        'label' => 'employee',
                        'name' => 'employee',
                    ],
                    [
                        'label' => 'employee description',
                        'name' => 'employee_description',
                    ],
                 
                 
                
                  
                    [
                        'label' => 'Actions',
                        'name' => 'actions',
                    ],
                ],
                
                'filters' => (object) $request->all(),
            'routeResourceName' => $this->routeResourceName,
            'can' => [
                // 'create' => $request->user()->can('create diagnose'),
            ],
            'method'=> 'laboratories',
            ]);
    }

    // public function report($id)
    // {
    //     // dd($id);
    //     $invoice = Invoice::find($id);
    //     $report = $invoice->diagnose()->first();
    //     return  $report;
    // }


    public function labResult($id)
    {
        // dd($id);
        $labResult = Laboratory::find($id);

        return Inertia::render('Patient/Result', [
            // 'title' => 'Patient Details',
            
            'result' => new LaboratoryResource($labResult),        
        ]
        );

    }


    public function radiologies(Request $request)
    {
        $radiologies = Radiology::query()
        ->select([
            'id',
            'doctor_id',
            'employee_id',
            'description',
            'employee_description',
            'patient_id',
            'created_at',
            ])

            ->where('patient_id',  Auth::user()->id) 
            ->with(['doctor','employee'])

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
        
// dd($laboratories);
            return Inertia::render('Patient/Radiologies', [
                // 'title' => 'Patient Details',
                
                'items' => RadiologyResource::collection($radiologies),                
                
                'headers' => [
                    [
                        'label' => 'created at',
                        'name' => 'created_at',
                    ],
                    
                
                    [
                        'label' => 'doctor',
                        'name' => 'doctor',
                    ],
                    [
                        'label' => 'description',
                        'name' => 'description',
                    ],
    
                    [
                        'label' => 'employee',
                        'name' => 'employee',
                    ],
                    [
                        'label' => 'employee description',
                        'name' => 'employee_description',
                    ],
                 
                 
                
                  
                    [
                        'label' => 'Actions',
                        'name' => 'actions',
                    ],
                ],
                
                'filters' => (object) $request->all(),
            'routeResourceName' => $this->routeResourceName,
            'can' => [
                // 'create' => $request->user()->can('create diagnose'),
            ],
            'method'=> 'radiologies',
            ]);

    }

    public function radiologyResult($id)
    {
        // dd($id);
        $radiology_result = Radiology::find($id);

        return Inertia::render('Patient/Result', [
            // 'title' => 'Patient Details',
            
            'result' => new RadiologyResource($radiology_result),        
        ]
        );

    }
}
