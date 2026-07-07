<?php

namespace App\Providers;

use App\Models\Stagiaire;
use App\Policies\StagiairePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // ⬇️ Ajoute cette ligne pour lier le modèle Stagiaire à sa politique
        Stagiaire::class => StagiairePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Tu peux définir ici d'autres Gates si nécessaire
        // Gate::define('...', function ($user, $model) { ... });
    }
}