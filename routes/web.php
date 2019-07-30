	<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/test', 'ServiceController@test')->name('test');
Route::get('home', 'HomeController@index')->name('home');
Route::get('cdrreport', 'ReportController@index')->name('cdrreport');
Route::get('cdrreportarchive', 'ReportController@cdrreportarchive')->name('cdrreportarchive');
Route::get('cdrreportout', 'ReportController@cdrreportout')->name('cdrreportout');
Route::get('reminder', 'ReminderController@index')->name('Reminder');
Route::get('operator', 'ReportController@operator')->name('operator');
Route::get('contacts', 'ReportController@contacts')->name('contacts');
Route::get('voicemail', 'ReportController@voicemail')->name('voicemail');
Route::get('blacklist', 'ReportController@blacklist')->name('blacklist');
Route::get('holiday', 'ReportController@holiday')->name('holiday');
Route::get('conference', 'ReportController@conference')->name('conference');
Route::get('notification', 'NotificationController@index')->name('notification');
Route::get('cdrtags', 'ReportController@cdrtags')->name('cdrtags');
Route::get('operatorgrp', 'ReportController@operatorgrp')->name('operatorgrp');
Route::get('livecalls', 'ReportController@livecalls')->name('livecalls');
Route::get('users', 'UserController@index')->name('UserList');
Route::get('adduser', 'UserController@addUser')->name('addUser');
Route::post('user_store', 'UserController@store')->name('user_store');
Route::get('user/{id}', 'UserController@destroy')->name('deleteUser');
Route::get('edit/{id}', 'UserController@edit')->name('editUser');
Route::patch('update/user/{id}', 'UserController@update')->name('updateUser');

Route::get('did_list', 'DidController@index')->name('DidList');
Route::get('extra_did/{id}', 'DidController@extra_did')->name('extraDid');
Route::post('add_extra_did', 'DidController@add_extra_did')->name('addExtraDid');
Route::delete('delete_extra_did/{id}', 'DidController@delete_extra_did')->name('deleteExtraDid');
Route::get('add_did', 'DidController@addDid')->name('addDid');
Route::post('store', 'DidController@store')->name('store');
Route::get('did/{id}', 'DidController@destroy')->name('deleteDid');
Route::get('edit/did/{id}', 'DidController@edit')->name('editDid');
Route::patch('update/did/{id}', 'DidController@update')->name('updateDid');

Route::get('cdrexport', 'ReportController@cdrexport')->name('cdrexport');
Route::get('cdroutexport', 'ReportController@cdroutexport')->name('cdroutexport');
Route::get('voicemailexport', 'ReportController@voicemailexport')->name('voicemailexport');
Route::get('operatordept', 'ReportController@operatordept')->name('operatordept');

//ajax
//Route::get('getForm', 'CdrAjaxController@getForm');
Route::post('getForm', 'CdrAjaxController@getForm');
Route::post('addContact', 'CdrAjaxController@addContact');
Route::post('addTag', 'CdrAjaxController@addTag');
Route::post('addReminder', 'CdrAjaxController@addReminder');
Route::post('getreportsearch', 'CdrAjaxController@getreportsearch');
Route::post('getcdroutsearch', 'CdrAjaxController@getcdroutsearch');
Route::post('getvoicesearch', 'CdrAjaxController@getvoicesearch');
Route::post('getoperatorsearch', 'CdrAjaxController@getoperatorsearch');


// Route::view('/', 'starter')->name('starter');
Route::get('large-compact-sidebar/dashboard/dashboard1', function () {
    // set layout sesion(key)
    session(['layout' => 'compact']);
    return view('dashboard.dashboardv1');
})->name('compact');

Route::get('large-sidebar/dashboard/dashboard1', function () {
    // set layout sesion(key)
    session(['layout' => 'normal']);
    return view('dashboard.dashboardv1');
})->name('normal');

Route::get('horizontal-bar/dashboard/dashboard1', function () {
    // set layout sesion(key)
    session(['layout' => 'horizontal']);
    return view('dashboard.dashboardv1');
})->name('horizontal');


Route::view('dashboard/dashboard1', 'dashboard.dashboardv1')->name('dashboard_version_1');
Route::view('dashboard/dashboard2', 'dashboard.dashboardv2')->name('dashboard_version_2');
Route::view('dashboard/dashboard3', 'dashboard.dashboardv3')->name('dashboard_version_3');
Route::view('dashboard/dashboard4', 'dashboard.dashboardv4')->name('dashboard_version_4');

