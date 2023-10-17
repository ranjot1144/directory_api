<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Website;
use Illuminate\Auth\Access\HandlesAuthorization;


class WebsitePolicy
{
    /**
     * Create a new policy instance.
     */
    public function update(User $user, Website $website)
    {
        // Allow a user to update a website if they own it
        return $user->id === $website->user_id;
    }

    public function delete(User $user, Website $website)
    {
        // Allow a user to delete a website if they own it
        return $user->id === $website->user_id;
    }
}
