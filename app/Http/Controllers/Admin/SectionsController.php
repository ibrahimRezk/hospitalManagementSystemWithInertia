<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionsRequest;
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
        $sections = Section::query()
            ->select([
                'id',
                // name will come from section resource
                'created_at', 
            ])
           

/////////// very important her to add wherehas translation to call astrotomic translations /////////////////////////
            ->whereHas('translations' , fn ($query) => 

            $query->when($request->name, fn (Builder $builder, $name) => $builder->where( 'name' , 'like', "%{$name}%"))
            )
 ////////////////////////////////////////////////////////////////////////////////////

            
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
            'items' => SectionResource::collection($sections),
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
                'create' => $request->user()->can('create section'),
            ],
            'method'=> 'index',

        ]);
    }

    public function create()
    {
        return Inertia::render('Section/Create', [
            'edit' => false,
            'title' => 'Add section',
            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function store(SectionsRequest $request)
    {
        

        Section::create($request->saveData());

        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User created successfully.');
    }

    public function edit(Section $section)
    {

        return Inertia::render('Section/Create', [ 
            'edit' => true,
            'title' => 'Edit User',
            'item' => new SectionResource($section),
            'routeResourceName' => $this->routeResourceName,
        ]);
    }

    public function update(SectionsRequest $request , Section $section) 
    {
        
        $section->update($request->saveData());


        return redirect()->route("admin.{$this->routeResourceName}.index")->with('success', 'User updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return back()->with('success', 'User deleted successfully.');
    }

// to show all doctors depending on section name 
    // public function show($id)
    // {
    //     $doctors = Section::findOrFail($id)->doctors;
    //     $section = Section::findOrFail($id);
    //     return view('Dashboard.Sections.show_doctors', compact('doctors', 'section'));
    // }
}
