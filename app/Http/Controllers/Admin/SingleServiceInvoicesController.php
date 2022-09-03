<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SingleInvoicesRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\ServiceResource;
use App\Models\Invoice;
use App\Models\Section;
use App\Models\Service;
use App\Models\User;
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


            // ->when($request->price, fn (Builder $builder, $price) => $builder->where('price', 'like', "%{$price}%"))

            ->latest('id')
            ->paginate(10);

        return Inertia::render('Invoices/SingleServiceInvoice/Index', [
            'title' => 'Invoices',
            'items' => InvoiceResource::collection($singleInvoices),
            'headers' => [
                [
                    'label' => 'Service Name',
                    'name' => 'service',
                ],
                [
                    'label' => 'Patient Name',
                    'name' => 'patient',
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
        $invoice['Service_id'] = $request->service;

        Invoice::create($invoice);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }


    public function edit(Invoice $invoice)
    {
    
        $doctors = User::query()->select('id','section_id')->with('section')->role('Doctor')->get();    // to get only doctors from users table
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        // dd($doctors);
        return Inertia::render('Invoices/SingleServiceInvoice/Create', [
            'edit' => false,
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

    // public function update(SingleServiceRequest $request, Service $service)
    // {
    //     $service->update($request->saveData());


    //     return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    // }

    // public function destroy(Service $service)
    // {
    //     $service->delete();

    //     return back()->with('success', 'User deleted successfully.');
    // }
}
