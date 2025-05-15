<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\tools\CategoryController;
use App\Http\Controllers\tools\ToolController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\BoardController;

// Route::get('/', function () {
//     return view('welcome');
// });

// カテゴリ一覧
Route::get('/category', [CategoryController::class, 'index'])->name('categorys.index');
// ツール検索結果
Route::get('/tools/search', [ToolController::class, 'search'])->name('tools.search');
// ツール詳細
Route::get('/tools/{code}', [ToolController::class, 'show'])->name('tools.show');

// カートに追加
Route::post('/cart/add', [ToolController::class, 'addToCart']);
// お気に入り登録・解除
Route::post('/favorite/add', [ToolController::class, 'addFavorite']);
Route::post('/favorite/remove', [ToolController::class, 'removeFavorite']);
// FAQ
    Route::get('/faq', [FaqController::class, 'index']);
    Route::get('/faq/{id}', [FaqController::class, 'show']);
// 掲示板
    Route::get('/board', [BoardController::class, 'index']);
    Route::get('/board/{id}', [BoardController::class, 'show']);
