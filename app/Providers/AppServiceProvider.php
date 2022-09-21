<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;

// use App\Models\Category;
// use App\Models\CategoryProduct;
// use App\Models\Product;
// use App\Models\User;
// use Spatie\Permission\Models\Permission;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      

        // if (request()->is(['admin', 'admin/*'])) {
        //     Inertia::setRootView('admin.app');
        // }

        // Inertia::setRootView('app');        //// needs more work  
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        
        JsonResource::withoutWrapping();

        Model::unguard();

        // Password::defaults(function () {
        //     $rule = Password::min(8);

        //     return $this->app->isProduction()
        //         ? $rule->mixedCase()->number()->symbols()->uncompromised()
        //         : $rule;
        // });

        
        // Relation::enforceMorphMap([
        //     'category' => Category::class,
        //     'category_product' => CategoryProduct::class,
        //     'product' => Product::class,
        //     'user' => User::class,
        //     'permission'=>Permission::class,
        // ]);
    }  
}
