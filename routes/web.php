<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('main');


// Route::get('/storage/badges', function () {
//     Artisan::call('storage:link');
// });

Route::group(['middleware' => 'role:company-admin'], function () {


    Route::resource('companyAdminController', 'App\Http\Controllers\CompanyAdminController');
    Route::get('/company-admin', [App\Http\Controllers\CompanyAdminController::class, 'index'])->name('company-admin');
    Route::get('/company-participants/{companyId}', [App\Http\Controllers\CompanyAdminController::class, 'companyParticipants'])->name('companyParticipants');
    Route::get('/company-participant-add', [App\Http\Controllers\CompanyAdminController::class, 'companyParticipantAdd'])->name('companyParticipantAdd');
    Route::get('/company-participant-edit/{id}', [App\Http\Controllers\CompanyAdminController::class, 'edit'])->name('companyParticipantEdit');
    Route::get('/company-accreditation-size/{eventId}/{companyId}', [App\Http\Controllers\CompanyAdminController::class, 'companyAccreditCategories'])->name('companyAccreditCategories');
    Route::get('companyAdminController/editCompanyAccreditSize/{id}', 'App\Http\Controllers\CompanyAdminController@editCompanyAccreditSize');
    Route::get('companyAdminController/storeCompanyAccrCatSize/{id}/{accredit_cat_id}/{size}/{company_id}/{event_id}', 'App\Http\Controllers\CompanyAdminController@storeCompanyAccrCatSize');
    Route::get('companyAdminController/destroyCompanyAccreditCat/{id}', 'App\Http\Controllers\CompanyAdminController@destroyCompanyAccreditCat');
    Route::get('companyAdminController/sendApproval/{companyId}/{eventId}', 'App\Http\Controllers\CompanyAdminController@sendApproval');
    Route::get('companyAdminController/sendRequest/{staffId}', 'App\Http\Controllers\CompanyAdminController@sendRequest');

    Route::resource('templateFormController', 'App\Http\Controllers\TemplateFormController');
    Route::get('/template-form/{template_id}/{company_id}', [App\Http\Controllers\TemplateFormController::class, 'index'])->name('templateForm');
    Route::get('/template-form-details/{participant_id}', [App\Http\Controllers\TemplateFormController::class, 'details'])->name('templateFormDetails');

    //Route::get('badge-generate/{staffId}', 'App\Http\Controllers\GenerateBadgeController@generate');
    //Route::get('badge-preview/{staffId}', 'App\Http\Controllers\GenerateBadgeController@getBadgePath');
    //Route::get('badge-print/{staffId}', 'App\Http\Controllers\GenerateBadgeController@printBadge');


    Route::post('upload-file', 'App\Http\Controllers\FileUploadController@store');

//    Route::get('pdf-generate', 'App\Http\Controllers\pdfController@generate');

    Route::get('/pdf-generate', [App\Http\Controllers\pdfController::class, 'generate'])->name('pdf-generate');

    Route::get('/subCompanies/{companyId}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanies'])->name('subCompanies');
    Route::get('/subCompany-add/{eventid}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanyAdd'])->name('subCompanyAdd');
    Route::get('/subCompany-edit/{id}/{eventid}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanyEdit'])->name('subCompanyEdit');
    Route::post('storeSubCompnay', [App\Http\Controllers\CompanyAdminController::class, 'storeSubCompnay'])->name('storeSubCompnay');
    Route::get('/subCompany-accreditation-size/{eventId}/{companyId}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanyAccreditCategories'])->name('subCompanyAccreditCategories');
    Route::get('companyAdminController/Invite/{companyId}', 'App\Http\Controllers\CompanyAdminController@Invite');

    Route::resource('dataentryController', 'App\Http\Controllers\DataEntryController');
    Route::get('/dataentrys/{companyId}', [App\Http\Controllers\DataEntryController::class, 'index'])->name('dataentrys');
    Route::get('/dataentry-add', [App\Http\Controllers\DataEntryController::class, 'focalpointAdd'])->name('dataentryAdd');
    Route::get('/dataentry-edit/{id}', [App\Http\Controllers\DataEntryController::class, 'edit'])->name('dataentryEdit');
    Route::get('dataentryController/reset_password/{id}/{password}', 'App\Http\Controllers\DataEntryController@resetPassword');

});

