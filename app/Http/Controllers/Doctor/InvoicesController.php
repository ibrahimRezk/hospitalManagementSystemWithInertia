<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnoseResource;
use App\Http\Resources\InvoiceResource;
use App\Models\Diagnose;
use App\Models\Invoice;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvoicesController extends Controller
{
    public $invoices = 1;
    public $completed = 3;
    public $review = 2;

    private string $routeResourceName = 'invoices';

    // public function __construct()
    // {
    //     $this->middleware('can:view diagnoses list')->only('index');
    //     $this->middleware('can:create diagnoses')->only(['create', 'store']);
    //     $this->middleware('can:edit diagnoses')->only(['edit', 'update']);
    //     $this->middleware('can:delete diagnoses')->only('destroy');
    // }


        // قائمة الكشوفات تحت الاجراء
        public function index(Request $request)
        {
            
            $invoices_status = $this->invoices;
            $page = 'Index';

            return  $this->invoices($request , $invoices_status, $page);
    
        }
       
        // قائمة المراجعات
        public function reviewInvoices(Request $request )
        {
            $invoices_status = $this->review;
            $page = 'Index';

            return $this->invoices($request , $invoices_status , $page);
        }
    


        // قائمة الفواتير المكتملة
        public function completedInvoices(Request $request) 
    
        {
            $invoices_status = $this->completed;
            $page = 'Index';

            return $this->invoices($request , $invoices_status , $page);
        }


    
        // public function show($id)
        // {
        //     $rays = Ray::findorFail($id);
        //     if($rays->doctor_id !=auth()->user()->id){
        //         //abort(404);
        //         return redirect()->route('404');
        //     }
        //     return view('Dashboard.Doctor.invoices.view_rays', compact('rays'));
        // }
    
        // public function showLaboratorie($id)
        // {
        //     $laboratories = Laboratorie::findorFail($id);
        //     if($laboratories->doctor_id !=auth()->user()->id){
        //         //abort(404);
        //         return redirect()->route('404');
        //     }
        //     return view('Dashboard.Doctor.invoices.view_laboratories', compact('laboratories'));
        // }

        

        public function invoices ($request , $invoices_status , $page){

                        // $invoices = Invoice::where('doctor_id',  Auth::user()->id)->where('invoice_status',1)->latest()->paginate(10);
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
                
                            ->where('doctor_id',  Auth::user()->id) 
                            ->where('invoice_status',$invoices_status)
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
                        
                
                            return Inertia::render('Doctor/Diagnose/DiagnosesList/'.$page, [
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
                                        'label' => '',
                                        'name' => '',
                                    ],
                                    // [
                                    //     'label' => 'Actions',
                                    //     'name' => 'actions',
                                    // ],
                                ],
                                'ActionMenu' =>[
                                    [
                                        'name'=>'Add Diagnose',
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
                                'create' => $request->user()->can('create diagnose'),
                            ],
                            'method'=> 'index',
                            ]);
                

        }

}
