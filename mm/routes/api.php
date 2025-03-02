<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TransactionTagController;
use App\Http\Controllers\BudgetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});


Route::group([

    
    'prefix' => 'auth'

], function ($router) {

    Route::post('signup', [UserController::class, 'register']);

});




Route::group([

    'middleware' => 'api',
    'prefix' => 'blog'

], function ($router) {

    Route::post('create', [BlogController::class, 'create']);
    Route::post('update', [BlogController::class, 'edit']);
    Route::post('delete', [BlogController::class, 'delete']);
    Route::get('all', [BlogController::class, 'getAll']);
    Route::get('single/{id}', [BlogController::class, 'getSingleBlog']);
});





Route::group([

    'middleware' => 'api',
    'prefix' => 'comment'

], function ($router) {

    Route::post('create', [CommentController::class, 'create']);
    Route::post('update', [CommentController::class, 'edit']);
    Route::post('delete', [CommentController::class, 'delete']);
});






Route::group([

    'middleware' => 'api',
    'prefix' => 'tags'

], function ($router) {

    Route::get('all', [TransactionTagController::class, 'getAll']);
});





Route::group([

    'middleware' => 'api',
    'prefix' => 'budgets'

], function ($router) {

    Route::post('create', [BudgetController::class, 'create']);
    Route::post('update', [BudgetController::class, 'edit']);
    Route::post('delete', [BudgetController::class, 'delete']);

    Route::get('all', [BudgetController::class, 'getAllOfAUser']);
    Route::get('single/{id}', [BudgetController::class, 'getSingleBudget']);
});