Route::group(['middleware' => 'role:event-admin'], function () {

    Route::resource('eventAdminController', 'App\Http\Controllers\EventAdminController');
    Route::get('/event-admin', [App\Http\Controllers\EventAdminController::class, 'index'])->name('event-admin');
    Route::get('/event-companies/{id}', [App\Http\Controllers\EventAdminController::class, 'eventCompanies'])->name('eventCompanies');
    Route::get('/event-company-participants/{companyId}/{eventId}', [App\Http\Controllers\EventAdminController::class, 'eventCompanyParticipants'])->name('eventCompanyParticipants');
    Route::get('eventAdminController/Invite/{companyId}', 'App\Http\Controllers\EventAdminController@Invite');
    Route::get('eventAdminController/Approve/{staffId}', 'App\Http\Controllers\EventAdminController@Approve');
    Route::get('eventAdminController/Reject/{staffId}', 'App\Http\Controllers\EventAdminController@Reject');
    Route::get('eventAdminController/RejectToCorrect/{staffId}/{reason}', 'App\Http\Controllers\EventAdminController@RejectToCorrect');

    Route::resource('focalpointController', 'App\Http\Controllers\FocalPointController');
    Route::get('/focalpoints', [App\Http\Controllers\FocalPointController::class, 'index'])->name('focalpoints');
    Route::get('/focalpoint-add', [App\Http\Controllers\FocalPointController::class, 'focalpointAdd'])->name('focalpointAdd');
    Route::get('/focalpoint-edit/{id}', [App\Http\Controllers\FocalPointController::class, 'edit'])->name('focalpointEdit');
    Route::get('focalpointController/reset_password/{id}/{password}', 'App\Http\Controllers\FocalPointController@resetPassword');

    Route::resource('companyController', 'App\Http\Controllers\CompanyController');

    //Route::get('/eventCompanies', [App\Http\Controllers\CompanyController::class, 'eventCompanies'])->name('eventCompanies');
    Route::get('/companies', [App\Http\Controllers\CompanyController::class, 'index'])->name('companies');
    Route::get('/company-add/{eventid}', [App\Http\Controllers\CompanyController::class, 'companyAdd'])->name('companyAdd');
    Route::get('/company-edit/{id}/{eventid}', [App\Http\Controllers\CompanyController::class, 'edit'])->name('companyEdit');
    Route::get('/company-accreditation-size-new/{id}/{eventid}', [App\Http\Controllers\CompanyController::class, 'companyAccreditCat'])->name('companyAccreditCat');
    Route::get('companyController/editCompanyAccreditSize/{id}', 'App\Http\Controllers\CompanyController@editCompanyAccreditSize');
    Route::get('companyController/storeCompanyAccrCatSize/{id}/{accredit_cat_id}/{size}/{company_id}/{event_id}', 'App\Http\Controllers\CompanyController@storeCompanyAccrCatSize');
    Route::get('companyController/destroyCompanyAccreditCat/{id}', 'App\Http\Controllers\CompanyController@destroyCompanyAccreditCat');
    Route::get('companyController/Approve/{companyId}/{eventId}', 'App\Http\Controllers\CompanyController@Approve');

    Route::get('badge-generate/{staffId}', 'App\Http\Controllers\GenerateBadgeController@generate');
    Route::get('badge-preview/{staffId}', 'App\Http\Controllers\GenerateBadgeController@getBadgePath');
    Route::get('badge-print/{staffId}', 'App\Http\Controllers\GenerateBadgeController@printBadge');
    Route::get('/event-participant-details/{participant_id}', [App\Http\Controllers\EventAdminController::class, 'details'])->name('participantDetails');

    Route::resource('fullFillmentController', 'App\Http\Controllers\FullFillmentController');
    Route::get('/selections', [App\Http\Controllers\FullFillmentController::class, 'index'])->name('Selections');
    Route::get('fullFillmentController/getCompanies/{field_id}', [App\Http\Controllers\FullFillmentController::class, 'getCompanies'])->name('getCompanies');
    Route::get('/all-participants/{event_id}/{company_id}/{accredit_id}/{checked}', [App\Http\Controllers\FullFillmentController::class, 'allParticipants'])->name('allParticipants');
    Route::get('fullFillmentController/getParticipants/{event_id}/{company_id}/{accredit_id}', [App\Http\Controllers\FullFillmentController::class, 'getParticipants'])->name('getParticipants');
    Route::post('/pdf-generate', [App\Http\Controllers\pdfController::class, 'generate'])->name('pdf-generate');
    Route::post('/fullFillment', [App\Http\Controllers\FullFillmentController::class, 'fullFillment'])->name('fullFillment');
});

