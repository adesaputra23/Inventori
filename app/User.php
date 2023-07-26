<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nik', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const Admin             = 1;
    const StafKecamatan     = 2;
    const KepalaKecamatan   = 3;

    const roleUserName = [
        1 => 'Admin',
        2 => 'Staf Kecamatan',
        3 => 'Kepala Kecamatan'
    ];

    public function role()
    {
        return $this->hasOne('App\Role', 'nik', 'nik');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Pegawai', 'nik', 'nik');
    }

    public static function isAdmin()
    {
        $user = Auth::user()->role->role;
        if ($user === self::Admin) {
            return true;
        }
        return false;
    }

    public static function isStaft()
    {
        $user = Auth::user()->role->role;
        if ($user === self::StafKecamatan) {
            return true;
        }
        return false;
    }

    public static function isKepala()
    {
        $user = Auth::user()->role->role;
        if ($user === self::KepalaKecamatan) {
            return true;
        }
        return false;
    }


}
