<?php

use App\Models\Karirhub\Pencaker\Pencaker;
use App\Models\Karirhub\Perusahaan\Perusahaan;
use App\Models\User;
use App\Models\Master\Kelas;
use Illuminate\Support\Facades\Session;
use App\Models\Biblio\Member;
use Illuminate\Support\Facades\Auth;

function userRoles()
{
    return [session('hakakses')];
}

function userId()
{
    return Auth::user() ? Auth::user()->id : NULL;
}

function userName()
{
    return Auth::user() ? Auth::user()->name : NULL;
}

function userType()
{
    return Auth::user() ? Auth::user()->type : NULL;
}

function userInisial()
{   
    # PERUBAHAN HELPER USER INISIAL
    if (Auth::user()) {
        return Auth::user()->username ?? Auth::user()->member_id ?? null;
    }

    $memberData = Session::get('biblio_user');
    return $memberData['member_id'] ?? null;
}

function userAvatar()
{
    $foto = Auth::user() ? Auth::user()->avatar : NULL;

    return avatar($foto);
}

function causerActivityLog()
{   
    # PERUBAHAN HELPER ACTIVITY LOG
    if (Auth::user()) {
        return Auth::user();
    }

    $memberData = Session::get('biblio_user');
    if (!empty($memberData['member_id'])) {
        return Member::find($memberData['member_id']);
    }

    return null;
}

/**
 * has role or permission
 * @var array $role_or_permission 
 * @var string $role_or_permission => delimitier |
 * @return boolean
 */
function hasRope($role_or_permission)
{
    $roleOrPermission = $role_or_permission;

    $rolesOrPermissions = is_array($roleOrPermission)
        ? $roleOrPermission
        : explode('|', $roleOrPermission);

    $user = auth()->user();
    if (!$user)
        return false;

    if ($user->canAny($rolesOrPermissions) || $user->hasAnyRole($rolesOrPermissions)) {
        return true;
    } else {
        return false;
    }
}

/**
 * unless role or permission
 * @var array $role_or_permission 
 * @var string $role_or_permission => delimitier |
 * @return boolean
 */
function unlessRope($role_or_permission)
{
    $roleOrPermission = $role_or_permission;

    $rolesOrPermissions = is_array($roleOrPermission)
        ? $roleOrPermission
        : explode('|', $roleOrPermission);

    $user = auth()->user();
    if (!$user)
        return false;

    if ($user->canAny($rolesOrPermissions) || $user->hasAnyRole($rolesOrPermissions)) {
        return false;
    } else {
        return true;
    }
}

/**
 * allow role or permission
 * @var array $role_or_permission 
 * @var string $role_or_permission => delimitier |
 * @return void
 */
function allowRope($role_or_permission)
{
    return hasRope($role_or_permission) ? true : abort(401);
}

/**
 * prevent role or permission
 * @var array $role_or_permission 
 * @var string $role_or_permission => delimitier |
 * @param [type] $role_or_permission
 * @return void
 */
function preventRope($role_or_permission)
{
    return hasRope($role_or_permission) ? abort(401) : true;
}
