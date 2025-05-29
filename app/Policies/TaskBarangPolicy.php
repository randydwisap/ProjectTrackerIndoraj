<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskBarang;

class TaskBarangPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskBarang.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskBarang.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskBarang.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskBarang.delete');
    }

}
