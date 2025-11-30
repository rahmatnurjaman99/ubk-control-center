<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FeeTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeTemplatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FeeTemplate');
    }

    public function view(AuthUser $authUser, FeeTemplate $feeTemplate): bool
    {
        return $authUser->can('View:FeeTemplate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FeeTemplate');
    }

    public function update(AuthUser $authUser, FeeTemplate $feeTemplate): bool
    {
        return $authUser->can('Update:FeeTemplate');
    }

    public function delete(AuthUser $authUser, FeeTemplate $feeTemplate): bool
    {
        return $authUser->can('Delete:FeeTemplate');
    }

    public function restore(AuthUser $authUser, FeeTemplate $feeTemplate): bool
    {
        return $authUser->can('Restore:FeeTemplate');
    }

    public function forceDelete(AuthUser $authUser, FeeTemplate $feeTemplate): bool
    {
        return $authUser->can('ForceDelete:FeeTemplate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FeeTemplate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FeeTemplate');
    }

    public function replicate(AuthUser $authUser, FeeTemplate $feeTemplate): bool
    {
        return $authUser->can('Replicate:FeeTemplate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FeeTemplate');
    }

}