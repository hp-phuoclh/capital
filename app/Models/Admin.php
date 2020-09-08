<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminInviteMember as AdminInviteMemberNotification;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class Admin extends Authenticatable
{
    use HasRoles, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($admin) {
            $admin->{$admin->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['registration_ids'];

    /**
     * save Registration Ids
     *
     * @param string $registration_id
     * @return void|bool
     */
    public function saveRegistrationIds($registration_id)
    {
        if (!$registration_id) {
            return false;
        }
        try {
            $registrationId = new RegistrationId;
            $registrationId->id = $registration_id;
            $registrationId->user_id = $this->id;
            $registrationId->type = 'admin';
            $registrationId->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    /**
     * Get the registrationIds for the order.
     */
    public function registrationIds()
    {
        return $this->hasMany(RegistrationId::class, 'user_id', 'id')->where('type', 'admin');
    }

    public function getRegistrationIdsAttribute()
    {
        return $this->registrationIds()->pluck('id');
    }

    /**
     * sendAdminInviteMemberNotification function
     *
     * @param [string] $token
     * @return void
     */
    public function sendAdminInviteMemberNotification($data)
    {
        $this->notify(new AdminInviteMemberNotification($data));
    }
}
