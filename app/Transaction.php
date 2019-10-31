<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    	'amount', 'user_id', 'category_id'
    ];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }
}
