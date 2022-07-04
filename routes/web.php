<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProposalController;
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
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::middleware(['auth'])->group(function () {
    Route:: resource('user', '\App\Http\Controllers\UserController')->middleware('role:admin');
    Route:: get('user/unarchive/{id}', [UserController::class, 'unarchive'])->name('user.unarchive')->middleware('role:admin');

    // Route::group(["middleware" => "role:admin,author"], function() {
        Route::get('home', [HomeController::class, 'index'])->name('home');
        Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout');

        // Route::resource('club', ClubController::class);
        // Route::get('club/detail/{id}', [ClubController::class, 'detail'])->name('club.detail');

        // Route::resource('player', PlayerController::class);
        // Route::get('player/destroy/{id}', [PlayerController::class, 'destroy'])->name('player.delete');

        // Route:: resource('tag', '\App\Http\Controllers\TagController');
        // Route:: get('tag/unarchive/{id}', [TagController::class, 'unarchive'])->name('tag.unarchive');

        // Route:: resource('post', '\App\Http\Controllers\PostController');
        // Route:: get('post/unarchive/{id}', [PostController::class, 'unarchive'])->name('post.unarchive');
        // Route:: get('post/delete_images/{id}', [PostController::class, 'delete_images'])->name('post.delete_images');

        // Route:: resource('category', '\App\Http\Controllers\CategoryController');
        // Route:: get('category/unarchive/{id}', [CategoryController::class, 'unarchive'])->name('category.unarchive');
        Route::get('proposals/review/{id}', [ProposalController::class, 'review'])->name('proposal.review');
        Route::patch('proposals/review_action/{id}', [ProposalController::class, 'review_action'])->name('proposal.review_action');

        Route:: resource('department', '\App\Http\Controllers\DepartmentController');
        Route:: resource('level', '\App\Http\Controllers\LevelController');
        Route:: resource('category', '\App\Http\Controllers\CategoryController');
        Route:: resource('position', '\App\Http\Controllers\PositionController');
        Route:: resource('proposal', '\App\Http\Controllers\ProposalController');
        Route::post('position/editLevel', ['\App\Http\Controllers\PositionController', 'editLevel'])->name('position.editLevel');
        // Route::get('proposal/review', ['\App\Http\Controllers\ProposalController', 'review'])->name('proposal.review');

        // Route::post('schedule', [ScheduleController::class, 'store'])->name('schedule.store');
        // Route::get('schedule/scorer/{id_schedule}', [ScheduleController::class, 'scorer'])->name('schedule.scorer');
        // Route::post('schedule/scorer_store', [ScheduleController::class, 'scorer_store'])->name('schedule.scorer_store');
        // Route::post('schedule/scorer_destroy', [ScheduleController::class, 'scorer_destroy'])->name('schedule.scorer_destroy');
        // Route::post('schedule/finish', [ScheduleController::class, 'finish'])->name('schedule.finish');
    // });
});

Route::group(['prefix' => 'error'], function(){
    Route::get('404', function () { return view('pages.error.404'); });
    Route::get('500', function () { return view('pages.error.500'); });
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
Route::any('/{page?}',function(){
    return View::make('pages.error.404');
})->where('page','.*');
