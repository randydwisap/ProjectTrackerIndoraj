<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TaskAlihMedia;
use Illuminate\Auth\Access\Response;

class TaskAlihMediaPolicy
{
    /**
     * Determine whether the user can view any task alih media records.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('taskAlihMedia.view');
    }

    /**
     * Determine whether the user can view a specific task alih media record.
     */
    public function view(User $user, TaskAlihMedia $taskAlihMedia)
    {
        return $user->hasPermissionTo('taskAlihMedia.view');
    }

    /**
     * Determine whether the user can create a task alih media record.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('taskAlihMedia.create');
    }

    /**
     * Determine whether the user can update a task alih media record.
     */
    public function update(User $user, TaskAlihMedia $taskAlihMedia)
    {
        return $user->hasPermissionTo('taskAlihMedia.update');
    }

    /**
     * Determine whether the user can delete a task alih media record.
     */
    public function delete(User $user, TaskAlihMedia $taskAlihMedia)
    {
        return $user->hasPermissionTo('taskAlihMedia.delete');
    }

    /**
     * Determine whether the user can bulk delete task alih media records.
     */

}
