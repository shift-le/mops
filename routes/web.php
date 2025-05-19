<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\tools\CategoryController;
use App\Http\Controllers\tools\ToolController;
use App\Http\Controllers\favorites\FavoriteController;
use App\Http\Controllers\carts\CartController;
use App\Http\Controllers\Auth\MockLoginController;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
// お気に入り一覧
Route::get('/favorite', [FavoriteController::class, 'search'])->name('favorites.search');
// カートの中の確認
Route::get('/cart', [CartController::class, 'index'])->name('carts.index');
Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
// 依頼主届け先入力
Route::get('/checkout', function () {
            return view('carts.checkout');
            })->name('checkout');


// カートに追加
Route::post('/cart/add', [ToolController::class, 'addToCart'])->name('cart.add');
// お気に入り登録・解除
Route::post('/favorite/add', [FavoriteController::class, 'addFavorite'])->name('favorites.add');
Route::post('/favorite/remove', [FavoriteController::class, 'removeFavorite'])->name('favorites.remove');
Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');


// 仮アカウントでログイン
Route::get('/mock-login/{userId}', [MockLoginController::class, 'loginAs']);
Route::post('/favorite/add', [ToolController::class, 'addFavorite']);
Route::post('/favorite/remove', [ToolController::class, 'removeFavorite']);
// FAQ(一覧・詳細)
    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/faq/{id}', [FaqController::class, 'show']);
// 掲示板（一覧・詳細）
    Route::get('/board', [BoardController::class, 'index'])->name('board.index');
    Route::get('/board/{id}', [BoardController::class, 'show']);
