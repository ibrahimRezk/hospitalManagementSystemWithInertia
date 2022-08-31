<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AmbulancesRequest;
use App\Http\Resources\AmbulanceResource;
use App\Models\Ambulance;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AmbulancesController extends Controller
{
    

        private string $routeResourceName = 'ambulances';
    
        public function __construct()
        {
            $this->middleware('can:view ambulances list')->only('index');
            $this->middleware('can:create ambulance')->only(['create', 'store']);
            $this->middleware('can:edit ambulance')->only(['edit', 'update']);
            $this->middleware('can:delete ambulance')->only('destroy');
        }
        public function index(Request $request)
        {
            // dd($request);
    
            $ambulance = Ambulance::query()
    
            ->select([
                'id',
                'car_number',
                'car_model',
                'car_year_made',
                'driver_license_number',
                'driver_phone',
                'is_available',
                'car_type',
                'created_at',
                ])
    
    /////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
                 ->whereHas('translations' , fn ($query) => 
    
                 $query->when($request->driver_name, fn (Builder $builder, $driver_name) => $builder->where( 'driver_name' , 'like', "%{$driver_name}%"))
                 )
     ////////////////////////////////////////////////////////////////////////////////////
                ->when($request->car_number, fn (Builder $builder, $car_number) => $builder->where('car_number', 'like', "%{$car_number}%"))
                ->when($request->car_model, fn (Builder $builder, $car_model) => $builder->where('car_model', 'like', "%{$car_model}%"))
                ->when($request->driver_license_number, fn (Builder $builder, $driver_license_number) => $builder->where('driver_license_number', 'like', "%{$driver_license_number}%"))
                ->when($request->car_type, fn (Builder $builder, $car_type) => $builder->where('car_type', 'like', "%{$car_type}%"))

    
                ->when(
                    $request->is_available !== null,
                    fn (Builder $builder) => $builder->when(
                        $request->is_available,
                        fn (Builder $builder) => $builder->active(),
                        fn (Builder $builder) => $builder->inActive()
                    )
                )
    
                ->latest('id')
                ->paginate(10);
    

            return Inertia::render('Services/Ambulance/Index', [
                'title' => 'Insurance Companies',
                'items' => AmbulanceResource::collection($ambulance),
                'headers' => [
                    [
                        'label' => 'Car number',
                        'name' => 'car_number',
                    ],
                    [
                        'label' => 'Car model',
                        'name' => 'car_model',
                    ],
                
                    [
                        'label' => 'Car year made',
                        'name' => 'car_year_made',
                    ],
                    [
                        'label' => 'Car type',
                        'name' => 'car_type',
                    ],
                    [
                        'label' => 'Driver name',
                        'name' => 'driver_name',
                    ],
                    [
                        'label' => 'Driver license number',
                        'name' => 'driver_license_number',
                    ],
                    [
                        'label' => 'Driver phone',
                        'name' => 'driver_phone',
                    ],
                    [
                        'label' => 'Is available',
                        'name' => 'is_available',
                    ],
                    [
                        'label' => 'Notes',
                        'name' => 'notes',
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
                    'create' => $request->user()->can('create ambulance'),
                ],
                'method'=> 'index', // used in composable filters
    
            ]);
        }
    
        public function create()
        {
            return Inertia::render('Services/Ambulance/Create', [
                'edit' => false,
                'title' => 'Add An Ambulance',
                'routeResourceName' => $this->routeResourceName,
            ]);
        }
    
        public function store(AmbulancesRequest $request)
        {
    
            Ambulance::create($request->saveData());
    
            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
        }
    
        public function edit(Ambulance $ambulance)
        {
            return Inertia::render('Services/Ambulance/Create', [
                'edit' => true,
                'title' => 'Edit Ambulance',
                'item' => new AmbulanceResource($ambulance),
                'routeResourceName' => $this->routeResourceName,
            ]);
        }
    
        public function update(AmbulancesRequest $request, Ambulance $ambulance)
        {
            // dd($request);
            $ambulance->update($request->saveData());
    
    
            return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
        }
    
        public function destroy(Ambulance $ambulance)
        {
            $ambulance->delete();
    
            return back()->with('success', 'User deleted successfully.');
        }
    
    
}
