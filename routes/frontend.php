<?php

    Route::group(['middleware' => ['auth']], function () {

        //language

        Route::get('/languages', 'LanguageController@index');

        Route::get('/languages/create', 'LanguageController@create');

        Route::post('/languages/create', 'LanguageController@store');

        Route::get('/languages/edit/{id}', 'LanguageController@edit');

        Route::post('/languages/edit/{id}', 'LanguageController@update');

        //language

        //IVR Menu

        Route::get('/ivrmenulist', 'AccountGroupdetailsController@index');

        Route::get('/ivrmenulist/create', 'AccountGroupdetailsController@create');

        Route::post('/ivrmenulist/edit', 'AccountGroupdetailsController@store');

        //IVR Menu


    });