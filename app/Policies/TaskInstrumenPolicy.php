<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskInstrumen;

class TaskInstrumenPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskInstrumen.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskInstrumen.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskInstrumen.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskInstrumen.delete');
    }

}
