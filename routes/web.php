<?php

/** Auth */
Auth::routes();
Route::get('verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');
Route::get('login/verify', 'Auth\LoginController@phone')->name('login.phone');
Route::post('login/verify', 'Auth\LoginController@verify');

/** Home */
Route::get('/', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'adverts',
    'as' => 'adverts.',
    'namespace' => 'Adverts'
], function () {
    Route::get('show/{advert}', 'AdvertController@show')->name('show');
    Route::post('show/{advert}/phone', 'AdvertController@phone')->name('phone');

    Route::get('{advert_path?}', 'AdvertController@index')->name('index')->where('advert_path', '.+');
});

/** Cabinet */
Route::group([
    'prefix' => 'cabinet',
    'as' => 'cabinet.',
    'namespace' => 'Cabinet',
    'middleware' => ['auth'],
], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::group([
        'prefix' => 'profile',
        'as' => 'profile.'
    ], function () {
        Route::get('/', 'ProfileController@index')->name('home');
        Route::get('edit', 'ProfileController@edit')->name('edit');
        Route::put('update', 'ProfileController@update')->name('update');
        Route::post('phone', 'PhoneController@request');
        Route::get('phone', 'PhoneController@form')->name('phone');
        Route::put('phone', 'PhoneController@verify')->name('phone.verify');
        Route::post('phone/auth', 'PhoneController@auth')->name('phone.auth');
    });

    Route::group([
        'prefix' => 'adverts',
        'as' => 'adverts.',
        'namespace' => 'Adverts',
        'middleware' => App\Http\Middleware\FilledProfileOnly::class,
    ], function () {
        Route::get('/', 'AdvertController@index')->name('index');
        Route::get('/create', 'CreateController@category')->name('create');
        Route::get('/create/region/{category}/{region?}', 'CreateController@region')->name('create.region');
        Route::get('/create/advert/{category}/{region?}', 'CreateController@advert')->name('create.advert');
        Route::post('/create/region/{category}/{region?}', 'CreateController@store')->name('create.advert.store');

        Route::get('{advert}/edit', 'ManageController@edit')->name('edit');
        Route::put('{advert}/edit', 'ManageController@update')->name('update');
        Route::get('{advert}/photos', 'ManageController@photos')->name('photos');
        Route::post('{advert}/photos', 'ManageController@photos');
        Route::post('{advert}/send', 'ManageController@send')->name('send');
        Route::delete('{advert}/destroy', 'ManageController@destroy')->name('destroy');
    });;
});


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
