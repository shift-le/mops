<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\tools\CategoryController;
use App\Http\Controllers\tools\ToolController;
use App\Http\Controllers\favorites\FavoriteController;
use App\Http\Controllers\carts\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\TopController;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\BoardController;

// ログイン
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'loginAs']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// パスワードリセット
Route::get('/password/reset', [PasswordResetController::class, 'request'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
Route::get('/password/complete', [PasswordResetController::class, 'complete'])->name('password.complete');

// トップページ
Route::get('/top', [TopController::class, 'index'])->name('top');
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
// 全てキャンセル
Route::post('/cart/cancel', [CartController::class, 'cancelAll'])->name('cart.cancel');
// カートに追加
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

// 発注
Route::match(['get', 'post'], '/checkout', [CartController::class, 'checkout'])->name('carts.checkout')->middleware('auth');
Route::post('/checkout/confirm', [CartController::class, 'confirm'])->name('carts.confirm')->middleware('auth');
Route::post('/checkout/complete', [CartController::class, 'complete'])->name('carts.complete')->middleware('auth');

// お気に入り登録・解除
Route::post('/favorite/add', [FavoriteController::class, 'addFavorite'])->name('favorites.add');
Route::post('/favorite/remove', [FavoriteController::class, 'removeFavorite'])->name('favorites.remove');
Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');




// FAQ(一覧・詳細)
    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/faq/{id}', [FaqController::class, 'show']);
// 掲示板（一覧・詳細）
    Route::get('/board', [BoardController::class, 'index'])->name('board.index');
    Route::get('/board/{id}', [BoardController::class, 'show']);
    
    //  /manageルートを別ファイルに分離
	require base_path('routes/manage.php'); 