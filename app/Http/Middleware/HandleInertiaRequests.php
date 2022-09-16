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
                    'url' => route('admin.dashboard'),
                    'isActive' => $request->routeIs('admin.dashboard'),
                    'isVisible' => true,
                    'hasSubmenu' => false,
                ],
                [
                    'label' => 'Profile',
                    'url' => route('admin.profile.show'),
                    'isActive' => $request->routeIs('admin.profile.show'),    // check if it is work
                    'isVisible' => true,
                    'hasSubmenu' => false,

                ],
                [
                    'label' => 'Users',
                    'url' => route('admin.users.index'),
                    'isActive' => $request->routeIs('admin.users.*'),
                    'isVisible' => $request->user()?->can('view users module'),
                    'hasSubmenu' => false,

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
                    'hasSubmenu' => false,

                ],
                [
                    'label' => 'Doctors',
                    'url' => route('admin.doctors.index'),
                    'isActive' => $request->routeIs('admin.doctors.*'),
                    'isVisible' => $request->user()?->can('view doctors module'),
                    'hasSubmenu' => false,
                ],
                [
                    'label' => 'Patients',
                    'url' => route('admin.patients.index'),
                    'isActive' => $request->routeIs('admin.patients.*'),
                    'isVisible' => $request->user()?->can('view patients module'),
                    'hasSubmenu' => false,
                ],
                [
                    'label' => 'Radiologists',
                    'url' => route('admin.radiologists.index'),
                    'isActive' => $request->routeIs('admin.radiologists.*'),
                    'isVisible' => $request->user()?->can('view radiologists module'),
                    'hasSubmenu' => false,
                ],
                [
                    'label' => 'Laboratorists',
                    'url' => route('admin.laboratorists.index'),
                    'isActive' => $request->routeIs('admin.laboratorists.*'),
                    'isVisible' => $request->user()?->can('view laboratorists module'),
                    'hasSubmenu' => false,
                ],
            
                [
                    'label' => 'Services',
                    // 'url' => route('admin.singleServices.index'),
                    'isActive' => $request->routeIs('admin.services.*'),
                    'isVisible' => $request->user()?->can('view services module'),
                    'hasSubmenu' => true,
                    'open'=>false,
                    'subMenus' => [
                        [
                            'label' => 'Single Services',
                            'url' => route('admin.singleServices.index'),
                            'isActive' => $request->routeIs('admin.singleService*'),
                            'isVisible' => $request->user()?->can('view services module'),
                        ],
                        [
                            'label' => 'Group Services',
                            'url' => route('admin.servicesGroups.index'),
                            'isActive' => $request->routeIs('admin.servicesGroups*'),
                            'isVisible' => $request->user()?->can('view services module'),
                        ],
                        [
                            'label' => 'Insurance Companies',
                            'url' => route('admin.insurances.index'),
                            'isActive' => $request->routeIs('admin.insurances*'),
                            'isVisible' => $request->user()?->can('view insurances module'),
                        ],
                        [
                            'label' => 'Ambulances',
                            'url' => route('admin.ambulances.index'),
                            'isActive' => $request->routeIs('admin.ambulances*'),
                            'isVisible' => $request->user()?->can('view ambulances module'),
                        ],
                    ]

                ],


                [
                    'label' => 'Invoices',
                    'isActive' => $request->routeIs('admin.invoices.*'),
                    'isVisible' => $request->user()?->can('view invoices module'),
                    'hasSubmenu' => true,
                    'open'=>false,
                    'subMenus' => [
                        [
                            'label' => 'Single Invoice',
                            'url' => route('admin.singleInvoices.index'),
                            'isActive' => $request->routeIs('admin.singleInvoices*'),
                            'isVisible' => $request->user()?->can('view invoices module'),
                        ],
                        [
                            'label' => 'Group Invoices',
                            'url' => route('admin.servicesGroupInvoices.index'),
                            'isActive' => $request->routeIs('admin.servicesGroupInvoices*'),
                            'isVisible' => $request->user()?->can('view invoices module'), 
                        ],

                    ]

                ],
                [
                    'label' => 'Accounts',
                    'isActive' => $request->routeIs('admin.accounts.*'),
                    'isVisible' => $request->user()?->can('view accounts module'),
                    'hasSubmenu' => true,
                    'open'=>false,
                    'subMenus' => [
                        [
                            'label' => 'Receipts',
                            'url' => route('admin.receipts.index'),
                            'isActive' => $request->routeIs('admin.receipts*'),
                            'isVisible' => $request->user()?->can('view receipts module'), 
                        ],
                        [
                            'label' => 'Payments',
                            'url' => route('admin.payments.index'),
                            'isActive' => $request->routeIs('admin.payments*'),
                            'isVisible' => $request->user()?->can('view payments module'),
                        ],
                    ]
                ],

                // add options to doctor completed and review to edit or add new things
                
// remember filters in accounts and invoices

// create new section in main menu for checkups for doctors and radiology section


// add status to services

// important review patient show   very interesting

// // resource collection  is important to get meta field in props.patient_radiology

            ],
        ]);
    }
}
