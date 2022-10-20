<?php

namespace App\Http\Middleware;

use App\Models\Notification;
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
            //



            
///////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////// 
// remains : 
// UPDATE all requests to ignor translatios like in single service request like this  $service_ar = $this->service?->translate('ar')->id   ;
// add roles to all dashboards to prevent unauthorized access to it
// ,,,,,,,,,,,,,,,,, other enhancements like beginTransactions resource , requests , doctor modal request validation
// add patient name in add diagnose modal and other items in nenu
// remember to  add role middleware to invoke methods
// translate validation errors
// roles index page has no filters yet
// add button to reset all filters
// add service type " single or group " of the current invoice in doctor diagnoses page
// add filters in pages in doctor section completed incoices and index and review pages
// to remove validation errors in modal if we close the modal and re open it again just reset form errors witch come from handle inertia request in open modal method
///////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////


// when getting data inside vue page we use axios.get instead of inertia.get   refere to patient invoices page

 // add options to doctor completed and review to edit or add new things
                
// remember filters in accounts and invoices

// create new section in main menu for checkups for doctors and radiology section

// add status to services

// important review patient show   very interesting

// // resource collection  is important to get meta field in props.patient_radiology

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// to deal with date in vue js file like difforhuman in laravel   we use'dayjs'
// 1 -  npm install dayjs
// 2 - <script setup>
// import dayjs from 'dayjs';
// import relativeTime from 'dayjs/plugin/relativeTime';
// dayjs.extend(relativeTime);

// 3 - in template
// <small class="ml-2 text-sm text-gray-600">{{ dayjs(chirp.created_at).fromNow() }}</small>
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/// very important package for perfect scrolling
// https://github.com/mercs600/vue3-perfect-scrollbar


// perfect tailwind css resource
// https://github.com/mdbootstrap/Tailwind-Elements

        ]);
    }
}
