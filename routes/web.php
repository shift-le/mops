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
use App\Http\Controllers\ordhistory\OrdHistoryController;
use App\Http\Controllers\UserController;


// ベースURLにアクセスしたら/loginへリダイレクト
Route::get('/', function () {
    return redirect('/login');
});

// パスワードリセット（非認証）
Route::get('/password/reset', [PasswordResetController::class, 'request'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/sendcomplete', [PasswordResetController::class, 'sendComplete'])->name('password.sendcomplete');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
Route::get('/password/complete', [PasswordResetController::class, 'complete'])->name('password.complete');

// ログイン関連
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'loginAs']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    
    // トップページ
    Route::get('/top', [TopController::class, 'index'])->name('top');

    // ユーザ情報
    Route::get('/users/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/complete', [UserController::class, 'complete'])->name('users.complete');

    // カテゴリ・ツール
    Route::prefix('tools')->group(function () {
        Route::get('/search', [ToolController::class, 'search'])->name('tools.search');
        Route::get('/{code}', [ToolController::class, 'show'])->name('tools.show');
    });

    Route::get('/category', [CategoryController::class, 'index'])->name('categorys.index');

    // お気に入り
    Route::get('/favorite', [FavoriteController::class, 'search'])->name('favorites.search');
    Route::post('/favorite/add', [FavoriteController::class, 'addFavorite'])->name('favorites.add');
    Route::post('/favorite/remove', [FavoriteController::class, 'removeFavorite'])->name('favorites.remove');
    Route::post('/favorite/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // カート
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('carts.index');
        Route::post('/update', [CartController::class, 'updateQuantity'])->name('cart.update');
        Route::post('/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cancel', [CartController::class, 'cancelAll'])->name('cart.cancel');
        Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    });

    // 発注
    Route::prefix('checkout')->group(function () {
        Route::match(['get', 'post'], '/', [CartController::class, 'checkout'])->name('carts.checkout');
        Route::post('/confirm', [CartController::class, 'confirm'])->name('carts.confirm');
        Route::post('/complete', [CartController::class, 'complete'])->name('carts.complete');
    });

    // 注文履歴
    Route::prefix('ordhistory')->name('ordhistory.')->group(function () {
        Route::get('/', [OrdHistoryController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/result', [OrdHistoryController::class, 'result'])->name('result');
        Route::get('/{orderCode}', [OrdHistoryController::class, 'show'])->name('show');
        Route::post('/{orderCode}/repeat', [OrdHistoryController::class, 'repeat'])->name('repeat');
    });
    // FAQ(一覧・詳細)
    Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/faq/{id}', [FaqController::class, 'show'])->name('faq.show');
// 掲示板（一覧・詳細）
    Route::get('/board', [BoardController::class, 'index'])->name('board.index');
    Route::get('/board/{id}', [BoardController::class, 'show'])->name('board.show');
});

    //  /manageルートを別ファイルに分離
	require base_path('routes/manage.php'); 