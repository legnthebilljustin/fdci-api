<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactsController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function() {
    Route::post("/login", "login");
    Route::post("/register", "register");
});

Route::middleware(["auth:sanctum"])->group(function() {
    Route::apiResource("contacts", ContactsController::class)->except(["index"]);

    Route::get('/userContacts', [ContactsController::class, 'getContacts']);
});
