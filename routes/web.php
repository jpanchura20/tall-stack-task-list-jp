<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\TaskManager;

// Sets up the route for the TaskManager Livewire component
Route::get('/', TaskManager::class);

