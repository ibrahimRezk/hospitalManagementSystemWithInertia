<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\App;

class HandleInertiaRadiologistRequests extends Middleware
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
                    'url' => route('radiologist.dashboard'),
                    'isActive' => $request->routeIs('radiologist.dashboard'),
                    'isVisible' => true,
                    'hasSubmenu' => false,
                ],
                [
                    'label' => 'Radiologies list',
                    'isActive' => $request->routeIs('radiologist.radiologies.*'),
                    // 'isVisible' => $request->user()?->can('view diagnoses module'),
                    'isVisible' => true,  // to be changed after assign role to doctor in admin dashboard
                    'hasSubmenu' => true,
                    'open'=>false,
                    'subMenus' => [
                        [
                            'label' => 'diagnoses List',
                            'url' => route('radiologist.radiologies.index'),
                            'isActive' => $request->routeIs('radiologist.radiologies*'),
                            // 'isVisible' => $request->user()?->can('view diagnoses module'),
                            'isVisible' =>true , // to be changed after assign role to doctor in admin dashboard
                        ],
                        [
                            'label' => 'completed diagnoses List',
                            'url' => route('radiologist.completedInvoices'),
                            'isActive' => $request->routeIs('radiologist.completedInvoices*'),
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
