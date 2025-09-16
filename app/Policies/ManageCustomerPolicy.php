<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ManageCustomer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ManageCustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_manage::customer');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ManageCustomer $manageCustomer): bool
    {
        return $user->can('view_manage::customer');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_manage::customer');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ManageCustomer $manageCustomer): bool
    {
        return $user->can('update_manage::customer');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ManageCustomer $manageCustomer): bool
    {
        return $user->can('delete_manage::customer');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_manage::customer');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, ManageCustomer $manageCustomer): bool
    {
        return $user->can('force_delete_manage::customer');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_manage::customer');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, ManageCustomer $manageCustomer): bool
    {
        return $user->can('restore_manage::customer');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_manage::customer');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, ManageCustomer $manageCustomer): bool
    {
        return $user->can('replicate_manage::customer');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_manage::customer');
    }
}
