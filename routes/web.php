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

/* ----------Cdr Report----------- */
Route::get('cdrreport', 'ReportController@index')->name('cdrreport');
Route::post('add_cdr_contact', 'ReportController@addContact')->name('addContact');
Route::post('add_tag', 'ReportController@addTag')->name('addTag');
Route::post('add_note', 'ReportController@addNote')->name('addNote');
Route::post('add_reminder', 'ReportController@addReminder')->name('addReminder');
Route::get('get_reminder/{id}', 'ReportController@getReminder')->name('getReminder');
Route::get('delete_reminder/{id}', 'ReportController@deleteReminder')->name('deleteReminder');
Route::delete('delete_comment/{id}', 'ReportController@deleteComment')->name('deleteComment');
Route::get('download_file/{file}/{id}', 'ReportController@downloadFile')->name('downloadFile');
Route::get('cdrreportarchive', 'ReportController@cdrreportarchive')->name('cdrreportarchive');
Route::get('cdrreportout', 'ReportController@cdrreportout')->name('cdrreportout');
Route::get('reminder', 'ReminderController@index')->name('Reminder');

/* pbx extension */
Route::get('pbx_extension', 'ReminderController@pbxextension')->name('PbxExtension');
Route::get('get_pbx_extension/{id}', 'ReminderController@getExtension')->name('getExtension');
Route::get('delete_pbxexten/{id}', 'ReminderController@delete_pbxexten')->name('deletePbxexten');
Route::post('add_extension', 'ReminderController@addExtension')->name('addExtension');

/* pbx ringgroups */
Route::get('pbx_ringgroups', 'ReminderController@pbxringgroups')->name('PbxRingGroups');
Route::get('get_pbx_ringgroup/{id}', 'ReminderController@getRinggroup')->name('getRinggroup');
Route::post('add_ringgroup', 'ReminderController@addRinggroup')->name('addRinggroup');
Route::get('delete_ringgroup/{id}', 'ReminderController@deleteRinggroup')->name('deleteRinggroup');

/* pbx did */
Route::get('pbx_did', 'ReminderController@pbxDid')->name('PbxDid');


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
Route::get('delete_account/{id}', 'UserController@deleteAccount')->name('deleteAccount');
Route::get('edit/{id}', 'UserController@edit')->name('editUser');
Route::patch('update/user/{id}', 'UserController@update')->name('updateUser');

/* ----------login account----------- */
Route::get('account', 'UserController@loginAccounts')->name('loginAccounts');
Route::post('add_account', 'UserController@addAccount')->name('addAccount');
Route::get('edit_account/{id}', 'UserController@editAccount')->name('editAccount');
Route::get('get_customer/{usertype}/{resellerid}', 'UserController@getCustomer')->name('getCustomer');
Route::get('get_did/{groupid}', 'UserController@getDid')->name('getDid');

/* ----------Coperate Group----------- */
Route::get('coperates', 'UserController@coperates')->name('CoperateGroup');
Route::post('add_coperate', 'UserController@addCoperate')->name('AddCoperate');
Route::get('edit_coperate/{id}', 'UserController@editCoperate')->name('editCoperate');
Route::get('destroy_coperate/{id}', 'UserController@destroyCoperate')->name('destroyCoperate');

Route::get('blacklist', 'UserController@blacklist')->name('BlackList');
Route::get('add_black_list', 'UserController@addBlacklist')->name('addBlackList');
Route::get('blacklist/{id}', 'UserController@destroyBlacklist')->name('deleteBlacklist');
Route::post('blacklist_store', 'UserController@storeBlacklist')->name('blacklistStore');

Route::get('operators', 'UserController@operators')->name('OperatorList');
Route::get('add_operator', 'UserController@addOperator')->name('addOperator');
Route::post('operator_store', 'UserController@storeOperator')->name('operatorStore');
Route::get('edit/operator/{id}', 'UserController@editOperator')->name('editOperator');
Route::patch('update/operator/{id}', 'UserController@updateOperator')->name('updateOperator');
Route::get('operator/{id}', 'UserController@destroyOperator')->name('deleteOperator');

/* operatorgrp */
Route::get('operatorgrp', 'UserController@operatorgrp')->name('OperatorGroup');
Route::get('operatorgrp_details/{id}', 'UserController@operatorgrp_details')->name('OperatorGroupDetails');

/* holiday */
Route::get('holiday', 'ManagementController@holiday')->name('holiday');
Route::post('holiday_store', 'ManagementController@holidayStore')->name('holidayStore');
Route::get('delete_holiday/{id}', 'ManagementController@delete_holiday')->name('deleteHoliday');

