<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\UserResource;
use App\Models\Section;
use Illuminate\Database\Eloquent\Builder; 
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SectionsController extends Controller
{
    private string $routeResourceName = 'sections';
    // private string $role = 'Super Admin';

    public function __construct()
    {
        $this->middleware('can:view sections list')->only('index');
        $this->middleware('can:create section')->only(['create', 'store']);
        $this->middleware('can:edit section')->only(['edit', 'update']);
        $this->middleware('can:delete section')->only('destroy');
    }

    public function index(Request $request)
    {
        $users = Section::query()
            ->select([
                'id',
                // 'name',
                // 'description',
                // 'email',
                'created_at', 
            ])
           
            // ->with(['roles:roles.id,roles.name'])

             /////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
             ->whereHas('translations' , fn ($query) => 

             $query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
             )

 ////////////////////////////////////////////////////////////////////////////////////


            // ->when( 
            //     $request->roleId,
            //     fn (Builder $builder, $roleId) => $builder->whereHas(
            //         'roles',
            //         fn (Builder $builder) => $builder->where('roles.id', $roleId)
            //     )
            // )
            
            ->when( 
                $request->sectionId,
                fn (Builder $builder, $sectionId) => $builder->whereHas(
                    'sections',
                    fn (Builder $builder) => $builder->where('sections.id', $sectionId)
                )
            )
            ->latest('id')
            ->paginate(10);

        return Inertia::render('Section/Index', [
            'title' => 'Sections',
            'items' => SectionResource::collection($users),
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
            // 'roles' => RoleResource::collection(Role::get(['id', 'name'])),
            // 'roles' => RoleResource::collection(Role::get(['id'])),
            'can' => [
                'create' => $request->user()->can('create section'),
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Section/Create', [
            'edit' => false,
            'title' => 'Add User',
            'routeResourceName' => $this->routeResourceName,
            'roles' => RoleResource::collection(Role::get(['id', 'name'])),
        ]);
    }

    public function store(UsersRequest $request)
    {
        $data = $request->safe()->only(['email', 'password']);

        $data["ar"]["name"] = $request->name_ar;
        $data["en"]["name"] = $request->name_en;
        $user = User::create($data);
        $user->assignRole($request->roleId);

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $user->load(['roles:roles.id']);

        return Inertia::render('Section/Create', [ 
            'edit' => true,
            'title' => 'Edit User',
            'item' => new UserResource($user),
            'routeResourceName' => $this->routeResourceName,
            'roles' => RoleResource::collection(Role::get(['id', 'name'])),
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
