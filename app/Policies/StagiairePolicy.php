<?php

namespace App\Policies;

use App\Models\Stagiaire;
use App\Models\User;

class StagiairePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Stagiaire $stagiaire): bool
    {
        return $user->id === $stagiaire->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Stagiaire $stagiaire): bool
    {
        return $user->id === $stagiaire->user_id;
    }

    public function delete(User $user, Stagiaire $stagiaire): bool
    {
        return $user->id === $stagiaire->user_id;
    }
}