<?php

namespace App\Http\Controllers\Laboratorist;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaboratoryResource;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LaboratoriesController extends Controller
{
    private string $routeResourceName = 'laboratories';
    public $underProccessing = 0;
    public $completed = 1;

    public function __construct()
    {
        $this->middleware('can:view laboratories list')->only([ 'index' , 'completedInvoices']);
        $this->middleware('can:create laboratory')->only([ 'create','store']);
        // $this->middleware('can:edit laboratory')->only([ 'update']);
        // $this->middleware('can:delete laboratory')->only('destroy');
    }


       // قائمة الكشوفات تحت الاجراء
       public function index(Request $request)
       {
           
           $status = $this->underProccessing;
           $page = 'Index';

           return  $this->invoices($request , $status, $page);
   
       }

        // قائمة الفواتير المكتملة
        public function completedInvoices(Request $request) 
    
        {
            $status = $this->completed;
            $page = 'CompletedInvoices';

            return $this->invoices($request , $status , $page);
        }


    public function invoices($request , $status , $page){

        $laboratories = Laboratory::query()
        ->select([
            'id',
            'description',
            'invoice_id',
            'patient_id',
            'doctor_id',
            'employee_id',
            'status',
            'created_at',
            ])

            ->where('status',$status)


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
// dd($laboratories);

        

            return Inertia::render('Laboratorist/DiagnosisList/'.$page, [
                // 'title' => 'Patient Details',
    
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
    
    

                    
                    [
                        'label' => 'Actions',
                        'name' => 'actions',
                    ],
                    // [
                    //     'label' => 'Actions',
                    //     'name' => 'actions',
                    // ],
                ],

                'filters' => (object) $request->all(),
            'routeResourceName' => $this->routeResourceName,
            'can' => [
                'create' => $request->user()->can('create diagnose'),
            ],
            'method'=> 'index',
            ]);

    }


    public function create(Request $request){
        // dd($request);
        $laboratory_details = Laboratory::find($request->id);
        $laboratory_details->load('media');

        return Inertia::render('Laboratorist/DiagnosisList/Create', [
            'item' => new LaboratoryResource($laboratory_details), 
            'edit' => false,
            'title' => 'Add Laboratory Report',
            'routeResourceName' => $this->routeResourceName,
            'employee_id' => Auth::user()->id,

        ]);
    }

    public function store(Request $request)
    {
        // dd($request);
        $laboratory = Laboratory::find($request->id);
        $laboratory->update([
            'employee_description'=> $request->employee_description,
            'employee_id'         => $request->employee_id,
            'status'              => 1
        ]);
        return redirect()->route("laboratorist.{$this->routeResourceName}.index")->with('success', 'User created successfully.');

    }
}
