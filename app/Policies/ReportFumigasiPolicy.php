<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReportFumigasi;

class ReportFumigasiPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('reportFumigasi.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('reportFumigasi.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('reportFumigasi.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('reportFumigasi.delete');
    }

}
