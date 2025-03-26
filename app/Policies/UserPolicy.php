<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
{
    return $user->hasPermissionTo('user.view'); // Pastikan permission ada
}

public function create(User $user)
{
    return $user->hasPermissionTo('user.create');
}

public function update(User $user)
{
    return $user->hasPermissionTo('user.update');
}

public function delete(User $user)
{
    return $user->hasPermissionTo('user.delete');
}
}
