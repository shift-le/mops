<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AdminCheck;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\Auth\ManageLoginController;
use App\Http\Controllers\ManagementUserController;
use App\Http\Controllers\ManagementFaqController;
use App\Http\Controllers\ManagementBoardController;
use App\Http\Controllers\ManagementOrderController;
use App\Http\Controllers\ManagementToolController;

// /manageのベースURLにアクセスしたら/loginへリダイレクト
Route::get('/manage', function () {
    return redirect('/manage/login');
});
//管理ログイン　命名変更
Route::get('/manage/login', [ManageLoginController::class, 'show'])->name('managelogin.show');
Route::post('/manage/login', [ManageLoginController::class, 'login'])->name('managelogin.login');
Route::post('/manage/logout', [ManageLoginController::class, 'logout'])->name('managelogin.logout');

// 管理機能の認証＆権限チェック付きルートグループ
Route::prefix('manage')
    ->middleware(['auth', 'adminonly'])
    ->group(function () {
        Route::get('/top', [ManageController::class, 'top'])->name('manage.top');
        // その他の管理

    // ユーザー情報管理（一覧・詳細・新規・削除・更新・インポート・エクスポート）
    Route::prefix('managementuser')->name('managementuser.')->group(function () {
        Route::get('/', [ManagementUserController::class, 'index'])->name('index');
        Route::get('/create', [ManagementUserController::class, 'create'])->name('create');
        Route::post('/store', [ManagementUserController::class, 'store'])->name('store');
        Route::get('/import', [ManagementUserController::class, 'import'])->name('import');
        Route::post('/importexec', [ManagementUserController::class, 'importExec'])->name('importexec');
        Route::get('/export', [ManagementUserController::class, 'exportConfirm'])->name('export');
        Route::post('/export-exec', [ManagementUserController::class, 'exportExec'])->name('export.exec');
        Route::get('/detail/{id}', [ManagementUserController::class, 'detail'])->name('detail');
        Route::post('/{id}', [ManagementUserController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [ManagementUserController::class, 'update'])->name('update');
    });

    // FAQ管理（一覧・詳細・新規登録・削除・更新）
    Route::prefix('managementfaq')->name('managementfaq.')->group(function () {
        Route::get('/', [ManagementFaqController::class, 'index'])->name('index');
        Route::get('/create', [ManagementFaqController::class, 'create'])->name('create');
        Route::post('/store', [ManagementFaqController::class, 'store'])->name('store');
        Route::post('/confirm', [ManagementFaqController::class, 'confirm'])->name('confirm');
        Route::get('/show/{id}', [ManagementFaqController::class, 'show'])->name('show');
        Route::post('/{id}', [ManagementFaqController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [ManagementFaqController::class, 'update'])->name('update');
    });

    // 掲示板管理（一覧・詳細・新規登録・削除・更新）
    Route::prefix('managementboard')->name('managementboard.')->group(function () {
        Route::get('/', [ManagementBoardController::class, 'index'])->name('index');
        Route::get('/create', [ManagementBoardController::class, 'create'])->name('create');
        Route::post('/store', [ManagementBoardController::class, 'store'])->name('store');
        Route::post('/confirm', [ManagementBoardController::class, 'confirm'])->name('confirm');
        Route::get('/show/{id}', [ManagementBoardController::class, 'show'])->name('show');
        Route::post('/{id}', [ManagementBoardController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [ManagementBoardController::class, 'update'])->name('update');
    });

    // 注文検索（一覧・詳細・更新）
    Route::prefix('managementorder')->name('managementorder.')->group(function () {
        Route::get('/', [ManagementOrderController::class, 'index'])->name('index');
        Route::get('/show/{id}', [ManagementOrderController::class, 'show'])->name('show');
        Route::post('/update/{id}', [ManagementOrderController::class, 'update'])->name('update');
        // Route::delete('/{id}', [ManagementOrderController::class, 'delete'])->name('delete');
    });

    // ツール情報管理（一覧・詳細・削除・更新・インポート）
    Route::prefix('managementtool')->name('managementtool.')->group(function () {
        Route::get('/', [ManagementToolController::class, 'index'])->name('index');
        Route::get('/create', [ManagementToolController::class, 'create'])->name('create');
        Route::post('/NoticeStatus', [ManagementToolController::class, 'NoticeStatus'])->name('NoticeStatus');
        Route::post('/store', [ManagementToolController::class, 'store'])->name('store');
        Route::post('/confirm', [ManagementToolController::class, 'confirm'])->name('confirm');
        Route::get('/import', [ManagementToolController::class, 'import'])->name('import');
        Route::post('/importexec', [ManagementToolController::class, 'importExec'])->name('importexec');
        Route::get('/show/{id}', [ManagementToolController::class, 'show'])->name('show');
        Route::post('/{id}', [ManagementToolController::class, 'delete'])->name('delete');
        Route::put('/update/{id}', [ManagementToolController::class, 'update'])->name('update');
        Route::get('/invoice/{id}', [ManagementOrderController::class, 'invoice'])->name('invoice');
    });

});
