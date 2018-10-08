<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('login/phone', 'Auth\LoginController@phone')->name('login.phone');
Route::post('login/phone', 'Auth\LoginController@verify');

Route::get('login/{network}', 'Auth\NetworkController@redirect')->name('login.network');
Route::get('login/{network}/callback', 'Auth\NetworkController@callback');

Route::get('verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Route::get('banner/get', 'BannerController@get')->name('banner.get');
Route::get('banner/{banner}/click', 'BannerController@click')->name('banner.click');

Route::group([
    'prefix' => 'adverts',
    'as' => 'adverts.',
    'namespace' => 'Adverts',
], function () {
    Route::get('show/{advert}', 'AdvertController@show')->name('show');
    Route::post('show/{advert}/phone', 'AdvertController@phone')->name('phone');
    Route::post('show/{advert}/favorites', 'FavoriteController@add')->name('favorites');
    Route::delete('/show/{advert}/favorites', 'FavoriteController@remove');

    Route::get('{adverts_path?}', 'AdvertController@index')->name('index')->where('adverts_path', '.+');
});

/** Cabinet */
Route::group([
    'prefix' => 'cabinet',
    'as' => 'cabinet.',
    'namespace' => 'Cabinet',
    'middleware' => ['auth'],
], function () {
    Route::get('/', 'HomeController@index')->name('home');

    /** Profile */
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', 'ProfileController@index')->name('home');
        Route::get('edit', 'ProfileController@edit')->name('edit');
        Route::put('update', 'ProfileController@update')->name('update');
        Route::post('phone', 'PhoneController@request');
        Route::get('phone', 'PhoneController@form')->name('phone');
        Route::put('phone', 'PhoneController@verify')->name('phone.verify');
        Route::post('phone/auth', 'PhoneController@auth')->name('phone.auth');
    });

    /** Favorites */
    Route::group(['prefix' => 'favorites', 'as' => 'favorites.'], function () {
        Route::get('/', 'FavoriteController@index')->name('index');
        Route::delete('{advert}', 'FavoriteController@remove')->name('remove');
    });

    /** Adverts */
    Route::group([
        'prefix' => 'adverts',
        'as' => 'adverts.',
        'namespace' => 'Adverts',
        'middleware' => [App\Http\Middleware\FilledProfileOnly::class],
    ], function () {
        Route::get('/', 'AdvertController@index')->name('index');

        Route::group(['prefix' => 'create', 'as' => 'create'], function () {
            Route::get('/', 'CreateController@category');
            Route::get('region/{category}/{region?}', 'CreateController@region')->name('.region');
            Route::get('advert/{category}/{region?}', 'CreateController@advert')->name('.advert');
            Route::post('advert/{category}/{region?}', 'CreateController@store')->name('.advert.store');
        });

        Route::group(['prefix' => '{advert}'], function () {
            Route::get('edit', 'ManageController@editForm')->name('edit');
            Route::put('edit', 'ManageController@edit');
            Route::get('photos', 'ManageController@photosForm')->name('photos');
            Route::post('photos', 'ManageController@photos');
            Route::get('attributes', 'ManageController@attributesForm')->name('attributes');
            Route::post('attributes', 'ManageController@attributes');
            Route::post('send', 'ManageController@send')->name('send');
            Route::post('close', 'ManageController@close')->name('close');
            Route::delete('/destroy', 'ManageController@destroy')->name('destroy');
        });
    });

    /** Banners */
    Route::group([
        'prefix' => 'banners',
        'as' => 'banners.',
        'namespace' => 'Banners',
        'middleware' => [App\Http\Middleware\FilledProfileOnly::class],
    ], function () {
        Route::get('/', 'BannerController@index')->name('index');
        Route::group(['prefix' => 'create', 'as' => 'create'], function () {
            Route::get('/', 'CreateController@category');
            Route::get('region/{category}/{region?}', 'CreateController@region')->name('.region');
            Route::get('banner/{category}/{region?}', 'CreateController@banner')->name('.banner');
            Route::post('banner/{category}/{region?}', 'CreateController@store')->name('.banner.store');
        });

        Route::group(['prefix' => '{banner}'], function () {
            Route::get('show', 'BannerController@show')->name('show');
            Route::get('edit', 'BannerController@editForm')->name('edit');
            Route::put('edit', 'BannerController@edit');
            Route::get('file', 'BannerController@fileForm')->name('file');
            Route::put('file', 'BannerController@file');
            Route::post('send', 'BannerController@send')->name('send');
            Route::post('cancel', 'BannerController@cancel')->name('cancel');
            Route::post('order', 'BannerController@order')->name('order');
            Route::delete('destroy', 'BannerController@destroy')->name('destroy');
        });
    });
});