Route::group(['middleware' => 'role:super-admin'], function () {
    Route::resource('EventController', 'App\Http\Controllers\EventController');
    Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events');
    Route::get('/event-add', [App\Http\Controllers\EventController::class, 'eventAdd'])->name('eventAdd');
    Route::get('/event-edit/{id}', [App\Http\Controllers\EventController::class, 'edit'])->name('eventEdit');
    Route::get('EventController/remove/{event_security_category_id}', 'App\Http\Controllers\EventController@remove');
    Route::get('EventController/storeEventSecurityCategory/{event_id}/{security_category_id}', 'App\Http\Controllers\EventController@storeEventSecurityCategory');

    Route::get('/titles', [App\Http\Controllers\TitleController::class, 'index'])->name('titles');
    Route::get('/companyCategories', [App\Http\Controllers\CompanyCategoryController::class, 'index'])->name('companyCategories');

    Route::resource('titleController', 'App\Http\Controllers\TitleController');
    Route::get('titleController/destroy/{id}', 'App\Http\Controllers\TitleController@destroy');
    Route::get('titleController/changeStatus/{id}/{status}', 'App\Http\Controllers\TitleController@changeStatus');

    Route::resource('companyCategoryController', 'App\Http\Controllers\CompanyCategoryController');
    Route::get('companyCategoryController/destroy/{id}', 'App\Http\Controllers\CompanyCategoryController@destroy');
    Route::get('companyCategoryController/changeStatus/{id}/{status}', 'App\Http\Controllers\CompanyCategoryController@changeStatus');

    Route::resource('contactController', 'App\Http\Controllers\ContactController');
    Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts');
    Route::get('/contact-add', [App\Http\Controllers\ContactController::class, 'contactAdd'])->name('contactAdd');
    Route::get('/contact-edit/{id}', [App\Http\Controllers\ContactController::class, 'edit'])->name('contactEdit');
    Route::get('contactController/removeContactTitle/{contact_title_id}', 'App\Http\Controllers\ContactController@removeContactTitle');
    Route::get('contactController/storeContactTitle/{contact_id}/{title_id}', 'App\Http\Controllers\ContactController@storeContactTitle');

    Route::get('/securityCategories', [App\Http\Controllers\SecurityCategoryController::class, 'index'])->name('securityCategories');
    Route::resource('securityCategoryController', 'App\Http\Controllers\SecurityCategoryController');
    Route::get('securityCategoryController/destroy/{id}', 'App\Http\Controllers\SecurityCategoryController@destroy');
    Route::get('securityCategoryController/changeStatus/{id}/{status}', 'App\Http\Controllers\SecurityCategoryController@changeStatus');

    Route::get('/eventTypes', [App\Http\Controllers\EventTypeController::class, 'index'])->name('eventTypes');
    Route::resource('eventTypeController', 'App\Http\Controllers\EventTypeController');
    Route::get('eventTypeController/destroy/{id}', 'App\Http\Controllers\EventTypeController@destroy');
    Route::get('eventTypeController/changeStatus/{id}/{status}', 'App\Http\Controllers\EventTypeController@changeStatus');

    Route::get('/accreditationCategories', [App\Http\Controllers\AccreditationCategoryController::class, 'index'])->name('accreditationCategories');
    Route::resource('accreditationCategoryController', 'App\Http\Controllers\AccreditationCategoryController');
    Route::get('accreditationCategoryController/destroy/{id}', 'App\Http\Controllers\AccreditationCategoryController@destroy');
    Route::get('accreditationCategoryController/changeStatus/{id}/{status}', 'App\Http\Controllers\AccreditationCategoryController@changeStatus');

    Route::resource('participantController', 'App\Http\Controllers\ParticipantController');
    Route::get('/participants', [App\Http\Controllers\ParticipantController::class, 'index'])->name('participants');
    Route::get('/participant-add', [App\Http\Controllers\ParticipantController::class, 'participantAdd'])->name('participantAdd');
    Route::get('/participant-edit/{id}', [App\Http\Controllers\ParticipantController::class, 'edit'])->name('participantEdit');

    Route::get('/templates', [App\Http\Controllers\TemplateController::class, 'index'])->name('templates');
    Route::get('/template-add', [App\Http\Controllers\TemplateController::class, 'templateAdd'])->name('templateAdd');
    Route::resource('templateController', 'App\Http\Controllers\TemplateController');
    Route::get('templateController/destroy/{id}', 'App\Http\Controllers\TemplateController@destroy');
    Route::get('templateController/changeStatus/{id}/{status}', 'App\Http\Controllers\TemplateController@changeStatus');
    Route::get('templateController/changeLock/{id}/{status}', 'App\Http\Controllers\TemplateController@changeLock');

    Route::resource('userController', 'App\Http\Controllers\UserController');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/users-add', [App\Http\Controllers\UserController::class, 'userAdd'])->name('userAdd');
    Route::get('/users-edit/{id}', [App\Http\Controllers\UserController::class, 'userEdit'])->name('userEdit');
    Route::get('userController/reset_password/{id}/{password}', 'App\Http\Controllers\UserController@resetPassword');

    Route::get('/template-fields/{template_id}', [App\Http\Controllers\TemplateFieldController::class, 'index'])->name('templateFields');
    Route::resource('templateFieldController', 'App\Http\Controllers\TemplateFieldController');
    Route::get('templateFieldController/destroy/{field_id}', 'App\Http\Controllers\TemplateFieldController@destroy');

    Route::get('/field-elements/{field_id}', [App\Http\Controllers\FieldElementController::class, 'index'])->name('fieldElements');
    Route::resource('fieldElementController', 'App\Http\Controllers\FieldElementController');
    Route::get('fieldElementController/destroy/{element_id}', 'App\Http\Controllers\FieldElementController@destroy');

    Route::get('/template-badge', [App\Http\Controllers\TemplateBadgeController::class, 'index'])->name('templateBadge');
    Route::resource('templateBadgeController', 'App\Http\Controllers\TemplateBadgeController');
    Route::get('templateBadgeController/changeLock/{id}/{status}', 'App\Http\Controllers\TemplateBadgeController@changeLock');

    Route::post('store-file', 'App\Http\Controllers\FileUploadController@store');

    Route::get('/template-badge-fields/{badge_id}', [App\Http\Controllers\TemplateBadgeFieldController::class, 'index'])->name('templateBadgeFields');
    Route::resource('templateBadgeFieldController', 'App\Http\Controllers\TemplateBadgeFieldController');
    Route::get('templateBadgeFieldController/destroy/{field_id}', 'App\Http\Controllers\TemplateBadgeFieldController@destroy');
    Route::get('badge-design-generate/{badgeId}', 'App\Http\Controllers\GenerateBadgeController@generatePreview');
    Route::get('badge-design-generate/{badgeId}', 'App\Http\Controllers\GenerateBadgeController@generatePreview');
});

