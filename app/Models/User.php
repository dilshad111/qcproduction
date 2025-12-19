<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'theme',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
    ];

    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function hasPermission($permission) {
        if ($this->isAdmin()) return true;
        if (!$this->permissions) return false;
        
        // Handle specific actions like can_edit, can_delete
        return isset($this->permissions[$permission]) && $this->permissions[$permission];
    }

    public function canViewMenu($menuName) {
        if ($this->isAdmin()) return true;
        
        $menuAccess = $this->permissions['menu_access'] ?? [];
        return in_array($menuName, $menuAccess);
    }

    public function canViewSubmenu($submenuName) {
        if ($this->isAdmin()) return true;
        
        $submenuAccess = $this->permissions['submenu_access'] ?? [];
        return in_array($submenuName, $submenuAccess);
    }
}
