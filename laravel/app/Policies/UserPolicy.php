<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can update another user.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        // Allow super-admin to update anyone
        if ($authUser->hasRole('super-admin')) {
            return true;
        }

        // Allow company-admin to update staff and other admins in their company
        if ($authUser->hasRole('company-admin')) {
            return $authUser->company_id === $targetUser->company_id;
        }

        // Staff can only update themselves
        return $authUser->id === $targetUser->id;
    }

    /**
     * Determine if the user can delete another user.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        // Allow super-admin to delete anyone
        if ($authUser->hasRole('super-admin')) {
            return true;
        }

        // Allow company-admin to delete staff and other admins in their company
        if ($authUser->hasRole('company-admin')) {
            // Prevent deleting themselves
            if ($authUser->id === $targetUser->id) {
                return false;
            }

            return $authUser->company_id === $targetUser->company_id;
        }

        // Staff cannot delete anyone
        return false;
    }
}
