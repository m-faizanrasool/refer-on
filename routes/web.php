<?php

use App\Http\Controllers\BlacklistedTaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserStatus;
use App\Http\Middleware\IsAdminMiddleware;
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

Route::get('about-us', [StaticPageController::class, 'aboutus'])->name('aboutus');
Route::get('how-it-works', [StaticPageController::class, 'howitworks'])->name('howitworks');
Route::get('privacy-policy', [StaticPageController::class, 'privacypolicy'])->name('privacypolicy');
Route::get('term-of-use', [StaticPageController::class, 'termofuse'])->name('termofuse');

Route::middleware(['auth', CheckUserStatus::class])->group(function () {
    Route::get('profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/detail/{user_id?}', [ProfileController::class, 'detail'])->name('profile.detail');
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //make route for aboutus page, how it works, privacy policy and termof use

    Route::get('task/fulfill/{taskId}', [TaskController::class, 'fulfill'])->name('task.fulfill');
    Route::post('task/invalid', [TaskController::class, 'invalid'])->name('task.invalid');
    Route::post('task/complete', [TaskController::class, 'complete'])->name('task.complete');
    Route::get('task/created/{taskId}', [TaskController::class, 'created'])->name('task.created');
    Route::patch('task', [TaskController::class, 'updateStatus'])->name('task.updateStatus');

    Route::resources([
        'task' => TaskController::class,
    ]);

    Route::middleware([IsAdminMiddleware::class])->group(function () {
        Route::get('user', [UserController::class, 'index'])->name('user.index');
        Route::get('user/block/{user_id}', [UserController::class, 'block'])->name('user.block');
        Route::get('user/unblock/{user_id}', [UserController::class, 'unblock'])->name('user.unblock');

        Route::resources([
            'blacklisted-tasks' => BlacklistedTaskController::class,
        ]);
    });
});

require __DIR__.'/auth.php';
