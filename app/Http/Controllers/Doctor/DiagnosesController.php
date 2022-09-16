<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\AddReviewsRequest;
use App\Http\Requests\Doctor\DiagnosesRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Diagnose;
use App\Models\Invoice;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DiagnosesController extends Controller
{
    private string $routeResourceName = 'diagnoses';

    // public function __construct()
    // {
    //     $this->middleware('can:view diagnoses list')->only('index');
    //     $this->middleware('can:create diagnoses')->only(['create', 'store']);
    //     $this->middleware('can:edit diagnoses')->only(['edit', 'update']);
    //     $this->middleware('can:delete diagnoses')->only('destroy');
    // }




    public function store(DiagnosesRequest $request)
    {
dd($request);

        // change invoice type to 3  to be completed 
        $invoice = Invoice::find($request->id);
        $invoice->update(['invoice_status' => 3]);
        // because mass assignment we put it like this  // to be changed
        $diagnose = new Diagnose();
        $diagnose->date = date('Y-m-d');
        $diagnose->diagnose = $request->diagnose;
        $diagnose->medicine = $request->medicine; 
        $diagnose->invoice_id = $request['data']['id'];
        $diagnose->patient_id = $request['data']['patient']['id'];
        $diagnose->doctor_id = $request['data']['doctor']['id'];
        $diagnose->save();
    }

    public function update(Request $request , $id){
        // dd($request);
        $diagnose =Diagnose::find($id);
        $diagnose->update($request->all());
    }


    public function addReview(AddReviewsRequest $request)
    {
        // dd($request);

         // change invoice type to 3  to be add review 
         $invoice = Invoice::find($request->id);
         $invoice->update(['invoice_status' => 2]);
         // because mass assignment we put it like this  // to be changed
         $diagnose = new Diagnose();
         $diagnose->date = date('Y-m-d');
         $diagnose->review_date = $request->review_date;
         $diagnose->diagnose = $request->diagnose;
         $diagnose->medicine = $request->medicine; 
         $diagnose->invoice_id = $request['data']['id'];
         $diagnose->patient_id = $request['data']['patient']['id'];
         $diagnose->doctor_id = $request['data']['doctor']['id'];
         $diagnose->save();

    }






// this line can be added in above to methods to call invoice_status method instead of two lines update to avoid repeating code 
    // $this->invoice_status($request->invoice_id,2);

    // public function invoice_status($invoice_id,$id_status){
    //     $invoice_status = Invoice::findorFail($invoice_id);
    //     $invoice_status->update([
    //         'invoice_status'=>$id_status
    //     ]);
}
