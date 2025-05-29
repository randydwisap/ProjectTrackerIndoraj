<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JenisBarang;

class JenisBarangPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('jenisBarang.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('jenisBarang.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('jenisBarang.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('jenisBarang.delete');
    }

}
