<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class PatientsController extends Controller
{
    private string $routeResourceName = 'patients';
    private string $role = 'Patient';

    public function __construct()
    {
        $this->middleware('can:view patients list')->only('index');
        $this->middleware('can:create patient')->only(['create', 'store']);
        $this->middleware('can:edit patient')->only(['edit', 'update']);
        $this->middleware('can:delete patient')->only('destroy');
    }

    public function index(Request $request)
    {
        // dd($request);

        $patients = User::query()
        
        
        ->select([
            'id',
            // 'name',
            'email',
            'birth_date',
            'phone',
            'gender',
            'blood_group',
            'created_at'
            ])

            // to get only one kind of user depends on a role such as (admin , doctor , patient , ray empoyee  ..... )
            ->role($this->role)


/////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
             ->whereHas('translations' , fn ($query) => 

             $query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
             )
             ->whereHas('translations' , fn ($query) => 

             $query->when($request->address, fn (Builder $builder, $address) => $builder->where( 'address' , 'like', "%{$address}%"))
             )
 ////////////////////////////////////////////////////////////////////////////////////



            ->when($request->email, fn (Builder $builder, $email) => $builder->where('email', 'like', "%{$email}%"))
            ->when($request->phone, fn (Builder $builder, $phone) => $builder->where('phone', 'like', "%{$phone}%"))
            ->when($request->gender, fn (Builder $builder, $gender) => $builder->where('gender', 'like', "%{$gender}%"))
            ->when($request->blood_group, fn (Builder $builder, $blood_group) => $builder->where('blood_group', 'like', "%{$blood_group}%"))


            
            ->latest('id')
            ->paginate(10);
            // dd($patients); 


        return Inertia::render('Patient/Index', [
            'title' => 'Patients',
            'items' => PatientResource::collection($patients),
            'headers' => [
                [
                    'label' => 'Name',
                    'name' => 'name',
                ],
                // [
                //     'label' => 'Email',
                //     'name' => 'email',
                // ],
                [
                    'label' => 'Date of Birth',
                    'name' => 'birth_date',
                ],
                [
                    'label' => 'phone number',
                    'name' => 'phone_number',
                ],
                [
                    'label' => 'Gender',
                    'name' => 'gender',
                ],
                [
                    'label' => 'Blood Group',
                    'name' => 'blood_group',
                ],

                [
                    'label' => 'Address',
                    'name' => 'address',
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
                'create' => $request->user()->can('create patient'),
            ],
            'method'=> 'index',

        ]);
    }



    public function create() 
    {
        return Inertia::render('Patient/Create', [
            'edit' => false,
            'title' => 'Add Patient',
            'routeResourceName' => $this->routeResourceName,
            
        ]);
    }

    public function store(UsersRequest $request) 
    {
        // dd($request);

        // $data = $request->safe()->only(['email', 'password' ,'phone']);
        $data = $request->only(['email', 'birth_date' ,'phone' ,'gender','blood_group','password']);

        $data["ar"]["name"] = $request->name_ar;
        $data["en"]["name"] = $request->name_en;
        $data["ar"]["address"] = $request->address_ar;
        $data["en"]["address"] = $request->address_en;

        // we can use it like this 
        // $data['section_id'] = $request->section; 

        $user = User::create($data);
        
        $user->assignRole(4);  // to be changed

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }


// // very important here we don't use route model binding becase we are looking in user table but url is doctor and it has to be the same so we search for the id 
//     // public function edit($id)
//     // $user= User::find($id);



    public function edit(User $user)
    {

        return Inertia::render('Patient/Create', [ 
            'edit' => true,
            'title' => 'Edit Patient',
            'item' => new PatientResource($user),
            'routeResourceName' => $this->routeResourceName,

            
        ]);
    }

    public function update(UsersRequest $request, User $user)
    {
        // review samir gamal method to make password nullable on update and update userRequest file
        $data = $request->safe()->only(['email', 'birth_date' ,'phone' ,'gender','blood_group','password']);
        
        $data["ar"]['name'] = $request->name_ar;
        $data["en"]['name'] = $request->name_en;
        $data["ar"]["address"] = $request->address_ar;
        $data["en"]["address"] = $request->address_en;

        $user->update($data);

        $user->syncRoles(4);  // to be changed

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
