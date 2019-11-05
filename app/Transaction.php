<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    	'amount', 'detail', 'attachment', 'category_id'
    ];

    public function category()
    {
    	return $this->belongsTo('App\Category');
    }
}
