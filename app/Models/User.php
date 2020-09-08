<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname', 'phone', 'name', 'email', 'password', 'birthday', 'gender',
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
        'deleted_at' => 'datetime',
    ];

    protected $attributes = [
        'email' => '',
    ];

    protected $dates = ['birthday'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['registration_ids'];

    public function setBirthdayAttribute($date)
    {
        $this->attributes['birthday'] = empty($date) ? null : Carbon::createFromFormat('d/m/Y', $date);
    }

    /**
     * save Registration Ids
     *
     * @param string $registration_id
     * @return void|boot
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
            $registrationId->type = 'user';
            $registrationId->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return false;
        }
    }

    /**
     * get Gender String
     * 1: Female
     * 2: Male
     *
     * @return String
     */
    public function getGenderString()
    {
        $gender = [
            1 => __('Female'),
            2 => __('Male'),
        ];

        return isset($gender[$this->gender]) ? $gender[$this->gender] : '';
    }

    public function scopeFindByPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    /**
     * Get the registrationIds for the order.
     */
    public function registrationIds()
    {
        return $this->hasMany(RegistrationId::class)->where('type', 'user');
    }

    public function getRegistrationIdsAttribute()
    {
        return $this->registrationIds()->pluck('id');
    }

}
