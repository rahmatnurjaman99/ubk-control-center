<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ScholarshipAssignment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipAssignmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ScholarshipAssignment');
    }

    public function view(AuthUser $authUser, ScholarshipAssignment $scholarshipAssignment): bool
    {
        return $authUser->can('View:ScholarshipAssignment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ScholarshipAssignment');
    }

    public function update(AuthUser $authUser, ScholarshipAssignment $scholarshipAssignment): bool
    {
        return $authUser->can('Update:ScholarshipAssignment');
    }

    public function delete(AuthUser $authUser, ScholarshipAssignment $scholarshipAssignment): bool
    {
        return $authUser->can('Delete:ScholarshipAssignment');
    }

    public function restore(AuthUser $authUser, ScholarshipAssignment $scholarshipAssignment): bool
    {
        return $authUser->can('Restore:ScholarshipAssignment');
    }

    public function forceDelete(AuthUser $authUser, ScholarshipAssignment $scholarshipAssignment): bool
    {
        return $authUser->can('ForceDelete:ScholarshipAssignment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ScholarshipAssignment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ScholarshipAssignment');
    }

    public function replicate(AuthUser $authUser, ScholarshipAssignment $scholarshipAssignment): bool
    {
        return $authUser->can('Replicate:ScholarshipAssignment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ScholarshipAssignment');
    }

}