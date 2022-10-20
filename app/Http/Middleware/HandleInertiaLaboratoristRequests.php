<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\App;

class HandleInertiaLaboratoristRequests extends Middleware
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

            'notificationsCount' => Notification::CountNotification(auth()->user()->id)->count(),
            'notifications' =>  Notification::where('user_id', auth()->user()->id)->where('read_status', 0)->latest()->take(5)->get() ,
            'auth' => [
                // 'user' => $request->user(),
                'token' => csrf_token(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
            ],
            'currentPage' => last(request()->segments()),
            'locale' => App::getLocale(),

            'menus' => [
                [
                    'label' => 'Dashboard',
                    'url' => route('laboratorist.dashboard'),
                    'isActive' => $request->routeIs('laboratorist.dashboard'),
                    'isVisible' => true,
                    'hasSubmenu' => false,
                ],
                [
                    'label' => 'Profile',
                    'url' => route('laboratorist.profile.show'),
                    'isActive' => $request->routeIs('laboratorist.profile.show'),    // check if it is work
                    'isVisible' => true,
                    'hasSubmenu' => false,

                ],
                [
                    'label' => 'Laboratories_list',
                    'isActive' => $request->routeIs('laboratorist.laboratories.*'),
                    // 'isVisible' => $request->user()?->can('view diagnoses module'),
                    'isVisible' => true,  // to be changed after assign role to doctor in admin dashboard
                    'hasSubmenu' => true,
                    'open'=>false,
                    'subMenus' => [
                        [
                            'label' => 'diagnoses_List',
                            'url' => route('laboratorist.laboratories.index'),
                            'isActive' => $request->routeIs('laboratorist.laboratories*'),
                            // 'isVisible' => $request->user()?->can('view diagnoses module'),
                            'isVisible' =>true , // to be changed after assign role to doctor in admin dashboard
                        ],
                        [
                            'label' => 'completed_diagnoses_List',
                            'url' => route('laboratorist.completedInvoices'),
                            'isActive' => $request->routeIs('laboratorist.completedInvoices*'),
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
