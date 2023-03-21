<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\SoftDeleteUsers;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeleteUsers;

    public const ROLE_ADMIN = 'Admin';
    public const ROLE_REVIEWER = 'Reviewer';
    public const ROLE_EDITOR = 'Editor';

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    public const NOT_DELETED = 0;
    public const DELETED= 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'verify_email',
        'is_active',
        'is_delete',
        'group_role',
        'last_login_at',
        'last_login_ip',
        'created_at',
        'updated_at',
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
     * @var array
     */
    protected $casts = [
        'last_login_at' => 'datetime:Y-m-d H:i:s',
        'created_at' =>  'datetime:Y-m-d H:i:s',
        'updated_at' =>  'datetime:Y-m-d H:i:s',
    ];

    public function getStatusAttribute()
    {
        if ($this->is_active === 0) {
            return 'Tạm khóa';
        }

        return 'Đang hoạt động';
    }

    public function setPasswordAttribute($value, bool $hash = true)
    {
        if ($hash === true) {
            $value = Hash::make($value);
        }

        $this->attributes['password'] = $value;
    }
}
