<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Submission $submission): bool
    {
        return $user->hasPermissionTo('submission_show') || $submission->isAuthOwner();
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user): bool
    {
        return $user->hasPermissionTo('submission_store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Submission $submission): bool
    {
        return $submission->canEdit() && $user->hasPermissionTo('submission_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('submission_delete');
    }

    public function approveDeny(User $user, Submission $submission): bool
    {
        return $user->hasPermissionTo('approve_deny_submissions') && $submission->isAuthMaintainer();
    }

    public function addModerator(User $user, Submission $submission): bool
    {
        return $user->hasPermissionTo('approve_deny_submissions') && $submission->hasNoMaintainer();
    }
}
