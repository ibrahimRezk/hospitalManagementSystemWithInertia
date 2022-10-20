<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SingleServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SingleServicesController extends Controller 
{
    private string $routeResourceName = 'singleServices';


    public function __construct()
    {
        $this->middleware('can:view services list')->only(['index']);
        $this->middleware('can:create service')->only(['create', 'store']);
        $this->middleware('can:edit service')->only(['edit', 'update']);
        $this->middleware('can:delete service')->only('destroy');
    }

    public function index(Request $request)
    {
        $Services = Service::query()
            ->select([
                'id',
                'price',
                'status',
                'created_at',

            ])
            /////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
            ->whereHas(
                'translations',
                fn ($query) =>
                $query->when($request->name, fn (Builder $builder, $name) => $builder->where('name', 'like', "%{$name}%"))
            )
        

            ////////////////////////////////////////////////////////////////////////////////////      
           
            ->when($request->price, fn (Builder $builder, $price) => $builder->where('price', 'like', "%{$price}%"))


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

        return Inertia::render('Admin/Services/SingleService/Index', [
            'title' => 'Services',
            'items' => ServiceResource::collection($Services),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                ],
                [
                    'label' => 'Description',
                    'name' => 'description',
                ],
                [
                    'label' => 'price',
                    'name' => 'price',
                ],
                [
                    'label' => 'status',
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
                'create' => $request->user()->can('create service'),
            ],
            'method' => 'index',
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Services/SingleService/Create', [
            'edit' => false,
            'title' => 'Add Single Service',
            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function store(SingleServiceRequest $request)
    {


        Service::create($request->saveData());

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }

    public function edit(Service $service)
    {
        return Inertia::render('Admin/Services/SingleService/Create', [
            'edit' => true,
            'title' => 'Edit Single Service',
            'item' => new ServiceResource($service),
            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function update(SingleServiceRequest $request, Service $service)
    {
        // dd($service->translate('en')->id);

        $service->update($request->saveData());


        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