// uiKits
Route::view('uikits/alerts', 'uiKits.alerts')->name('alerts');
Route::view('uikits/accordion', 'uiKits.accordion')->name('accordion');
Route::view('uikits/buttons', 'uiKits.buttons')->name('buttons');
Route::view('uikits/badges', 'uiKits.badges')->name('badges');
Route::view('uikits/carousel', 'uiKits.carousel')->name('carousel');
Route::view('uikits/lists', 'uiKits.lists')->name('lists');
Route::view('uikits/pagination', 'uiKits.pagination')->name('pagination');
Route::view('uikits/popover', 'uiKits.popover')->name('popover');
Route::view('uikits/progressbar', 'uiKits.progressbar')->name('progressbar');
Route::view('uikits/tables', 'uiKits.tables')->name('tables');
Route::view('uikits/tabs', 'uiKits.tabs')->name('tabs');
Route::view('uikits/tooltip', 'uiKits.tooltip')->name('tooltip');
Route::view('uikits/modals', 'uiKits.modals')->name('modals');
Route::view('uikits/NoUislider', 'uiKits.NoUislider')->name('NoUislider');
Route::view('uikits/cards', 'uiKits.cards')->name('cards');
Route::view('uikits/cards-metrics', 'uiKits.cards-metrics')->name('cards-metrics');

// extra kits
Route::view('extrakits/imageCroper', 'extraKits.imageCroper')->name('imageCroper');
Route::view('extrakits/loader', 'extraKits.loader')->name('loader');
Route::view('extrakits/laddaButton', 'extraKits.laddaButton')->name('laddaButton');
Route::view('extrakits/toastr', 'extraKits.toastr')->name('toastr');
Route::view('extrakits/sweetAlert', 'extraKits.sweetAlert')->name('sweetAlert');
Route::view('extrakits/tour', 'extraKits.tour')->name('tour');
Route::view('extrakits/upload', 'extraKits.upload')->name('upload');


// Apps
Route::view('apps/invoice', 'apps.invoice')->name('invoice');
Route::view('apps/inbox', 'apps.inbox')->name('inbox');
Route::view('apps/chat', 'apps.chat')->name('chat');
Route::view('apps/calendar', 'apps.calendar')->name('calendar');

// forms
Route::view('forms/smartWizard', 'forms.smartWizard')->name('smartWizard');
Route::view('forms/tagInput', 'forms.tagInput')->name('tagInput');
Route::view('forms/forms-basic', 'forms.forms-basic')->name('forms-basic');
Route::view('forms/form-layouts', 'forms.form-layouts')->name('form-layouts');
Route::view('forms/form-input-group', 'forms.form-input-group')->name('form-input-group');
Route::view('forms/form-validation', 'forms.form-validation')->name('form-validation');
Route::view('forms/form-editor', 'forms.form-editor')->name('form-editor');

// datatables
Route::view('datatables/basic-tables', 'datatables.basic-tables')->name('basic-tables');

// sessions
Route::view('sessions/signIn', 'sessions.signIn')->name('signIn');
Route::view('sessions/signUp', 'sessions.signUp')->name('signUp');
Route::view('sessions/forgot', 'sessions.forgot')->name('forgot');

// others
Route::view('others/notFound', 'others.notFound')->name('notFound');
Route::view('others/user-profile', 'others.user-profile')->name('user-profile');
Route::view('others/starter', 'starter')->name('starter');

//CRM
Route::get('/crm/category-list', 'CrmController@categoryList')->name('category-list');
Route::get('/crm/sub-category-list', 'CrmController@subCategoryList')->name('sub-category-list');
Route::get('/crm/status-list', 'CrmController@statusList')->name('status-list');
Route::get('/crm/category-add', 'CrmController@categoryadd');
Route::post('/crm/category-add', 'CrmController@categoryadd');
Route::get('/crm/status-add', 'CrmController@statusadd');
Route::post('/crm/status-add', 'CrmController@statusadd');
Route::get('/crm/category-delete/{categoryId}', 'CrmController@categorydelete')->name('category-delete');
Route::get('/crm/sub-category-delete/{subCategoryId}', 'CrmController@subcategorydelete')->name('sub-category-delete');
Route::get('/crm/status-delete/{statusId}', 'CrmController@statusdelete')->name('status-delete');

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| The routes associated with frontend goes here. added by Kurian
|
*/
require_once "frontend.php";
