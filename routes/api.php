<?php

use App\Http\Controllers\V1\OptionController;
use App\Http\Controllers\V1\SidebarLinkController;
use App\Http\Controllers\V1\TelegramLinkController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\UserProfileController;
use App\Http\Controllers\V1\UserWeeklyAttachmentController;
use App\Http\Controllers\V1\UserWeeklyAttachmentDetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function() {
    Route::post('/register', [UserController::class, 'store']);
    Route::get('/start-datetime', [OptionController::class, 'showStartDatetime']);
    Route::get('/end-datetime', [OptionController::class, 'showEndDatetime']);
    
    Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
    Route::post('/reset-password', [UserController::class, 'resetPassword']);
    
    Route::get('/google-tracking-id', [OptionController::class, 'getGoogleTrackingId']);
});

Route::middleware('auth:api')->prefix('v1')->group(function() {
    Route::get('/login', [UserController::class, 'login']);
    Route::get('/logout', [UserController::class, 'logout']);
});

Route::middleware(['auth:api', 'user'])->prefix('v1')->group(function() {
    Route::apiResource('/users', UserController::class)->except(['store', 'destroy']);
    Route::apiResource('/user-profiles', UserProfileController::class);
    Route::apiResource('/user-weekly-attachments', UserWeeklyAttachmentController::class);
    Route::apiResource('/user-weekly-attachment-details', UserWeeklyAttachmentDetailController::class);
    Route::get('/extra-links', [SidebarLinkController::class, 'index']);
    Route::get('/option-get-value-by-name', [OptionController::class, 'getValueByName']);
});

Route::middleware(['auth:api', 'admin'])->prefix('v1')->group(function() {
    Route::apiResource('/options', OptionController::class);
    Route::patch('option-update-value-by-name', [OptionController::class, 'updateValueByName']);
    Route::apiResource('/telegram-links', TelegramLinkController::class);
    Route::apiResource('/sidebar-links', SidebarLinkController::class);
});