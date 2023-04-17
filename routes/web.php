<?php

use App\Http\Controllers\BlacklistedTaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name("home");

Route::middleware('auth')->group(function () {
    Route::get('profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/detail/{user_id?}', [ProfileController::class, 'detail'])->name('profile.detail');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('task/fulfill/{task_id}', [TaskController::class, 'fulfill'])->name('task.fulfill');
    Route::post('task/complete', [TaskController::class, 'complete'])->name('task.complete');
    Route::get('task/created/{task_id}', [TaskController::class, 'created'])->name('task.created');
    Route::patch('task', [TaskController::class, 'updateStatus'])->name('task.updateStatus');

    Route::resources([
        'user' => UserController::class,
        'task' => TaskController::class,
        'blacklisted-tasks' => BlacklistedTaskController::class,
    ]);
});

require __DIR__.'/auth.php';
