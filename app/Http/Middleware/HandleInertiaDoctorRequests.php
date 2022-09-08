<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\App;

class HandleInertiaDoctorRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

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
            'locale' => App::getLocale(),

            'menus' => [
                [
                    'label' => 'Dashboard',
                    'url' => route('doctor.dashboard'),
                    'isActive' => $request->routeIs('doctor.dashboard'),
                    'isVisible' => true,
                    'hasSubmenu' => false,
                ],
                [
                    'label' => 'Doctor diagnoses',
                    'isActive' => $request->routeIs('doctor.diagnoses.*'),
                    // 'isVisible' => $request->user()?->can('view diagnoses module'),
                    'isVisible' => true,  // to be changed after assign role to doctor in admin dashboard
                    'hasSubmenu' => true,
                    'open'=>false,
                    'subMenus' => [
                        [
                            'label' => 'diagnoses List',
                            'url' => route('doctor.diagnoses.index'),
                            'isActive' => $request->routeIs('doctor.diagnoses*'),
                            // 'isVisible' => $request->user()?->can('view diagnoses module'),
                            'isVisible' =>true , // to be changed after assign role to doctor in admin dashboard
                        ],
                    ],
                ],
               
                // [
                //     'label' => 'Profile',
                //     'url' => route('admin.profile.show'),
                //     'isActive' => $request->routeIs('admin.profile.show'),    // check if it is work
                //     'isVisible' => true,
                //     'hasSubmenu' => false,

                // ],
                


            ],
        ]);
    }
}
