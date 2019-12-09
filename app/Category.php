<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public static function is_exist()
    {
        return  (self::credits()->count() > 0) && (self::debits()->count() > 0);
    }

    public static function fromRequest(Request $request, $type)
    {
        return new self([
            'name' => $request->name,
            'type' => $type,
            'super_id' => ($request->super_id != 0) ? $request->super_id : NULL,
            'user_id' => Auth::id(),
        ]);
    }

    public static function credits($parent_only = false)
    {
        $categories = Auth::user()->categories()->where('type', 'cr');

        if ($parent_only)
        {
            return $categories->where('super_id', NULL)->get();
        }
        else
        {
            return $categories->get();
        }
    }

    public static function debits($parent_only = false)
    {
        $categories = Auth::user()->categories()->where('type', 'db');

        if ($parent_only)
        {
            return $categories->where('super_id', NULL)->get();
        }
        else
        {
            return $categories->get();
        }
    }
}
