<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskFumigasi;

class TaskFumigasiPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskFumigasi.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskFumigasi.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskFumigasi.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskFumigasi.delete');
    }

}