/** Admin part */
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth', 'can:admin-panel'],
], function () {
    Route::post('ajax/upload/image', 'UploadController@image')->name('ajax.upload.image');

    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('users', 'UsersController');
    Route::post('users/{user}/verify', 'UsersController@verify')->name('users.verify');
    Route::resource('regions', 'RegionController');

    Route::resource('pages', 'PageController');
    Route::group([
        'prefix' => 'pages/{page}',
        'as' => 'pages.'
    ], function () {
        Route::post('/first', 'PageController@first')->name('first');
        Route::post('/up', 'PageController@up')->name('up');
        Route::post('/down', 'PageController@down')->name('down');
        Route::post('/last', 'PageController@last')->name('last');
    });

    /** Adverts */
    Route::group(['prefix' => 'adverts', 'as' => 'adverts.', 'namespace' => 'Adverts'], function () {
        Route::resource('categories', 'CategoryController');
        Route::group(['prefix' => 'categories/{category}', 'as' => 'categories.'], function () {
            Route::post('first', 'CategoryController@first')->name('first');
            Route::post('up', 'CategoryController@up')->name('up');
            Route::post('down', 'CategoryController@down')->name('down');
            Route::post('last', 'CategoryController@last')->name('last');
            Route::resource('attributes', 'AttributeController')->except('index');
        });

        Route::group(['prefix' => 'adverts', 'as' => 'adverts.'], function () {
            Route::get('/', 'AdvertController@index')->name('index');
            Route::group(['prefix' => '{advert}'], function () {
                Route::get('edit', 'AdvertController@editForm')->name('edit');
                Route::put('edit', 'AdvertController@edit');
                Route::get('photos', 'AdvertController@photosForm')->name('photos');
                Route::post('photos', 'AdvertController@photos');
                Route::get('attributes', 'AdvertController@attributesForm')->name('attributes');
                Route::post('attributes', 'AdvertController@attributes');
                Route::post('moderate', 'AdvertController@moderate')->name('moderate');
                Route::get('reject', 'AdvertController@rejectForm')->name('reject');
                Route::post('reject', 'AdvertController@reject');
                Route::delete('destroy', 'AdvertController@destroy')->name('destroy');
            });
        });
    });

    /** Banners */
    Route::group([
        'prefix' => 'banners',
        'as' => 'banners.'
    ], function () {
        Route::get('/', 'BannerController@index')->name('index');
        Route::group(['prefix' => '{banner}'], function () {
            Route::get('show', 'BannerController@show')->name('show');
            Route::get('edit', 'BannerController@editForm')->name('edit');
            Route::put('edit', 'BannerController@edit');
            Route::post('moderate', 'BannerController@moderate')->name('moderate');
            Route::get('reject', 'BannerController@rejectForm')->name('reject');
            Route::post('reject', 'BannerController@reject');
            Route::post('pay', 'BannerController@pay')->name('pay');
            Route::delete('destroy', 'BannerController@destroy')->name('destroy');
        });
    });
});

Route::get('/{page_path}', 'PageController@show')->name('page')->where('page_path', '.+');