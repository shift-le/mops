<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\ManagementUserController;
use App\Http\Controllers\ManagementFaqController;
use App\Http\Controllers\ManagementBoardController;

// /manageのベースURLにアクセスしたら/loginへリダイレクト
Route::get('/manage', function () {
    return redirect('/manage/login');
});

// ログインページ
Route::get('/manage/login', [ManageController::class, 'login']);
Route::post('/manage/login', [ManageController::class, 'doLogin']);

// 管理画面トップ
Route::get('/manage/top', [ManageController::class, 'top'])->name('manage.dashboard');

// ユーザー情報管理
Route::prefix('manage/managementuser')->name('managementuser.')->group(function () {
    Route::get('/', [ManagementUserController::class, 'index'])->name('index');
    Route::get('/create', [ManagementUserController::class, 'create'])->name('create');
    Route::get('/import', [ManagementUserController::class, 'import'])->name('import');
    Route::get('/export', [ManagementUserController::class, 'export'])->name('export');

    // ここ追記 👇
    Route::get('/{id}/detail', [ManagementUserController::class, 'detail'])->name('detail');
    Route::delete('/{id}', [ManagementUserController::class, 'delete'])->name('delete');
});

// FAQ(一覧・詳細)
    
Route::get('manage/managementfaq', [ManagementFaqController::class, 'index'])->name('managementfaq.index');
    Route::get('/managementfaq/{id}', [ManagementFaqController::class, 'show']);
// 掲示板（一覧・詳細）
    Route::get('manage/managementboard', [ManagementBoardController::class, 'index'])->name('managementboard.index');
    Route::get('/managementboard/{id}', [ManagementBoardController::class, 'show']);