<?php

Route::group([
	'prefix' => 'admin',
	'as' => 'admin.',
	'namespace' => 'Admin',
	'middleware' => ['auth', 'admin']
], function() {
    Route::get('/', 'DasboardController@home')->name('home');

    // nhà mạng
    Route::resource('sim', 'SimController', [
        'names' => 'sim',
        'except' => ['create', 'show']
    ]);

    // nạp tiền
    Route::resource('recharge', 'RechargeBillController', [
        'names' => 'recharge',
        'only' => ['index', 'update', 'destroy']
    ]);

    // rút tiền
    Route::resource('withdraw', 'WithdrawBillController', [
    	'names' => 'withdraw',
    	'only' => ['index', 'update', 'destroy']
    ]);

    // chuyển tiền
    Route::resource('transfer', 'TransferBillController', [
        'names' => 'transfer',
        'only' => ['index']
    ]);

    // mua
    Route::resource('buy', 'BuyBillController', [
        'names' => 'buy',
        'only' => ['index', 'update']
    ]);

    Route::resource('game', 'GameController', [
    	'names' => 'game',
    	'only' => ['index', 'store', 'edit', 'update', 'destroy']
    ]);

    Route::group(['as' => 'game.', 'prefix' => 'game/{game}'], function() {
        Route::resource('account', 'AccountController', [
            'names' => 'account',
            'only' => ['index', 'update', 'destroy']
        ]);
    });

    Route::group([
        'as' => 'datatables.',
        'prefix' => 'datatables',
        'middleware' => 'ajax'
    ], function() {
        Route::get('game', 'DataTablesController@listGame')->name('game');
        Route::get('game/{game_id}', 'DataTablesController@listAccount')->name('game.account');
        Route::get('sim', 'DataTablesController@listSim')->name('sim');
        Route::get('recharge-bills', 'DataTablesController@listRechargeBill')->name('recharge_bills');
        Route::get('withdraw-bills', 'DataTablesController@listWithdrawBill')->name('withdraw_bills');
    });
});

Route::group(['middleware' => 'auth'], function() {
    // nạp tiền
    Route::post('recharge/order', 'RechargeBillController@order')->name('recharge.order');
    Route::get('recharge/order-cancel', 'RechargeBillController@orderCancel')->name('recharge.order.cancel');
    Route::get('recharge/check-order', 'RechargeBillController@checkOrder')->name('recharge.order.check');
    Route::resource('recharge', 'RechargeBillController', [
        'names' => 'recharge',
        'only' => ['create', 'store']
    ]);

    // rút tiền
    Route::resource('withdraw', 'WithdrawBillController', [
        'names' => 'withdraw',
        'only' => ['create', 'store']
    ]);

    // chuyển tiền
    Route::resource('transfer', 'TransferBillController', [
    	'names' => 'transfer',
    	'only' => ['create', 'store']
    ]);

    // mua ACC
    Route::post('buy', 'BuyBillController@store')->name('buy.store');

    // tài khoản
    Route::resource('account', 'AccountController', [
        'names' => 'account',
        'only' => ['create', 'store', 'update']
    ]);

    Route::get('history', 'TransactionHistoryController@index')->name('history.index');
    Route::get('history/check-card/{recharge_bill}', 'TransactionHistoryController@checkCard')->name('history.card.check');

    Route::get('instruction', function() {
        return view('instruction');
    })->name('instruction');

    Route::get('change-password', 'ChangePassController@showChangePassForm')->name('password.change');
    Route::post('change-password', 'ChangePassController@changePassword')->name('password.change');
});

Route::get('game/{game}/account', 'AccountController@index')->name('game.account.index');
Route::get('/account/{account}', 'AccountController@show')->name('account.show');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

// change password with sms
Route::get('password', function() {
    return view('auth.rspassword');
})->name('password.reset');
Route::get('password/smsreset', 'ChangePassController@smsResetPass');

// delete account with sms
Route::get('smsdelete/account', 'AccountController@smsdelete');

// sms to recharge money
Route::get('smsrecharge', 'RechargeBillController@smsRecharge');

Route::get('migrate/{password}', function($password) {
    if ($password === 'TgHHj25G3') {
        $exitCode = Artisan::call('migrate:rollback', [
            '--step' => 3,
        ]);
        echo $exitCode;
    }
});
