<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    public $timestamps = true;
    
    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ganti password user
     *
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     */
    public function changePassword(string $currentPassword, string $newPassword): bool
    {
        // Pastikan password lama cocok
        if (!Hash::check($currentPassword, $this->password)) {
            return false;
        }

        // Update password baru
        $this->password = Hash::make($newPassword);
        return $this->save();
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
