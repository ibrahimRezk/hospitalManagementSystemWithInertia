<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PaymentsRequest;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PaymentResource;
use App\Models\FundAccount;
use App\Models\PatientAccount;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentsController extends Controller
{
    private string $routeResourceName = 'payments';
    
    public function __construct()
    {
        $this->middleware('can:view payments list')->only('index');
        $this->middleware('can:create payment')->only(['create', 'store']);
        $this->middleware('can:edit payment')->only(['edit', 'update']);
        $this->middleware('can:delete payment')->only('destroy');
    }
    public function index(Request $request)
    {
        // dd($request);

        $payments = Payment::query()

        ->select([
            'id',
            'patient_id',
            'amount',
            'description',
            'created_at',
            ])

            ->with(['patient:id'])

            // ->when( 
            //     $request->patient_id,
            //     fn (Builder $builder, $patient_id) =>  $builder->where( 'patient_id', $patient_id)
            //     )

            // ->when($request->car_number, fn (Builder $builder, $car_number) => $builder->where('car_number', 'like', "%{$car_number}%"))


            ->latest('id')
            ->paginate(10);

        return Inertia::render('Accounts/Payment/Index', [
            'title' => 'Receipts',
            'items' => PaymentResource::collection($payments),
            'headers' => [
                [
                    'label' => 'Patient Name',
                    'name' => 'patient_name',
                ],
                [
                    'label' => 'Amount',
                    'name' => 'amount',
                ],
                [
                    'label' => 'Description',
                    'name' => 'description',
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
                'create' => $request->user()->can('create payment'),
            ],
            'method'=> 'index', // used in composable filters

        ]);
    }

    public function create()
    {
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        return Inertia::render('Accounts/Payment/Create', [
            'edit' => false,
            'title' => 'Add Payment',
            'routeResourceName' => $this->routeResourceName,
            'patients' => PatientResource::collection($patients),
        ]);
    }

    public function store(PaymentsRequest $request)
    {

        // store receipt_accounts
        $payment = new Payment();
        $payment->patient_id = $request->patient_id;
        $payment->amount = $request->amount;
        $payment->description = $request->description;
        $payment->save();

        // store fund_accounts
        $fund_accounts = new FundAccount();
        $fund_accounts->date =date('y-m-d');
        $fund_accounts->Payment_id = $payment->id;
        $fund_accounts->credit = $request->amount;
        $fund_accounts->Debit = 0.00;
        $fund_accounts->save();

        // store patient_accounts
        $patient_accounts = new PatientAccount();
        $patient_accounts->date =date('y-m-d');
        $patient_accounts->patient_id = $request->patient_id;
        $patient_accounts->Payment_id = $payment->id;
        $patient_accounts->Debit = $request->amount;
        $patient_accounts->credit = 0.00;
        $patient_accounts->save();

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }

    public function edit(Payment $payment)
    {
        $payment->load(['patient:id']);
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        return Inertia::render('Accounts/Payment/Create', [
            'edit' => true,
            'title' => 'Edit Payment',
            'item' => new PaymentResource($payment),
            'patients' => PatientResource::collection($patients),

            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function update(PaymentsRequest $request, Payment $payment)
    {

        $payment->update($request->all());
        
        // $payment = Payment::findorfail($request->id);
        // $payment->patient_id = $request->patient_id;
        // $payment->amount = $request->amount;
        // $payment->description = $request->description;
        // $payment->save();

        // update fund_accounts
        $fund_accounts = FundAccount::where('Payment_id',$payment->id)->first();  /// modify Payment to be in small letters
        $fund_accounts->date =date('y-m-d');
        $fund_accounts->Payment_id = $payment->id;     /// modify Payment to be in small letters
        $fund_accounts->credit = $request->amount;
        $fund_accounts->Debit = 0.00;  /// modify Debit to be in small letters
        $fund_accounts->save();

        // update patient_accounts
        $patient_accounts = PatientAccount::where('payment_id',$payment->id)->first();
        $patient_accounts->date =date('y-m-d');
        $patient_accounts->patient_id = $request->patient_id;
        $patient_accounts->payment_id = $payment->id;
        $patient_accounts->Debit = $request->amount;
        $patient_accounts->credit = 0.00;
        $patient_accounts->save();

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back()->with('success', 'User deleted successfully.');
    }

}