/* ivr menu */
Route::get('ivr_menu', 'ManagementController@ivrMenu')->name('ivrMenu');
Route::get('delete_ivr/{id}', 'ManagementController@deleteIvr')->name('deleteIvr');
Route::post('add_ivr_menu', 'ManagementController@addIvrmenu')->name('addIvrmenu');
Route::get('get_ivr_menu/{id}', 'ManagementController@getIvrMenu')->name('getIvrMenu');


/* voicemail */
Route::get('voicemail', 'ManagementController@voicemail')->name('Voicemail');

/* voicefiles */
Route::get('voice_files', 'ManagementController@voiceFiles')->name('voiceFiles');
Route::post('add_voicefile', 'ManagementController@addVoicefile')->name('addVoicefile');
Route::get('get_voicefile/{id}', 'ManagementController@getVoicefile')->name('getVoicefile');

/* generalfiles */
Route::get('general_files', 'ManagementController@generalFiles')->name('generalFiles');
Route::get('delete_file/{id}', 'ManagementController@deleteFile')->name('deleteFile');
Route::post('add_general_file', 'ManagementController@addGeneralFile')->name('addGeneralFile');

Route::get('moh_listings', 'ManagementController@mohListings')->name('mohListings');
Route::get('delete_moh/{id}/{classname}', 'ManagementController@deleteMoh')->name('deleteMoh');
Route::post('add_moh', 'ManagementController@addMoh')->name('addMoh');
Route::get('get_moh/{id}', 'ManagementController@getMoh')->name('getMoh');

/* contacts */
Route::get('contacts', 'ManagementController@contacts')->name('Contacts');
Route::get('edit_contact', 'ManagementController@editContact')->name('editContact');
Route::get('delete_contact/{id}', 'ManagementController@delete_contact')->name('deleteContact');

/* ---------- Did ----------- */
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
Route::post('add_cdr', 'ReportController@addCdr')->name('AddCdr');

/* ---------- Operator Department ----------- */
Route::get('optdept_list', 'OperatorController@index')->name('OptdeptList');
Route::get('get_ivr/{groupid}', 'OperatorController@getIvr')->name('getIvr');
Route::post('add_operator', 'OperatorController@addOperator')->name('addOperator');
Route::get('get_operator/{id}', 'OperatorController@getOperator')->name('getOperator');
Route::get('delete_operator/{id}', 'OperatorController@deleteOperator')->name('deleteOperator');
Route::get('nonoperator_list', 'OperatorController@nonOperatorList')->name('NonOperatorList');
Route::get('sms_list', 'OperatorController@sms')->name('Sms');
Route::post('add_sms', 'OperatorController@addSms')->name('addSms');
Route::get('get_sms/{id}', 'OperatorController@getSms')->name('getSms');
Route::get('delete_sms/{id}', 'OperatorController@deleteSms')->name('deleteSms');
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
Route::post('getSubCategory', 'CdrAjaxController@getSubCategory');


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
Route::get('/crm/category-edit/{id}', 'CrmController@categoryedit')->name('category-edit');
Route::patch('/crm/category-update/{id}', 'CrmController@categoryupdate')->name('category-update');
Route::get('/crm/status-add', 'CrmController@statusadd');
Route::post('/crm/status-add', 'CrmController@statusadd');
Route::get('/crm/category-delete/{categoryId}', 'CrmController@categorydelete')->name('category-delete');
Route::get('/crm/sub-category-delete/{subCategoryId}', 'CrmController@subcategorydelete')->name('sub-category-delete');
Route::get('/crm/status-delete/{statusId}', 'CrmController@statusdelete')->name('status-delete');

Route::get('/crm/sub-category-add', 'CrmController@subcategoryadd');
Route::post('/crm/sub-category-add', 'CrmController@subcategoryadd');
Route::get('/crm/sub-category-edit/{id}', 'CrmController@subcategoryedit')->name('sub-category-edit');
Route::patch('/crm/sub-category-update/{id}', 'CrmController@subcategoryupdate')->name('sub-category-update');


//leads
Route::get('leads', 'UserController@leadList')->name('LeadList');
Route::get('addlead', 'UserController@addLead')->name('addLead');
Route::post('leadstore', 'UserController@storeLead')->name('leadstore');


Route::get('leaddelete/{id}', 'UserController@destroyLead')->name('deleteLead');
Route::get('editlead/{id}', 'UserController@editLead')->name('editLead');
Route::patch('update/lead/{id}', 'UserController@updateLead')->name('updateLead');
/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| The routes associated with frontend goes here. added by Kurian
|
*/
require_once "frontend.php";
