<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SubjectCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SubjectCategory');
    }

    public function view(AuthUser $authUser, SubjectCategory $subjectCategory): bool
    {
        return $authUser->can('View:SubjectCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SubjectCategory');
    }

    public function update(AuthUser $authUser, SubjectCategory $subjectCategory): bool
    {
        return $authUser->can('Update:SubjectCategory');
    }

    public function delete(AuthUser $authUser, SubjectCategory $subjectCategory): bool
    {
        return $authUser->can('Delete:SubjectCategory');
    }

    public function restore(AuthUser $authUser, SubjectCategory $subjectCategory): bool
    {
        return $authUser->can('Restore:SubjectCategory');
    }

    public function forceDelete(AuthUser $authUser, SubjectCategory $subjectCategory): bool
    {
        return $authUser->can('ForceDelete:SubjectCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SubjectCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SubjectCategory');
    }

    public function replicate(AuthUser $authUser, SubjectCategory $subjectCategory): bool
    {
        return $authUser->can('Replicate:SubjectCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SubjectCategory');
    }

}