<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TahfidzTarget;
use Illuminate\Auth\Access\HandlesAuthorization;

class TahfidzTargetPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TahfidzTarget');
    }

    public function view(AuthUser $authUser, TahfidzTarget $tahfidzTarget): bool
    {
        return $authUser->can('View:TahfidzTarget');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TahfidzTarget');
    }

    public function update(AuthUser $authUser, TahfidzTarget $tahfidzTarget): bool
    {
        return $authUser->can('Update:TahfidzTarget');
    }

    public function delete(AuthUser $authUser, TahfidzTarget $tahfidzTarget): bool
    {
        return $authUser->can('Delete:TahfidzTarget');
    }

    public function restore(AuthUser $authUser, TahfidzTarget $tahfidzTarget): bool
    {
        return $authUser->can('Restore:TahfidzTarget');
    }

    public function forceDelete(AuthUser $authUser, TahfidzTarget $tahfidzTarget): bool
    {
        return $authUser->can('ForceDelete:TahfidzTarget');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TahfidzTarget');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TahfidzTarget');
    }

    public function replicate(AuthUser $authUser, TahfidzTarget $tahfidzTarget): bool
    {
        return $authUser->can('Replicate:TahfidzTarget');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TahfidzTarget');
    }

}