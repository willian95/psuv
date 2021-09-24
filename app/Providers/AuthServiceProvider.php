<?php

namespace App\Providers;

use App\Models\{Manager, Order, User};
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('show-order', function (User $user, Order $order) {
            $roleNames = $user->getRoleNames();
            $check = false;

            if ($roleNames->contains('admin')) {
                $check = true;
            }elseif ($roleNames->contains('client')) {
                if ($roleNames->contains('client.person')) {
                    $person = $user->person;
                    $check = $person->id == $order->people_id;
                }else{
                    $company = $user->company;
                    $check = $company->id == $order->company_id;
                }
            }else{
                $manager = $user->manager;
                
                $check = $order->managers()->wherePivot('manager_id', $manager->id)->exists();
            }
            
            return $check ? Response::allow()
            : Response::deny('No estas autorizado para ver este tramite.');
        });

        Gate::define('approved-manager', function (User $user, Manager $manager) {
            $check = false;
            if ($user->id == $manager->user_id && $manager->approved) {
                $check = true;
            }
            return $check ? Response::allow()
            : Response::deny('Tu perfil no ha sido aprobado por un administrador.', 400);
        });

        Gate::define('has-order', function (User $user, Manager $manager) {
            $check = false;
            $user->loadProfile();
            $profile_name = '';
            $profile = null;

            if ($user->relationLoaded('company')) {
                $profile = $user->company;
                $profile_name = 'company_id';
            }elseif ($user->relationLoaded('person')) {
                $profile = $user->person;
                $profile_name = 'people_id';
            }

            if($profile){
                $check = $manager->orders()->where($profile_name,$profile->id)->exists();
            }

            return $check ? Response::allow()
            : Response::deny('No posees ningun tramite con este tramitador.');
        });

        Gate::define('has-manager', function (User $user, Manager $manager){
            return $user->id == $manager->user_id ? Response::allow()
            : Response::deny('No posees ningun manager con ese id',404);
        });
    }
}
