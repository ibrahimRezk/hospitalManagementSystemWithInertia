<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DiagnosisController extends Controller
{
    private string $routeResourceName = 'diagnoses';

    // public function __construct()
    // {
    //     $this->middleware('can:view diagnoses list')->only('index');
    //     $this->middleware('can:create diagnosis')->only(['create', 'store']);
    //     $this->middleware('can:edit diagnosis')->only(['edit', 'update']);
    //     $this->middleware('can:delete diagnosis')->only('destroy');
    // }


    public function index(Request $request)
    {
        // $invoices = Invoice::where('doctor_id',  Auth::user()->id)->where('invoice_status',1)->latest()->paginate(10);
        $invoices = Invoice::query()
        ->select([
            'id',
            'invoice_type',
            'patient_id',
            'service_id',
            'group_id',
            'price',
            'discount_value',
            'tax_rate',
            'tax_value',
            'total_with_tax',
            'price',
            'created_at',
            ])

            ->where('doctor_id',  Auth::user()->id) 
            ->where('invoice_status',1)

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

        

            return Inertia::render('Doctor/Diagnosis/DiagnosisList/Index', [
                // 'title' => 'Patient Details',
    
                'items' => InvoiceResource::collection($invoices),

    
                'headers' => [
                    [
                        'label' => 'Patient name',
                        'name' => 'patient_name',
                    ],
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
                        'label' => 'Invoice State',
                        'name' => 'invoice_state',
                    ],
                    [
                        'label' => 'Actions',
                        'name' => 'actions',
                    ],
                ],
                'ActionMenu' =>[
                    [
                        'name'=>'Add Diagnosis',
                        'url'=>'#'
                    ],
                    [
                        'name'=>'Add Review',
                        'url'=>'#'
                    ],
                    [
                        'name'=>'Return to Radiology',
                        'url'=>'#'
                    ],
                    [
                        'name'=>'Return to Laboratory',
                        'url'=>'#'
                    ]
                ],
                'filters' => (object) $request->all(),
            'routeResourceName' => $this->routeResourceName,
            'can' => [
                'create' => $request->user()->can('create diagnosis'),
            ],
            'method'=> 'index',
            ]);


    }
}
