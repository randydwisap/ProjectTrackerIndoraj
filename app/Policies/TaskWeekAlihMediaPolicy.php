<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskWeekAlihMedia;

class TaskWeekAlihMediaPolicy
{
    public function viewAny(User $user) {
        return $user->hasPermissionTo('taskWeekAlihMedia.view');
    }

    public function create(User $user) {
        return $user->hasPermissionTo('taskWeekAlihMedia.create');
    }

    public function update(User $user) {
        return $user->hasPermissionTo('taskWeekAlihMedia.update');
    }

    public function delete(User $user) {
        return $user->hasPermissionTo('taskWeekAlihMedia.delete');
    }

}
