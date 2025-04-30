<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JenisTaskAlihMedia;

class JenisTaskAlihMediaPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('jenisTaskAlihMedia.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('jenisTaskAlihMedia.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('jenisTaskAlihMedia.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('jenisTaskAlihMedia.delete');
    }

}
