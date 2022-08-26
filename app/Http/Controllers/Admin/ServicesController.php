<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicesController extends Controller
{
    private string $routeResourceName = 'services';


    public function __construct()
    {
        $this->middleware('can:view services list')->only(['index' ,'singleService']);
        $this->middleware('can:create service')->only(['create', 'store']);
        $this->middleware('can:edit service')->only(['edit', 'update']);
        $this->middleware('can:delete service')->only('destroy');
    }


    public function index()
    {
        return 'hbi';
    }

    public function show()
    {
        //
    }

    public function singleService(Request $request)
    {
        $Services = Service::query()
        ->select([
            'id',
            'price',
            'status',
            'created_at', 

            ])
/////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
->whereHas('translations' , fn ($query) => 
$query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
)
->whereHas('translations' , fn ($query) => 
$query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
)

////////////////////////////////////////////////////////////////////////////////////      
->when($request->status, fn (Builder $builder, $status) => $builder->where('status', 'like', "%{$status}%"))
->when($request->price, fn (Builder $builder, $price) => $builder->where('price', 'like', "%{$price}%"))

            ->latest('id')
            ->paginate(10);

        return Inertia::render('Services/SingleServices', [
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
            'method'=> 'singleService',
        ]);
    }
}
