<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskDayAlihMedia;

class TaskDayAlihMediaPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskDayAlihMedia.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskDayAlihMedia.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskDayAlihMedia.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskDayAlihMedia.delete');
    }

}
