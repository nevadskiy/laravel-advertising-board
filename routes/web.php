<?php

/** Auth */
Auth::routes();
Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

/** Home */
Route::get('/', 'HomeController@index')->name('home');

/** Cabinet */
Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');

/** Admin part */
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth', 'can:admin-panel'],
], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('users', 'UsersController');
    Route::resource('regions', 'RegionsController');
    Route::post('users/{user}/verify', 'UsersController@verify')->name('users.verify');

    Route::group([
        'prefix' => 'adverts',
        'as' => 'adverts.',
        'namespace' => 'Adverts',
    ], function () {
        Route::resource('categories', 'CategoryController');

        Route::group([
            'prefix' => 'categories/{category}',
            'as' => 'categories.',
        ], function () {
            Route::post('first', 'CategoryController@first')->name('first');
            Route::post('last', 'CategoryController@last')->name('last');
            Route::post('up', 'CategoryController@up')->name('up');
            Route::post('down', 'CategoryController@down')->name('down');
            Route::resource('attributes', 'AttributeController')->except('index');
        });
    });
});