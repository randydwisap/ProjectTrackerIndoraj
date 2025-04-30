<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskAplikasi;

class TaskAplikasiPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskAplikasi.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskAplikasi.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskAplikasi.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskAplikasi.delete');
    }

}
