<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;

class TaskPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('task.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('task.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('task.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('task.delete');
    }

}
