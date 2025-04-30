<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class MarketingPolicy
{
    /**
     * Determine whether the user can view any marketing records.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('marketing.view');
    }

    /**
     * Determine whether the user can create a marketing record.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('marketing.create');
    }

    /**
     * Determine whether the user can update a marketing record.
     */
    public function update(User $user)
    {
        return $user->hasPermissionTo('marketing.update');
    }

    /**
     * Determine whether the user can delete a marketing record.
     */
    public function delete(User $user)
    {
        return $user->hasPermissionTo('marketing.delete');
    }
}
