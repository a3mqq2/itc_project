<?php

use App\Http\Controllers\GeneralManagement\GeneralManagementController;
use App\Http\Controllers\GeneralManagement\MedicalFileCategoryController;
use App\Http\Controllers\GeneralManagement\UserController;
use Illuminate\Support\Facades\Route;

/**
 * General Management Routes
 *
 * All routes in this file are prefixed with 'general-management'
 * and require authentication middleware
 */

Route::middleware(['auth', 'role:admin'])->prefix('general-management')->name('general-management.')->group(function () {

    // Home page
    Route::get('/home', [GeneralManagementController::class, 'home'])->name('home');

    // User Management
    Route::resource('users', UserController::class);

    // Medical File Categories
    Route::resource('medical-file-categories', MedicalFileCategoryController::class)->except(['show']);

});
