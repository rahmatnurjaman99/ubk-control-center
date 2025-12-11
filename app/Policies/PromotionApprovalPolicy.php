<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PromotionApproval;
use Illuminate\Auth\Access\HandlesAuthorization;

class PromotionApprovalPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PromotionApproval');
    }

    public function view(AuthUser $authUser, PromotionApproval $promotionApproval): bool
    {
        return $authUser->can('View:PromotionApproval');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PromotionApproval');
    }

    public function update(AuthUser $authUser, PromotionApproval $promotionApproval): bool
    {
        return $authUser->can('Update:PromotionApproval');
    }

    public function delete(AuthUser $authUser, PromotionApproval $promotionApproval): bool
    {
        return $authUser->can('Delete:PromotionApproval');
    }

    public function restore(AuthUser $authUser, PromotionApproval $promotionApproval): bool
    {
        return $authUser->can('Restore:PromotionApproval');
    }

    public function forceDelete(AuthUser $authUser, PromotionApproval $promotionApproval): bool
    {
        return $authUser->can('ForceDelete:PromotionApproval');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PromotionApproval');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PromotionApproval');
    }

    public function replicate(AuthUser $authUser, PromotionApproval $promotionApproval): bool
    {
        return $authUser->can('Replicate:PromotionApproval');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PromotionApproval');
    }

}