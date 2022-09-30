<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\LaboratoryResource;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaboratoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view diagnoses list')->only([ 'show']);
        $this->middleware('can:create diagnose')->only([ 'store']);
        $this->middleware('can:edit diagnose')->only([ 'update']);
        $this->middleware('can:delete diagnose')->only('destroy');
    }


    public function store(Request $request)
    {
        // dd($request);
        // try {
            Laboratory::create([
                'description'=>$request->description,
                'invoice_id'=>$request['data']['id'],
                'patient_id'=>$request['data']['patient']['id'],
                'doctor_id'=>$request['data']['doctor']['id'],
            ]);
            // session()->flash('add');
            // return redirect()->back();
        // }
        // catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // }
    }

    public function update(Request $request, $id)
    {
        // try {
            $radiologies = Laboratory::findOrFail($id);
            $radiologies->update([
                'description' => $request->description,
            ]);
            // session()->flash('edit');
            return redirect()->back();
        // }
        // catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // }
    }

    public function show($id){
        $result = Laboratory::findorFail($id);
        $result->load('media');
        if($result->doctor_id !=auth()->user()->id){
            //abort(404);
            return redirect()->route('404');
        }

        return Inertia::render('Doctor/Patient/Result', [
            'result'=> new LaboratoryResource($result)
        ]);
    }

    public function destroy($id)
    {
        // try {
            Laboratory ::destroy($id);
            // session()->flash('delete');
            // return redirect()->back();
        // }
        // catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        // }
    }
}
