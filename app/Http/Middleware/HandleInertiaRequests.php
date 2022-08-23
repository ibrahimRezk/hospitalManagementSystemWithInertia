<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\App;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    // protected $rootView = 'app';
    protected $rootView = 'admin.app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // 'auth' => [
            //     'user' => $request->user(),
            // ],
            'flash' => [
                'success' => $request->session()->get('success'),
            ], 
            'currentPage' => last(request()->segments()),
            'locale'=> App::getLocale(),

            'menus' => [
                [
                    'label' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'isActive' => $request->routeIs('admin.dashboard'),
                    'isVisible' => true,
                ],
                [
                    'label' => 'Profile',
                    'url' => route('admin.profile.show'),
                    'isActive' => $request->routeIs('admin.profile.show'),    // check if it is work
                    'isVisible' => true,
                ],
                // [
                //     'label' => 'Permissions',
                //     'url' => route('admin.permissions.index'),
                //     'isActive' => $request->routeIs('admin.permissions.*'),
                //     'isVisible' => $request->user()?->can('view permissions module'),
                // ],
                // [
                //     'label' => 'Roles',
                //     'url' => route('admin.roles.index'),
                //     'isActive' => $request->routeIs('admin.roles.*'),
                //     'isVisible' => $request->user()?->can('view roles module'),
                // ],
            
                [
                    'label' => 'Sections',
                    'url' => route('admin.sections.index'),
                    'isActive' => $request->routeIs('admin.sections.*'),
                    'isVisible' => $request->user()?->can('view sections module'),
                ],
                [
                    'label' => 'Doctors',
                    'url' => route('admin.doctors.index'),
                    'isActive' => $request->routeIs('admin.doctors.*'),
                    'isVisible' => $request->user()?->can('view doctors module'),
                ],
                [
                    'label' => 'Users',
                    'url' => route('admin.users.index'),
                    'isActive' => $request->routeIs('admin.users.*'),
                    'isVisible' => $request->user()?->can('view users module'),
                ],
        
            ],
        ]);
    }
}
