<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Detail extends Model
{
    protected $fillable = [
    	'amount', 'budget_id'
    ];

    public function budget()
    {
    	return $this->belongsTo('App\Budget');
    }

    public static function fromRequest(Request $request)
    {
    	return new self([
			'amount' => $request->amount,
            'budget_id' => $request->budget_id,
    	]);
    }
}
