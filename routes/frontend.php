<?php

    Route::group(['middleware' => ['auth']], function () {

        //language

        Route::get('/languages', 'LanguageController@index');

        //language

        //IVR Menu

        Route::get('/ivrmenulist', 'AccountGroupdetailsController@index');

        Route::get('/ivrmenulist/create', 'AccountGroupdetailsController@create');

        Route::post('/ivrmenulist/edit', 'AccountGroupdetailsController@store');

        //IVR Menu


    });