<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JenisTahapAplikasi;

class JenisTahapAplikasiPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('jenisTahapAplikasi.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('jenisTahapAplikasi.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('jenisTahapAplikasi.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('jenisTahapAplikasi.delete');
    }

}
