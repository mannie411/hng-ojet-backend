<?php

namespace App;

use Cache;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'username', 'email', 'gender', 'password', 'location', 'stack', 'token', 'slack_id'
    ];

    protected $guard_name = 'api';

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

    protected static function boot()
    {
        //
        parent::boot();

        static::created(
            function ($user){
                $user->profile()->create([
                    'bio' => 'Welcome to Start.Ng.',
                ]);
            }
        );
    }

    public function tracks()
    {
        return $this->belongsToMany('App\Track', 'track_users')->withTimestamps();
    }

    /**
     * The teams that belong a user belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany('App\Team')->withTimestamps();
    }

    public function profile()
    {
        //
        return $this->hasOne('App\Profile');
    }

    public function status()
    {
        return Cache::has('online' . $this->id);

    }
     
    public function activities()
    {
        return $this->hasMany('App\Activity');
    }


    public function probation()
    {
        return $this->hasOne(Probation::class);
    }
    
    public function submissions()
    {
        return $this->hasMany('App\TaskSubmission');
    }
}
