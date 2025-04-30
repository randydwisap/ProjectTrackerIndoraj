<?php

namespace App\Providers;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Filament\Resources\Resource;
use App\Policies\UserPolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    // List resource yang butuh permission otomatis
    $resources = [
        'user', 'role' , 'marketing', 'taskAlihMedia', 'taskDayAlihMedia', 'taskWeekAlihMedia', 'jenisTaskAlihMedia',
        'taskAplikasi', 'reportAplikasi', 'jenisTahapAplikasi'
        , 'taskFumigasi', 'reportFumigasi', 'jenisTahapFumigasi'
        , 'task', 'taskDayDetail', 'taskWeekOverview', 'jenisTask'
    ];

    $permissions = ['view', 'create', 'update', 'delete'];

    foreach ($resources as $resource) {
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => "{$resource}.{$permission}", 'guard_name' => 'web']);
        }
    }
    }
}
