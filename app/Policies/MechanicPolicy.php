<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mechanic;
use Illuminate\Auth\Access\HandlesAuthorization;

class MechanicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_mechanic');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mechanic $mechanic): bool
    {
        return $user->can('view_mechanic');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_mechanic');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mechanic $mechanic): bool
    {
        return $user->can('update_mechanic');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mechanic $mechanic): bool
    {
        return $user->can('delete_mechanic');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_mechanic');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Mechanic $mechanic): bool
    {
        return $user->can('force_delete_mechanic');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_mechanic');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Mechanic $mechanic): bool
    {
        return $user->can('restore_mechanic');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_mechanic');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Mechanic $mechanic): bool
    {
        return $user->can('replicate_mechanic');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_mechanic');
    }
}
