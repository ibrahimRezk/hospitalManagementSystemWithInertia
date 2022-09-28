<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PatientAccountResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\ReceiptResource;
use App\Http\Resources\UserResource;
use App\Models\Invoice;
use App\Models\PatientAccount;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class PatientsController extends Controller
{
    private string $routeResourceName = 'patients';
    private string $role = 'Patient';

    public function __construct()
    {
        $this->middleware('can:view patients list')->only(['index', 'show']);
        $this->middleware('can:create patient')->only(['create', 'store']);
        $this->middleware('can:edit patient')->only(['edit', 'updatePatient']);
        $this->middleware('can:delete patient')->only('destroy');
    }

    // important note 
    // we use resourcename collection when date will come as an array of objects but we use new resourceName if it is only one object

    public function index(Request $request)
    {
        // dd($request);

        $patients = User::query()


            ->select([
                'id',
                // 'name',
                'email',
                'birth_date',
                'phone',
                'gender',
                'blood_group',
                'created_at'
            ])

            ->with('media')

            // to get only one kind of user depends on a role such as (admin , doctor , patient , ray empoyee  ..... )
            ->role($this->role)

            /////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
            ->whereHas(
                'translations',
                fn ($query) =>

                $query->when($request->name, fn (Builder $builder, $name) => $builder->where('name', 'like', "%{$name}%"))
            )
            ->whereHas(
                'translations',
                fn ($query) =>

                $query->when($request->address, fn (Builder $builder, $address) => $builder->where('address', 'like', "%{$address}%"))
            )
            ////////////////////////////////////////////////////////////////////////////////////

            ->when($request->email, fn (Builder $builder, $email) => $builder->where('email', 'like', "%{$email}%"))
            ->when($request->phone, fn (Builder $builder, $phone) => $builder->where('phone', 'like', "%{$phone}%"))
            ->when($request->gender, fn (Builder $builder, $gender) => $builder->where('gender', 'like', "%{$gender}%"))
            ->when($request->blood_group, fn (Builder $builder, $blood_group) => $builder->where('blood_group', 'like', "%{$blood_group}%"))

            ->latest('id')
            ->paginate(10);
        // dd($patients); 

        return Inertia::render('Admin/Patient/Index', [
            'title' => 'Patients',
            'items' => PatientResource::collection($patients),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                ],

                [
                    'label' => 'Date of Birth',
                    'name' => 'birth_date',
                ],
                [
                    'label' => 'phone number',
                    'name' => 'phone_number',
                ],
                [
                    'label' => 'Gender',
                    'name' => 'gender',
                ],
                [
                    'label' => 'Blood Group',
                    'name' => 'blood_group',
                ],

                [
                    'label' => 'Address',
                    'name' => 'address',
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
                'create' => $request->user()->can('create patient'),
            ],
            'method' => 'index',

        ]);
    }


    public function show(User $user)
    {

        $patient_invoices = Invoice::where('patient_id', '=', $user->id)
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
            ->with('media')
            
            ->latest()->paginate(1000);
        $patient_payments = Payment::query()->where('patient_id', '=', $user->id)->latest()->paginate(1000);
        $patient_receipts = Receipt::where('patient_id', '=', $user->id)->latest()->paginate(1000);

        $patient_account = PatientAccount::where('patient_id', "=", $user->id)
            ->select('id', 'patient_id', 'invoice_id', 'receipt_id', 'payment_id', 'Debit', 'credit', 'created_at')
            ->latest()->paginate(1000);



        $patient_invoices->load(['service']);
        $patient_invoices->load(['group']);

        $patient_account->load(['invoice']);
        $patient_account->load(['receipt']);
        $patient_account->load(['payment']);

        // we have to send data with all relations in invoice model to be recieved here   up  invoice_id  with invoice  and in invoice model we send all relations to get all data including names

        return Inertia::render('Admin/Patient/Show', [
            // 'title' => 'Patient Details',

            'patient'  => new PatientResource($user),
            'payments' => PaymentResource::collection($patient_payments),
            'invoices' => InvoiceResource::collection($patient_invoices),
            'receipts' => ReceiptResource::collection($patient_receipts),
            'statement' => PatientAccountResource::collection($patient_account),

            'headers' => [
                [
                    'label' => 'Patient Details',
                    'name' => 'patient_details',
                ],
                [
                    'label' => 'Invoices',
                    'name' => 'invoices',
                ],
                [
                    'label' => 'Payments',
                    'name' => 'payments',
                ],
                [
                    'label' => 'Statement',
                    'name' => 'statement',
                ],
                [
                    'label' => 'Radiology',
                    'name' => 'radiology',
                ],

                [
                    'label' => 'Laboratory',
                    'name' => 'laboratory',
                ],
            ],
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Patient/Create', [
            'edit' => false,
            'title' => 'Add Patient',
            'routeResourceName' => $this->routeResourceName,

        ]);
    }

    public function store(UsersRequest $request)
    {

        // $data = $request->safe()->only(['email', 'password' ,'phone']);
        // $data = $request->only(['email', 'birth_date', 'phone', 'gender', 'blood_group', 'password']);
        $data = $request->only(['email', 'birth_date', 'phone', 'gender', 'blood_group']);

        $data['password'] = $request->phone; /// to let patient log in to his account with his email and phone number as a password
        $data["ar"]["name"] = $request->name_ar;
        $data["en"]["name"] = $request->name_en;
        $data["ar"]["address"] = $request->address_ar;
        $data["en"]["address"] = $request->address_en;

        // we can use it like this 
        // $data['section_id'] = $request->section; 

        // dd($data);
        $user = User::create($data);

        $user->assignRole($this->role);  // to be changed

        if($request->hasFile('image')){
            $user->media()->delete();
            $user->addMediaFromRequest('image')
                ->withResponsiveImages() // this will create multipe sizes of the same image but it will take time on creating
                ->toMediaCollection();
        }

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }


    // // very important here we don't use route model binding becase we are looking in user table but url is doctor and it has to be the same so we search for the id 
    //     // public function edit($id)
    //     // $user= User::find($id);



    public function edit(User $user)
    {
        $user->load('media');

        return Inertia::render('Admin/Patient/Create', [
            'edit' => true,
            'title' => 'Edit Patient',
            'item' => new PatientResource($user),
            'routeResourceName' => $this->routeResourceName,

        ]);
    }


    public function updatePatient(UsersRequest $request,  $id)
    {
        $user = User::find($id);

        // user phone number is password phone must be long enoph or it will return an error on updating
        $data = $request->only(['email', 'birth_date', 'phone', 'gender', 'blood_group']);
        // dd($data);
        $data['password'] = $request->phone; /// to let patient log in to his account with his email and phone number as a password
        $data["ar"]['name'] = $request->name_ar;
        $data["en"]['name'] = $request->name_en;
        $data["ar"]["address"] = $request->address_ar;
        $data["en"]["address"] = $request->address_en;

        $user->update($data);

        if($request->hasFile('image')){
            $user->media()->delete();
            $user->addMediaFromRequest('image')
                ->withResponsiveImages() // this will create multipe sizes of the same image but it will take time on creating
                ->toMediaCollection();
        }

        $user->syncRoles($this->role);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
