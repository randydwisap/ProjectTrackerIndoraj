<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LogAktivitas;

class LogAktivitasPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('logAktivitas.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('logAktivitas.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('logAktivitas.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('logAktivitas.delete');
    }

}
