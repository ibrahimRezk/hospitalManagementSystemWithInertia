<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\LaboratoristResource;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaboratoristsController extends Controller
{
    private string $routeResourceName = 'laboratorists';
    private string $role = 'Laboratorist';

    public function __construct()
    {
        $this->middleware('can:view laboratorists list')->only('index');
        $this->middleware('can:create laboratorist')->only(['create', 'store']);
        $this->middleware('can:edit laboratorist')->only(['edit', 'update']);
        $this->middleware('can:delete laboratorist')->only('destroy');
    }

    public function index(Request $request)
    {
        // dd($request);

        $laboratorists = User::query()
        ->select([
            'id',
            // 'name',
            'email',
            'created_at',
            'phone',
            'status',
            ])
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

        return Inertia::render('Admin/Laboratorist/Index', [
            'title' => 'Laboratorists',
            'items' => LaboratoristResource::collection($laboratorists),
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
        return Inertia::render('Admin/Laboratorist/Create', [
            'edit' => false,
            'title' => 'Add Laboratorist',
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

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }


// // very important here we don't use route model binding becase we are looking in user table but url is doctor and it has to be the same so we search for the id 
//     // public function edit($id)
//     // $user= User::find($id);



    public function edit(User $user)
    {
 

        return Inertia::render('Admin/Laboratorist/Create', [ 
            'edit' => true,
            'title' => 'Edit Laboratorist',
            'item' => new LaboratoristResource($user),
            'routeResourceName' => $this->routeResourceName,

            
        ]);
    }

    public function update(UsersRequest $request, User $user)
    {
        // review samir gamal method to make password nullable on update and update userRequest file
        $data = $request->safe()->only(['email', 'password' ,'phone']);
        
        $data["ar"]['name'] = $request->name_ar;
        $data["en"]['name'] = $request->name_en;
        $user->update($data);

        $user->syncRoles($this->role);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
