<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ListClassesData;
use App\Models\Classes;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassesController extends JsonResource
{
    public function __invoke(ListClassesData $request)
    {
        $class_id = $request->class_id;
        
        return ClassesResource::collection(Classes::where('class_id', $class_id))->get();
    }
}