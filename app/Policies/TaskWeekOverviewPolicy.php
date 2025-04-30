<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskWeekOverview;

class TaskWeekOverviewPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskWeekOverview.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskWeekOverview.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskWeekOverview.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskWeekOverview.delete');
    }
}
