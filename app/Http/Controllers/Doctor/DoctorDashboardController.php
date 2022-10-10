<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DoctorDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // dd($request);

        $invoices = Invoice::query()
                        ->select([
                            'id',
                            'invoice_type',
                            'patient_id',
                            'doctor_id',
                            'service_id',
                            'invoice_status',
                            'group_id',
                    
                            'created_at',
                            ])
                
                            ->where('doctor_id',  Auth::user()->id) 

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
                            ->paginate(10);

        return Inertia::render('Doctor/Dashboard', [
            'total_number'=> Invoice::where('doctor_id' , auth()->user()->id)->count(),
            'total_underProccessing'=> Invoice::where('invoice_status',1)->where('doctor_id' , auth()->user()->id)->count(),
            'total_reviewed'=> Invoice::where('invoice_status',2)->where('doctor_id' , auth()->user()->id)->count(),
            'total_completed'=> Invoice::where('invoice_status',3)->where('doctor_id' , auth()->user()->id)->count(),

            'title'=> 'latest invoices on system',
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
                    'label' => 'Invoice State',
                    'name' => 'invoice_state',
                ],
         
            ],
 
        // 'routeResourceName' => $this->routeResourceName,
        'can' => [
            'create' => $request->user()->can('create diagnose'),
        ],
        ]);


}

}
