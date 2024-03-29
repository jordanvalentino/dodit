<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function budgets()
    {
        return $this->hasMany('App\Budget');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function transactions()
    {
        return $this->hasManyThrough('App\Transaction', 'App\Category');
    }
}
