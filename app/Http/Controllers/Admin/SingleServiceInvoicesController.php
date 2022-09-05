<?php

namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SingleInvoicesRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\ServiceResource;
use App\Models\FundAccount;
use App\Models\Invoice;
use App\Models\PatientAccount;
use App\Models\Section;
use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SingleServiceInvoicesController extends Controller
{
    private string $routeResourceName = 'singleInvoices';


    public function __construct()
    {
        $this->middleware('can:view invoices list')->only(['index']);
        $this->middleware('can:create invoice')->only(['create', 'store']);
        $this->middleware('can:edit invoice')->only(['edit', 'update']);
        $this->middleware('can:delete invoice')->only('destroy');
    }

    public function index(Request $request)
    {
        // dd($request);
        $singleInvoices = Invoice::query()
            ->select([
                'id',
                'invoice_type',
                'price',
                'discount_value', 
                'tax_rate',
                'tax_value',
                'total_with_tax',
                'type',
                'doctor_id',
                'patient_id',
                'service_id',
                'section_id',
                'invoice_status',
                'created_at',
            ])


            ->with(['doctor:id'])
            ->with(['patient:id'])
            ->with(['service:id'])
            ->with(['section:id'])

            ->where ('invoice_type' , '=',   1)  // go get single services only




/// very important when searching in a relation inside another relation (nested relations)
            ->whereHas('patient' , fn ($query) => 
            $query->whereHas('translations' , fn ($query) => 
            $query->when($request->patient_name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
            ))

            ->whereHas('service' , fn ($query) => 
            $query->whereHas('translations' , fn ($query) => 
            $query->when($request->service_name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
            ))
        




            ->latest('id')
            ->paginate(10);

        return Inertia::render('Invoices/SingleServiceInvoice/Index', [
            'title' => 'Invoices',
            'items' => InvoiceResource::collection($singleInvoices),
            'headers' => [
                [
                    'label' => 'Patient Name',
                    'name' => 'patient',
                ],
                [
                    'label' => 'Service Name',
                    'name' => 'service',
                ],
                [
                    'label' => 'Doctor Name',
                    'name' => 'doctor',
                ],
                [
                    'label' => 'Section',
                    'name' => 'section',
                ],
                [
                    'label' => 'Price',
                    'name' => 'price',
                ],
                [
                    'label' => 'discount value',
                    'name' => 'discount_value',
                ],
                [
                    'label' => 'tax rate',
                    'name' => 'tax_rate',
                ],
                [
                    'label' => 'tax value',
                    'name' => 'tax_value',
                ],
                [
                    'label' => 'total with tax',
                    'name' => 'total_with_tax',
                ],

                [
                    'label' => 'Type',
                    'name' => 'type',
                ],
                [
                    'label' => 'Created At',
                    'name' => 'created_at',
                ],
                [
                    'label' => 'Actions',
                    'name' => 'actions',
                ],
            ],
            'filters' => (object) $request->all(),
            'routeResourceName' => $this->routeResourceName,
            'can' => [
                'create' => $request->user()->can('create invoice'),
            ],
            'method' => 'index',
        ]);
    }


    public function create()
    {

        $doctors = User::query()->select('id','section_id')->with('section')->role('Doctor')->get();    // to get only doctors from users table
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        // dd($doctors);
        return Inertia::render('Invoices/SingleServiceInvoice/Create', [
            'edit' => false,
            'title' => 'Add Single Service Invoice',
            'routeResourceName' => $this->routeResourceName,
            'doctors'  => DoctorResource::collection($doctors),
            'patients'  => PatientResource::collection($patients),
            'sections' => SectionResource::collection(Section::get(['id'])),
            'services' => ServiceResource::collection(Service::get(['id','price'])),
            'type' => [ ["id" => 1 , "name" =>'cash'] , ["id" => 2 , "name" =>'later'] ],
        ]);
    }

    public function store(SingleInvoicesRequest $request)
    {
        $invoice = $request->only('type','discount_value','price','tax_rate','tax_value','total_with_tax');
        $invoice['invoice_type'] = 1;
        $invoice['invoice_status'] = 1;
        $invoice['patient_id'] = $request->patient;
        $invoice['doctor_id'] = $request->doctor;
        $invoice['section_id'] = $request->section;
        $invoice['service_id'] = $request->service;

        $singleInvoice =Invoice::create($invoice);

    
        if ($singleInvoice->type == 1) {
            $fund_accounts = new FundAccount();
            $fund_accounts->date = date('Y-m-d');
            $fund_accounts->invoice_id = $singleInvoice->id;

            $fund_accounts->Debit = $singleInvoice->total_with_tax;
            $fund_accounts->credit = 0.00;
            $fund_accounts->save();
        } else {

            $patient_accounts = new PatientAccount();
            $patient_accounts->date = date('Y-m-d');
            $patient_accounts->invoice_id = $singleInvoice->id;

            $patient_accounts->patient_id = $singleInvoice->patient_id;

            $patient_accounts->Debit = $singleInvoice->total_with_tax;
            $patient_accounts->credit = 0.00;
            $patient_accounts->save();
        }

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }


    public function edit(Invoice $invoice)
    {
        // dd($invoice);
        $invoice->load(['patient:id', 'doctor:id' , 'section:id' ,'service:id']);
    
        $doctors = User::query()->select('id','section_id')->with('section')->role('Doctor')->get();    // to get only doctors from users table
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        // dd($doctors);
        return Inertia::render('Invoices/SingleServiceInvoice/Create', [
            'edit' => true,
            'title' => 'Edit Single Service',
            'item' => new InvoiceResource($invoice),
            'routeResourceName' => $this->routeResourceName,
            'doctors'  => DoctorResource::collection($doctors),
            'patients'  => PatientResource::collection($patients),
            'sections' => SectionResource::collection(Section::get(['id'])),
            'services' => ServiceResource::collection(Service::get(['id','price'])),
            'type' => [ ["id" => 1 , "name" =>'cash'] , ["id" => 2 , "name" =>'later'] ],
        ]);
    }

    public function update(SingleInvoicesRequest $request, Invoice $invoice)
    {

        // dd($request);
        
        $data = $request->only('type','discount_value','price','tax_rate','tax_value','total_with_tax');
        $data['invoice_type'] = 1;
        $data['invoice_status'] = 1;
        $data['patient_id'] = $request->patient;
        $data['doctor_id'] = $request->doctor;
        $data['section_id'] = $request->section;
        $data['service_id'] = $request->service;  // S in capital

        $invoice->update($data);


        
        if ($invoice->type == 1) {
            $fund_accounts = FundAccount::where('invoice_id', $invoice->id)->first();
            $fund_accounts->date = date('Y-m-d');
            $fund_accounts->invoice_id = $invoice->id;

            $fund_accounts->Debit = $invoice->total_with_tax;
            $fund_accounts->credit = 0.00;
            $fund_accounts->save();
        } else {
            $patient_accounts = PatientAccount::where('invoice_id', $invoice->id)->first();
            $patient_accounts->date = date('Y-m-d');
            $patient_accounts->invoice_id = $invoice->id;

            $patient_accounts->patient_id = $invoice->patient_id;

            $patient_accounts->Debit = 0.00;
            $patient_accounts->credit = $invoice->total_with_tax;
            $patient_accounts->save();
        }

        // $invoice->update($request->saveData());


        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
