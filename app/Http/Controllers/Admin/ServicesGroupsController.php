<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            ->when($request->status, fn (Builder $builder, $status) => $builder->where('status', 'like', "%{$status}%"))

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
        $groupService = Group::select('id')->latest()->get();;

        // dd($groupService);
        $groupService->load(['services:id']);
        $Services->load(['groups:id']); 


        return Inertia::render('Services/ServicesGroup/Create', [
            'edit' => false,
            'title' => 'Add Single Service',
            'routeResourceName' => $this->routeResourceName,
            'services' =>ServiceResource::collection($Services),
            'groupServiceCollection'=> ServicesGroupResource::collection($groupService)
        ]);
    }

}
