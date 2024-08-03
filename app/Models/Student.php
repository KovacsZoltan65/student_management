<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'class_id',
        'section_id'
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }


    /**
     * Scope a query to search students by name or email, class, and section.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        // If search parameter is present, filter results by name or email containing the search term
        return $query->when($request->search, function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like',  "%{$request->search}%");
            });
        })
        // If class_id parameter is present, filter results by that class_id
        ->when($request->class_id, function ($query) use ($request) {
            $query->where('class_id', $request->class_id);
        })
        // If section_id parameter is present, filter results by that section_id
        ->when($request->section_id, function ($query) use ($request) {
            $query->where('section_id', $request->section_id);
        });
    }
}
