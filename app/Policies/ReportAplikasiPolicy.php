<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReportAplikasi;

class ReportAplikasiPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('reportAplikasi.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('reportAplikasi.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('reportAplikasi.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('reportAplikasi.delete');
    }

}
