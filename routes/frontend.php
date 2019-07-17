<?php

    Route::group(['middleware' => ['auth']], function () {

        //language

        Route::get('/languages', 'LanguageController@index');

        //language

    });