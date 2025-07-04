<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\RadiologistResource;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RadiologistsController extends Controller 
{
    private string $routeResourceName = 'radiologists';
    private string $role = 'Radiologist';

    public function __construct() 
    {
        $this->middleware('can:view radiologists list')->only('index');
        $this->middleware('can:create radiologist')->only(['create', 'store']);
        $this->middleware('can:edit radiologist')->only(['edit', 'updateRadiologist']);
        $this->middleware('can:delete radiologist')->only('destroy');
    }

    public function index(Request $request)
    {
        // dd($request);

        $radiologists = User::query()
        ->select([
            'id',
            // 'name',
            'email',
            'created_at',
            'phone',
            'status',
            ])

            ->with('media')

            // to get only one kind of userd depends on a role such as (admin , doctor , patient , ray empoyee  ..... )
            ->role($this->role)

/////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
             ->whereHas('translations' , fn ($query) => 

             $query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
             )
 ////////////////////////////////////////////////////////////////////////////////////

            ->when($request->email, fn (Builder $builder, $email) => $builder->where('email', 'like', "%{$email}%"))
            ->when($request->phone, fn (Builder $builder, $phone) => $builder->where('phone', 'like', "%{$phone}%"))


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
            // dd($doctors); 

        return Inertia::render('Admin/Radiologist/Index', [
            'title' => 'Radiologists',
            'items' => RadiologistResource::collection($radiologists),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                ],
                [
                    'label' => 'phone number',
                    'name' => 'phone_number',
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
                'create' => $request->user()->can('create radiology'),
            ],
            'method'=> 'index', 

        ]);
    }



    public function create() 
    {
        return Inertia::render('Admin/Radiologist/Create', [
            'edit' => false,
            'title' => 'Add Radiologist',
            'routeResourceName' => $this->routeResourceName,

        ]);
    }

    public function store(UsersRequest $request) 
    {


        // $data = $request->safe()->only(['email', 'password' ,'phone']);
        $data = $request->only(['email', 'password' ,'phone']);

        $data["ar"]["name"] = $request->name_ar;
        $data["en"]["name"] = $request->name_en;


        $user = User::create($data);
        $user->assignRole($this->role);

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

        return Inertia::render('Admin/Radiologist/Create', [ 
            'edit' => true,
            'title' => 'Edit Radiologist',
            'item' => new RadiologistResource($user),
            'routeResourceName' => $this->routeResourceName,

            
        ]);
    }

    public function updateRadiologist(UsersRequest $request,  $id)
    {

        $user = User::find($id);

        // review samir gamal method to make password nullable on update and update userRequest file
        $data = $request->safe()->only(['email', 'password' ,'phone']);
        
        $data["ar"]['name'] = $request->name_ar;
        $data["en"]['name'] = $request->name_en;
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
