<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JenisTask;

class JenisTaskPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('jenisTask.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('jenisTask.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('jenisTask.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('jenisTask.delete');
    }

}
