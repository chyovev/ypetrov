<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    ///////////////////////////////////////////////////////////////////////////
    /**
     * A user can delete another user as long as they
     * are not trying to delete themselves.
     * 
     * @param  User $user  – user doing the action
     * @param  User $model – user being deleted
     * @return bool
     */
    public function delete(User $user, User $model): bool {
        return $user->id !== $model->id;
    }
    
}
