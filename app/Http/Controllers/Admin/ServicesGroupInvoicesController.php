<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServicesGroupInvoicesRequest;
use App\Http\Requests\Admin\servicesGroupsRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServicesGroupResource;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\Section;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicesGroupInvoicesController extends Controller
{
    private string $routeResourceName = 'servicesGroupInvoices';


    public function __construct()
    {
        $this->middleware('can:view invoices list')->only(['index']);
        $this->middleware('can:create invoice')->only(['create', 'store']);
        $this->middleware('can:edit invoice')->only(['edit', 'update']);
        $this->middleware('can:delete invoice')->only('destroy');
    }

    public function index(Request $request)
    {
        $groupInvoices = Invoice::query()
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
                'group_id',
                'section_id',
                'invoice_status',
                'created_at',
            ])


            ->with(['doctor:id'])
            ->with(['patient:id'])
            ->with(['group:id'])
            ->with(['section:id'])

            ->where ('invoice_type' , '=',   2)   // to get only services group invoices

        
            // ->when($request->price, fn (Builder $builder, $price) => $builder->where('price', 'like', "%{$price}%"))

            ->latest('id')
            ->paginate(10);

        return Inertia::render('Invoices/GroupInvoice/Index', [
            'title' => 'Invoices',
            'items' => InvoiceResource::collection($groupInvoices),
            'headers' => [
                [
                    'label' => 'Service Name',
                    'name' => 'group',
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


///////////  very important  notice that total before discount is in capital t in migration    modify it later
        return Inertia::render('Invoices/GroupInvoice/Create', [
            'edit' => false,
            'title' => 'Add Services Group Invoice',
            'routeResourceName' => $this->routeResourceName,
            'doctors'  => DoctorResource::collection($doctors),
            'patients'  => PatientResource::collection($patients),
            'sections' => SectionResource::collection(Section::get(['id'])),
            'groups' => ServicesGroupResource::collection(Group::get(['id','Total_before_discount', 'Total_before_discount','discount_value','tax_rate','Total_with_tax'])),
            'type' => [ ["id" => 1 , "name" =>'cash'] , ["id" => 2 , "name" =>'later'] ],
        ]);
    }

    public function store(ServicesGroupInvoicesRequest $request)
    {

        $invoice = $request->only('type','discount_value','price','tax_rate','tax_value','total_with_tax');
        $invoice['invoice_type'] = 2;
        $invoice['invoice_status'] = 1;
        $invoice['patient_id'] = $request->patient;
        $invoice['doctor_id'] = $request->doctor;
        $invoice['section_id'] = $request->section;
        $invoice['group_id'] = $request->group;

        Invoice::create($invoice);


        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }



    public function edit(Invoice $invoice)
    {
        $invoice->load(['patient:id', 'doctor:id' , 'section:id' ,'group:id']);
    
        $doctors = User::query()->select('id','section_id')->with('section')->role('Doctor')->get();    // to get only doctors from users table
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        // dd($doctors);
        return Inertia::render('Invoices/GroupInvoice/Create', [
            'edit' => true,
            'title' => 'Edit Services Group Invoice',
            'item' => new InvoiceResource($invoice),
            'routeResourceName' => $this->routeResourceName,
            'doctors'  => DoctorResource::collection($doctors),
            'patients'  => PatientResource::collection($patients),
            'sections' => SectionResource::collection(Section::get(['id'])),
            'groups' => ServicesGroupResource::collection(Group::get(['id','Total_before_discount', 'Total_before_discount','discount_value','tax_rate','Total_with_tax'])),
            'type' => [ ["id" => 1 , "name" =>'cash'] , ["id" => 2 , "name" =>'later'] ],
        ]);
    }

    public function update(ServicesGroupInvoicesRequest $request, Invoice $invoice)
    {

        // dd($request);
        
        $data = $request->only('type','discount_value','price','tax_rate','tax_value','total_with_tax');
        $data['invoice_type'] = 2;
        $data['invoice_status'] = 1;
        $data['patient_id'] = $request->patient;
        $data['doctor_id'] = $request->doctor;
        $data['section_id'] = $request->section;
        $data['group_id'] = $request->group;

        $invoice->update($data);


        // $invoice->update($request->saveData());


        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    // public function destroy(Invoice $invoice)
    // {
    //     $invoice->delete();

    //     return back()->with('success', 'User deleted successfully.');
    // }




}