Route::group(['middleware' => 'role:security-officer'], function () {

    Route::resource('securityOfficerAdminController', 'App\Http\Controllers\SecurityOfficerAdminController');
    Route::get('/security-officer-admin', [App\Http\Controllers\SecurityOfficerAdminController::class, 'index'])->name('security-officer-admin');
    Route::get('/security-officer-companies/{id}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'securityOfficerCompanies'])->name('securityOfficerCompanies');
    Route::get('/security-officer-company-participants/{id}/{companyId}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'securityOfficerCompanyParticipants'])->name('securityOfficerCompanyParticipants');
    Route::get('securityOfficerAdminController/Approve/{staffId}', 'App\Http\Controllers\SecurityOfficerAdminController@Approve');
    Route::get('securityOfficerAdminController/Reject/{staffId}', 'App\Http\Controllers\SecurityOfficerAdminController@Reject');
    Route::get('securityOfficerAdminController/RejectToCorrect/{staffId}/{reason}', 'App\Http\Controllers\SecurityOfficerAdminController@RejectToCorrect');
    Route::get('/security-officer-participant-details/{participant_id}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'details'])->name('securityParticipantDetails');

});

Route::group(['middleware' => 'role:data-entry'], function () {
    Route::get('/dataentry-participants', [App\Http\Controllers\DataEntryController::class, 'dataEntryParticipants'])->name('dataEntryParticipants');
    //Route::resource('dataentryController', 'App\Http\Controllers\DataEntryController');
    Route::get('/dataentry-participnat-add/{template_id}', [App\Http\Controllers\DataEntryController::class, 'participantAdd'])->name('participantAdd');
    Route::post('dataentryContoller/storeParticipant', [App\Http\Controllers\DataEntryController::class, 'storeParticipant'])->name('storeParticipant');
    Route::post('upload-file', 'App\Http\Controllers\FileUploadController@store');
});

Route::get('/send-notification', [App\Http\Controllers\NotificationController::class, 'sendAlertNotification']);
Route::get('/get-notification', [App\Http\Controllers\NotificationController::class, 'getNotifications']);
Route::get('/markAsRead-notification/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead']);

//Route::any('{query}',
//    function () {
//        return redirect('/');
//    })
//    ->where('query', '.*');

