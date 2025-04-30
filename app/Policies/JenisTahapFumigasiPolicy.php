<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JenisTahapFumigasi;

class JenisTahapFumigasiPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('jenisTahapFumigasi.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('jenisTahapFumigasi.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('jenisTahapFumigasi.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('jenisTahapFumigasi.delete');
    }

}
