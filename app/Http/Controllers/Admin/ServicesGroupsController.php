<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\servicesGroupsRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServicesGroupResource;
use App\Models\Group;
use App\Models\Service;
use App\Models\ServicesGroup;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


use Inertia\Inertia;


class ServicesGroupsController extends Controller
{
    private string $routeResourceName = 'servicesGroups';


    public function __construct()
    {
        $this->middleware('can:view services list')->only(['index']);
        $this->middleware('can:create service')->only(['create', 'store']);
        $this->middleware('can:edit service')->only(['edit', 'update']);
        $this->middleware('can:delete service')->only('destroy');
    }

    public function index(Request $request)
    {
        $ServicesGroup = Group::query()
            ->select([
                'id',
                // 'name',
                // 'notes',
                'Total_with_tax',
                'status',
                'created_at',

            ])
            ->with('services')
            /////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
            ->whereHas(
                'translations',
                fn ($query) =>
                $query->when($request->name, fn (Builder $builder, $name) => $builder->where('name', 'like', "%{$name}%"))
            )
                    ////////////////////////////////////////////////////////////////////////////////////      


                    /// on boolean cases it has to be like this , and we make scopes in model
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

        return Inertia::render('Services/ServicesGroup/Index', [
            'title' => 'Services Groups',
            'items' => ServicesGroupResource::collection($ServicesGroup),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                ],
                [
                    'label' => 'total with tax',
                    'name' => 'Total_with_tax',
                ],
                [
                    'label' => 'status',
                    'name' => 'status',
                ],
                [
                    'label' => 'notes',
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
                'create' => $request->user()->can('create service'),
            ],
            'method' => 'index',
        ]);
    }


    public function create()
    {
        $Services = Service::select('id','price','status')->latest()->get();
        // $groupService = Group::select('id')->latest()->get();  // problem in create page 

        // $groupService->load(['services:id']);  //problem in create page 
        // $Services->load(['groups:id']);  // problem in create page 


        return Inertia::render('Services/ServicesGroup/Create', [
            'edit' => false,
            'title' => 'Add Single Service',
            'routeResourceName' => $this->routeResourceName,
            'services' =>ServiceResource::collection($Services),
            // 'groupServiceCollection'=> ServicesGroupResource::collection($groupService)   //problem in create page 
        ]);
    }



    public function store(servicesGroupsRequest $request)
    {
// dd($request);
        $servicesGroup = Group::create($request->saveData());
        
        foreach($request->addedServices as $service)
        {
            $servicesGroup->services()->attach($service['id'],['quantity' => $service['quantity']]);

        }

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }


    public function edit(Group $group)
    {

        $Services = Service::select('id','price','status')->latest()->get();
        $servicesWithinGroup = $group->services;
        // $addedServices = Group::select('id')->latest()->get();;

        $group->load(['services:id']);

        $Services->load(['groups:id']); 


        return Inertia::render('Services/ServicesGroup/Create', [
            'edit' => true,
            'title' => 'Edit Services Group',
            'item' => new ServicesGroupResource($group),
            'services' =>ServiceResource::collection($Services),
            'routeResourceName' => $this->routeResourceName,
            'servicesWithinGroup'=> $servicesWithinGroup,
        ]);
    }


    public function update(servicesGroupsRequest $request, Group $group)
    {
        // dd($request);
        $group->update($request->saveData());

        // becauae we have pivot so we have to detach first the attach
        $group->services()->detach();
        foreach($request->addedServices as $service)
        {
           
            $group->services()->attach($service['id'],['quantity' => $service['quantity']]);

        }

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return back()->with('success', 'User deleted successfully.');
    }

}
