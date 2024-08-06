<?php

use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\ClassesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('sections', SectionController::class)->name('sections.index');

Route::get('classes', ClassesController::class)->name('classes.index');
