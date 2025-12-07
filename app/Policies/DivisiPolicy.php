<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Divisi;
use Illuminate\Auth\Access\HandlesAuthorization;

class DivisiPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Divisi');
    }

    public function view(AuthUser $authUser, Divisi $divisi): bool
    {
        return $authUser->can('View:Divisi');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Divisi');
    }

    public function update(AuthUser $authUser, Divisi $divisi): bool
    {
        return $authUser->can('Update:Divisi');
    }

    public function delete(AuthUser $authUser, Divisi $divisi): bool
    {
        return $authUser->can('Delete:Divisi');
    }

    public function restore(AuthUser $authUser, Divisi $divisi): bool
    {
        return $authUser->can('Restore:Divisi');
    }

    public function forceDelete(AuthUser $authUser, Divisi $divisi): bool
    {
        return $authUser->can('ForceDelete:Divisi');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Divisi');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Divisi');
    }

    public function replicate(AuthUser $authUser, Divisi $divisi): bool
    {
        return $authUser->can('Replicate:Divisi');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Divisi');
    }
}
