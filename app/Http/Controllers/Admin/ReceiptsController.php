<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReceiptsRequest;
use App\Http\Resources\PatientResource;
use App\Http\Resources\ReceiptResource;
use App\Models\FundAccount;
use App\Models\PatientAccount;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReceiptsController extends Controller
{
    private string $routeResourceName = 'receipts';
    
    public function __construct()
    {
        $this->middleware('can:view receipts list')->only('index');
        $this->middleware('can:create receipt')->only(['create', 'store']);
        $this->middleware('can:edit receipt')->only(['edit', 'update']);
        $this->middleware('can:delete receipt')->only('destroy');
    }
    public function index(Request $request)
    {
        // dd($request);

        $receipts = Receipt::query()

        ->select([
            'id',
            'patient_id',
            'amount',
            'description',
            'created_at',
            ])

            ->with(['patient:id'])

            ->when( 
                $request->amount,
                fn (Builder $builder, $amount) =>  $builder->where('amount', $amount) 
                )

            ->whereHas('patient' , fn ($query) => 
            $query->whereHas('translations' , fn ($query) => 
            $query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
            ))

            ->latest('id')
            ->paginate(10);

        return Inertia::render('Admin/Accounts/Receipt/Index', [
            'title' => 'Receipts',
            'items' => ReceiptResource::collection($receipts),
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
                'create' => $request->user()->can('create receipt'),
            ],
            'method'=> 'index', // used in composable filters

        ]);
    }

    public function create()
    {
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        return Inertia::render('Admin/Accounts/Receipt/Create', [
            'edit' => false,
            'title' => 'Add Receipt',
            'routeResourceName' => $this->routeResourceName,
            'patients' => PatientResource::collection($patients),
        ]);
    }

    public function store(ReceiptsRequest $request)
    {

        $receipt = Receipt::create($request->all());

                  // store fund_accounts
                  $fund_accounts = new FundAccount();
                  $fund_accounts->date =date('y-m-d');
                  $fund_accounts->receipt_id = $receipt->id;
                  $fund_accounts->Debit = $request->amount;
                  $fund_accounts->credit = 0.00;
                  $fund_accounts->save();
                  // store patient_accounts
                  $patient_accounts = new PatientAccount();
                  $patient_accounts->date =date('y-m-d');
                  $patient_accounts->patient_id = $request->patient_id;
                  $patient_accounts->receipt_id = $receipt->id;
                  $patient_accounts->Debit = 0.00;
                  $patient_accounts->credit =$request->amount;
                  $patient_accounts->save();
      

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }

    public function edit(Receipt $receipt)
    {
        $receipt->load(['patient:id']);
        $patients = User::query()->select('id')->role('Patient')->get();   // to get only patients from users table

        return Inertia::render('Admin/Accounts/Receipt/Create', [
            'edit' => true,
            'title' => 'Edit Receipt',
            'item' => new ReceiptResource($receipt),
            'patients' => PatientResource::collection($patients),

            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function update(ReceiptsRequest $request, Receipt $receipt)
    {
        // dd($request);
        $receipt->update($request->all());

        $fund_accounts = FundAccount::where('receipt_id',$receipt->id)->first();
        $fund_accounts->date =date('y-m-d');
        $fund_accounts->receipt_id = $receipt->id;
        $fund_accounts->Debit = $request->amount;
        $fund_accounts->credit = 0.00;
        $fund_accounts->save();
        // store patient_accounts
        $patient_accounts = PatientAccount::where('receipt_id',$receipt->id)->first();
        $patient_accounts->date =date('y-m-d');
        $patient_accounts->patient_id = $request->patient_id;
        $patient_accounts->receipt_id = $receipt->id;
        $patient_accounts->Debit = 0.00;
        $patient_accounts->credit =$request->amount;
        $patient_accounts->save();


        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();

        return back()->with('success', 'User deleted successfully.');
    }

}
