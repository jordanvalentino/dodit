<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $fillable = [
    	'amount', 'budget_id'
    ];

    public function budget()
    {
    	return $this->belongsTo('App\Budget');
    }
}
