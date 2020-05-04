<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/echo', [WebController::class, 'echo']);
Route::get('/chatroom', [WebController::class, 'chatroom']);
