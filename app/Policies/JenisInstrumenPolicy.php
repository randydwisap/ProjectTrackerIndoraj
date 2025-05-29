<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JenisInstrumen;

class JenisInstrumenPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('jenisInstrumen.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('jenisInstrumen.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('jenisInstrumen.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('jenisInstrumen.delete');
    }

}
