<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\ManagementUserController;
use App\Http\Controllers\ManagementFaqController;
use App\Http\Controllers\ManagementBoardController;
use App\Http\Controllers\ManagementOrderController;

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
    Route::post('/store', [ManagementUserController::class, 'store'])->name('store');
    Route::get('/import', [ManagementUserController::class, 'import'])->name('import');
// エクスポート確認画面
    Route::get('/export', [ManagementUserController::class, 'exportConfirm'])->name('export');
    Route::post('/export-exec', [ManagementUserController::class, 'exportExec'])->name('export.exec');

    // ここ追記 👇
    Route::get('/detail/{id}', [ManagementUserController::class, 'detail'])->name('detail');
    Route::delete('/{id}', [ManagementUserController::class, 'delete'])->name('delete');
    Route::post('/update/{id}', [ManagementUserController::class, 'update'])->name('update');
});

// FAQ(一覧・詳細・新規登録)
Route::get('manage/managementfaq', [ManagementFaqController::class, 'index'])->name('managementfaq.index');
Route::get('manage/managementfaq/create', [ManagementFaqController::class, 'create'])->name('managementfaq.create');
Route::post('manage/managementfaq/store', [ManagementFaqController::class, 'store'])->name('managementfaq.store'); // ← これ追加
Route::get('manage/managementfaq/show/{id}', [ManagementFaqController::class, 'show'])->name('managementfaq.show');
Route::delete('manage/managementfaq/{id}', [ManagementFaqController::class, 'delete'])->name('managementfaq.delete');
Route::post('manage/managementfaq/update/{id}', [ManagementFaqController::class, 'update'])->name('managementfaq.update');

// 掲示板（一覧・詳細）
Route::get('manage/managementboard', [ManagementBoardController::class, 'index'])->name('managementboard.index');
Route::get('manage/managementboard/create', [ManagementBoardController::class, 'create'])->name('managementboard.create');
Route::get('manage/managementboard/{id}', [ManagementBoardController::class, 'show'])->name('managementboard.show');
Route::delete('manage/managementboard/{id}', [ManagementBoardController::class, 'delete'])->name('managementboard.delete');
Route::post('manage/managementboard/store', [ManagementBoardController::class, 'store'])->name('managementboard.store');
Route::post('manage/managementboard/update/{id}', [ManagementBoardController::class, 'update'])->name('managementboard.update');

// 注文検索
Route::prefix('manage/managementorder')->name('managementorder.')->group(function () {
    Route::get('/', [ManagementOrderController::class, 'index'])->name('index');
    Route::get('/{id}', [ManagementOrderController::class, 'show'])->name('show');
    Route::post('/update/{id}', [ManagementOrderController::class, 'update'])->name('update');
    Route::delete('/{id}', [ManagementOrderController::class, 'delete'])->name('delete');
});
