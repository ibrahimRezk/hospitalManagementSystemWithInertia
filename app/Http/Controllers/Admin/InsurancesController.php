<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InsurancesRequest;
use App\Http\Resources\InsuranceResource;
use App\Models\Insurance;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InsurancesController extends Controller 
{

    private string $routeResourceName = 'insurances';

    public function __construct()
    {
        $this->middleware('can:view insurances list')->only('index');
        $this->middleware('can:create insurance')->only(['create', 'store']);
        $this->middleware('can:edit insurance')->only(['edit', 'update']);
        $this->middleware('can:delete insurance')->only('destroy');
    }
    public function index(Request $request)
    {
        // dd($request);

        $insurances = Insurance::query()

        ->select([
            'id',
            'insurance_code',
            'discount_percentage',
            'Company_rate',
            'status',
            'created_at',
            ])

/////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
             ->whereHas('translations' , fn ($query) => 

             $query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
             )
 ////////////////////////////////////////////////////////////////////////////////////



            ->when($request->insurance_code, fn (Builder $builder, $insurance_code) => $builder->where('insurance_code', 'like', "%{$insurance_code}%"))

            ->when(
                $request->status !== null,
                fn (Builder $builder) => $builder->when(
                    $request->status,
                    fn (Builder $builder) => $builder->active(),
                    fn (Builder $builder) => $builder->inActive()
                )
            )

            ->latest('id')
            ->paginate(10);

        return Inertia::render('Services/Insurance/Index', [
            'title' => 'Insurance Companies',
            'items' => InsuranceResource::collection($insurances),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                ],
                [
                    'label' => 'Insurance Code',
                    'name' => 'insurance_code',
                ],
            
                [
                    'label' => 'Discount percentage',
                    'name' => 'discount_percentage',
                ],
                [
                    'label' => 'Company rate',
                    'name' => 'Company_rate',
                ],
                [
                    'label' => 'Status',
                    'name' => 'status',
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
                'create' => $request->user()->can('create insurance'),
            ],
            'method'=> 'index', // used in composable filters

        ]);
    }

    public function create()
    {
        return Inertia::render('Services/Insurance/Create', [
            'edit' => false,
            'title' => 'Add An Insurance Company',
            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function store(InsurancesRequest $request)
    {

        Insurance::create($request->saveData());

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }

    public function edit(Insurance $insurance)
    {
        return Inertia::render('Services/Insurance/Create', [
            'edit' => true,
            'title' => 'Edit Insurance company',
            'item' => new InsuranceResource($insurance),
            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function update(InsurancesRequest $request, Insurance $insurance)
    {
        $insurance->update($request->saveData());


        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        return back()->with('success', 'User deleted successfully.');
    }

}
