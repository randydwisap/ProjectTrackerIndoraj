<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $password
 * @method save()
 * @method bool hasRole(string $role)
 * @method bool hasAnyRole(array|string ...$roles)
 * @method \Spatie\Permission\Models\Role[] getRoleNames()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'nik',
        'jabatan',
        'Telepon',
    ];

    public function marketingPic(): HasMany
    {
        return $this->hasMany(Marketing::class, 'nama_pic');
    }

    public function marketingManager(): HasMany
    {
        return $this->hasMany(Marketing::class, 'project_manager');
    }
}
