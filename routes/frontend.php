<?php

    Route::group(['middleware' => ['auth']], function () {

        //language

        Route::get('/languages', 'LanguageController@index');

        //language

        //IVR Menu

        Route::get('/ivrmenulist', 'AccountGroupController@index');

        //IVR Menu


    });