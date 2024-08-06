<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function scopeSearch(Builder $query, Request $request)
    {
        return $query->when($request->search, function($query) use($request)
        {
            $query->where('name', 'like', "%{$request->search}%");
        });
    }
    
    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }
}
