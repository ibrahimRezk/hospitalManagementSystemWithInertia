<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\AddReviewsRequest;
use App\Http\Requests\Doctor\DiagnosesRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Diagnose;
use App\Models\Invoice;
use App\Models\Notification;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DiagnosesController extends Controller
{
    private string $routeResourceName = 'diagnoses';

    public function __construct()
    {
        // $this->middleware('can:view diagnoses list')->only('index');
        $this->middleware('can:create diagnose')->only([ 'store']);
        $this->middleware('can:edit diagnose')->only(['update']);
        // $this->middleware('can:delete diagnose')->only('destroy');
    }




    public function store(DiagnosesRequest $request)
    {
// dd($request);

        // change invoice type to 3  to be completed 
        $invoice = Invoice::find($request->id);
        $invoice->update(['invoice_status' => $request->invoice_status]);
        // because mass assignment we put it like this  // to be changed
        $diagnose = new Diagnose();
        $diagnose->date = date('Y-m-d');
        $diagnose->review_date = $request->review_date ?? null;

        $diagnose->diagnose = $request->diagnose;
        $diagnose->medicine = $request->medicine; 
        $diagnose->invoice_id = $request['data']['id'];
        $diagnose->patient_id = $request['data']['patient']['id'];
        $diagnose->doctor_id = $request['data']['doctor']['id'];
        $diagnose->save();

        // if we want to update status for the only the last invoice notification
        $notification = Notification::where('patient_id' ,$diagnose->patient_id)->where('doctor_id', $request['data']['doctor']['id'])->where('read_status', 0)->first();
        $notification?->update(['read_status' => 1]); // notify question mark -> if there is notification

        // alternative long code 
        // if($notification != null ){
        //     $notification->update(['read_status' => 1]);
        // }


        //// if we want to update status for all invoices notifications for the same patient 
        // $notifications = Notification::where('patient_id' ,$diagnose->patient_id)->get();
        // foreach($notifications as $n){
        //     $n->update(['read_status' => 1]);
        // }
    }

    public function update(Request $request , $id){
        // dd($id);
        $invoice =Invoice::find($id);
        $diagnose = $invoice->diagnose;

        // $diagnose =Diagnose::find($id);
        // $invoice = Invoice::where('id', $diagnose->invoice_id); // to update state of invoice
        $invoice->update(['invoice_status' => $request->invoice_status]);
        $diagnose->date = date('Y-m-d');
        $diagnose->diagnose = $request->diagnose;
        $diagnose->medicine = $request->medicine; 
        $diagnose->invoice_id = $request['data']['id'];
        $diagnose->patient_id = $request['data']['patient']['id'];
        $diagnose->doctor_id = $request['data']['doctor']['id'];
        $diagnose->save();

           // if we want to update status for the only the last invoice notification
           $notification = Notification::where('patient_id' ,$diagnose->patient_id)->where('read_status', 0)->first();
           $notification?->update(['read_status' => 1]); // notify question mark -> if there is notification
    }






// this line can be added in above to methods to call invoice_status method instead of two lines update to avoid repeating code 
    // $this->invoice_status($request->invoice_id,2);

    // public function invoice_status($invoice_id,$id_status){
    //     $invoice_status = Invoice::findorFail($invoice_id);
    //     $invoice_status->update([
    //         'invoice_status'=>$id_status
    //     ]);
}
