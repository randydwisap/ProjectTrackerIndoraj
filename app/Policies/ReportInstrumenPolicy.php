<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReportInstrumen;

class ReportInstrumenPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('reportInstrumen.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('reportInstrumen.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('reportInstrumen.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('reportInstrumen.delete');
    }

}
