<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RegistrationIntake;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrationIntakePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RegistrationIntake');
    }

    public function view(AuthUser $authUser, RegistrationIntake $registrationIntake): bool
    {
        return $authUser->can('View:RegistrationIntake');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RegistrationIntake');
    }

    public function update(AuthUser $authUser, RegistrationIntake $registrationIntake): bool
    {
        return $authUser->can('Update:RegistrationIntake');
    }

    public function delete(AuthUser $authUser, RegistrationIntake $registrationIntake): bool
    {
        return $authUser->can('Delete:RegistrationIntake');
    }

    public function restore(AuthUser $authUser, RegistrationIntake $registrationIntake): bool
    {
        return $authUser->can('Restore:RegistrationIntake');
    }

    public function forceDelete(AuthUser $authUser, RegistrationIntake $registrationIntake): bool
    {
        return $authUser->can('ForceDelete:RegistrationIntake');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RegistrationIntake');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RegistrationIntake');
    }

    public function replicate(AuthUser $authUser, RegistrationIntake $registrationIntake): bool
    {
        return $authUser->can('Replicate:RegistrationIntake');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RegistrationIntake');
    }

}