<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
    	'name', 'type', 'super_id', 'user_id'
    ];

    public function transactions()
    {
        return $this->hasMany('App\Transaction');
    }

    public function parent()
    {
        return $this->belongsTo('App\Category', 'super_id');
    }

    public function children()
    {
        return $this->hasMany('App\Category', 'super_id');
    }
}
