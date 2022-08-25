<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DoctorsRequest;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\UserResource;
use App\Models\Section;
use Illuminate\Database\Eloquent\Builder; 
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DoctorsController extends Controller
{
    private string $routeResourceName = 'doctors';
    private string $role = 'Doctor';

    public function __construct()
    {
        $this->middleware('can:view doctors list')->only('index');
        $this->middleware('can:create doctor')->only(['create', 'store']);
        $this->middleware('can:edit doctor')->only(['edit', 'update']);
        $this->middleware('can:delete doctor')->only('destroy');
    }

    public function index(Request $request)
    {
        // dd($request);

        $doctors = User::query()
        
        
        ->select([
            'id',
            // 'name',
            'email',
            'created_at',
            // it is very important to put section_id here otherwise the relation will return on null 
            'section_id',
            'phone',
            'status',
            ])

            ->with(['section:id'])
            
            // to get only one kind of userd depends on a role such as (admin , doctor , patient , ray empoyee  ..... )
            ->role($this->role)


/////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
             ->whereHas('translations' , fn ($query) => 

             $query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
             )
 ////////////////////////////////////////////////////////////////////////////////////



            ->when($request->email, fn (Builder $builder, $email) => $builder->where('email', 'like', "%{$email}%"))


            // ->when( 
            //     $request->roleId,
            //     fn (Builder $builder, $roleId) => $builder->whereHas(
            //         'roles',
            //         fn (Builder $builder) => $builder->where('roles.id', $roleId)
            //     )
            // )

            // down here we are looking for section_id column depending on sectionId witch comming from request
            ->when( 
                $request->sectionId,
                fn (Builder $builder, $sectionId) =>  $builder->where( 'section_id', $sectionId)
                )
            
            ->latest('id')
            ->paginate(10);
            // dd($doctors); 


        return Inertia::render('Doctor/Index', [
            'title' => 'Doctors',
            'items' => DoctorResource::collection($doctors),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                ],
                // [
                //     'label' => 'Email',
                //     'name' => 'headers',
                // ],
                [
                    'label' => 'section',
                    'name' => 'section',
                ],
                [
                    'label' => 'phone number',
                    'name' => 'phone_number',
                ],
                [
                    'label' => 'dates',
                    'name' => 'dates',
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
            'sections' => SectionResource::collection(Section::get(['id'])),
            'roles' => RoleResource::collection(Role::get(['id'])),
            'can' => [
                'create' => $request->user()->can('create doctor'),
            ],
        ]);
    }



    public function create()
    {
        return Inertia::render('Doctor/Create', [
            'edit' => false,
            'title' => 'Add Doctor',
            'routeResourceName' => $this->routeResourceName,
            'roles' => RoleResource::collection(Role::get(['id', 'name'])),

            'sections' => SectionResource::collection(Section::get(['id'])),
            // 'appointments' =>
        ]);
    }

    public function store(UsersRequest $request)
    {
        //remember to use section_id not section because in database column name is section_id

        // $data = $request->safe()->only(['email', 'password' ,'phone']);
        $data = $request->only(['email', 'password' ,'phone' ,'section_id']);

        $data["ar"]["name"] = $request->name_ar;
        $data["en"]["name"] = $request->name_en;

        // we can use it like this 
        // $data['section_id'] = $request->section; 

        $user = User::create($data);
        $user->assignRole($request->roleId);
        // $appointments // to be done

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }


// very important here we don't use route model binding becase we are looking in user table but url is doctor and it has to be the same so we search for the id 
    // public function edit($id)
    // $user= User::find($id);



    public function edit(User $user)
    {
        $user->load(['roles:roles.id']);
        $user->load(['section:id']);

        return Inertia::render('Doctor/Create', [ 
            'edit' => true,
            'title' => 'Edit User',
            'item' => new UserResource($user),
            'routeResourceName' => $this->routeResourceName,
            'roles' => RoleResource::collection(Role::get(['id', 'name'])),
            'sections' => SectionResource::collection(Section::get(['id'])),
            // 'appointments' =>

            
        ]);
    }

    public function update(UsersRequest $request, User $user)
    {
        // review samir gamal method to make password nullable on update and update userRequest file
        $data = $request->safe()->only(['email', 'password']);
        
        $data["ar"]['name'] = $request->name_ar;
        $data["en"]['name'] = $request->name_en;
        $user->update($data);

        $user->syncRoles($request->roleId);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
