<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TransactionTagController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpensePlanConroller;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpensePlanTransController;
use App\Models\Transaction;

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





Route::group([

    'middleware' => 'api',
    'prefix' => 'plans'

], function ($router) {

    Route::post('create', [ExpensePlanConroller::class, 'create']);
    Route::post('update', [ExpensePlanConroller::class, 'edit']);
    Route::post('delete', [ExpensePlanConroller::class, 'delete']);
    Route::get('', [ExpensePlanConroller::class, 'getExpensePlan']);
    Route::get('single/{id}', [ExpensePlanConroller::class, 'getSingleExpensePlan']);
});




Route::group([

    'middleware' => 'api',
    'prefix' => 'transactions'

], function ($router) {

    Route::post('create', [TransactionController::class, 'create']);
    // Route::post('update', [TransactionController::class, 'edit']);
    Route::post('delete', [TransactionController::class, 'delete']);
    Route::get('all', [TransactionController::class, 'getAll']);
    Route::get('single/{id}', [TransactionController::class, 'getSingleTransaction']);
    Route::post(uri: 'bydate', action: [TransactionController::class, 'getTransactionsByDay']);
    Route::post(uri: 'bymonth', action: [TransactionController::class, 'getTransactionsByMonth']);
    Route::post(uri: 'byyear', action: [TransactionController::class, 'getTransactionsByYear']);
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'transactions/plans'

], function ($router) {

    Route::post('create', [ExpensePlanTransController::class, 'create']);
    Route::post('delete', [ExpensePlanTransController::class, 'delete']);
    Route::post('update', [ExpensePlanTransController::class, 'edit']);
    Route::get('all', [ExpensePlanTransController::class, 'getAll']);
    Route::get('single/{id}', [ExpensePlanTransController::class, 'getSingleExpensePlanTrans']);
});