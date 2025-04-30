<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskDayDetail;

class TaskDayDetailPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskDayDetail.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskDayDetail.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskDayDetail.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskDayDetail.delete');
    }

}
