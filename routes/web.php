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
    Route::get('/company-participants/{companyId}/{eventId}', [App\Http\Controllers\CompanyAdminController::class, 'companyParticipants'])->name('companyParticipants');
    Route::get('/company-participants/{companyId}/{eventId}/{values}', [App\Http\Controllers\CompanyAdminController::class, 'getPaticipantsData'])->name('companyParticipantsData');
    Route::get('/company-participant-add', [App\Http\Controllers\CompanyAdminController::class, 'companyParticipantAdd'])->name('companyParticipantAdd');
    Route::get('/company-participant-edit/{id}', [App\Http\Controllers\CompanyAdminController::class, 'edit'])->name('companyParticipantEdit');
    Route::get('/company-accreditation-size/{eventId}/{companyId}', [App\Http\Controllers\CompanyAdminController::class, 'companyAccreditCategories'])->name('companyAccreditCategories');
    Route::get('companyAdminController/editCompanyAccreditSize/{id}', 'App\Http\Controllers\CompanyAdminController@editCompanyAccreditSize')->name('companyAdminControllerEditCompanyAccreditSize');
    Route::get('companyAdminController/storeCompanyAccrCatSize/{id}/{accredit_cat_id}/{size}/{company_id}/{event_id}', 'App\Http\Controllers\CompanyAdminController@storeCompanyAccrCatSize')->name('companyAdminControllerStoreCompanyAccrCatSize');
    Route::get('companyAdminController/destroyCompanyAccreditCat/{id}', 'App\Http\Controllers\CompanyAdminController@destroyCompanyAccreditCat')->name('companyAdminControllerDestroyCompanyAccreditCat');
    Route::get('companyAdminController/sendApproval/{companyId}/{eventId}', 'App\Http\Controllers\CompanyAdminController@sendApproval')->name('companyAdminControllerSendApproval');
    Route::get('companyAdminController/sendRequest/{staffId}', 'App\Http\Controllers\CompanyAdminController@sendRequest')->name('companyAdminControllerSendRequest');

    Route::resource('templateFormController', 'App\Http\Controllers\TemplateFormController');
    Route::get('/template-form/{template_id}/{company_id}/{event_id}', [App\Http\Controllers\TemplateFormController::class, 'index'])->name('templateForm');
    Route::get('/template-form-details/{participant_id}', [App\Http\Controllers\TemplateFormController::class, 'details'])->name('templateFormDetails');




    Route::get('/pdf-generate', [App\Http\Controllers\pdfController::class, 'generate'])->name('pdf-generate');

    Route::get('/subCompanies/{companyId}/{eventId}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanies'])->name('subCompanies');
    Route::get('/subCompanies/{companyId}/{eventId}/{values}', [App\Http\Controllers\CompanyAdminController::class, 'getsubCompaniesData'])->name('subCompaniesData');
    Route::get('/subCompany-add/{eventid}/{companyid}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanyAdd'])->name('subCompanyAdd');
    Route::get('/subCompany-edit/{id}/{eventid}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanyEdit'])->name('subCompanyEdit');
    Route::post('storeSubCompnay', [App\Http\Controllers\CompanyAdminController::class, 'storeSubCompnay'])->name('storeSubCompnay');
    Route::get('/subCompany-accreditation-size/{eventId}/{companyId}', [App\Http\Controllers\CompanyAdminController::class, 'subCompanyAccreditCategories'])->name('subCompanyAccreditCategories');
    Route::get('companyAdminController/Invite/{companyId}/{eventId}', 'App\Http\Controllers\CompanyAdminController@Invite')->name('subsidiariesInvite');

    Route::resource('dataentryController', 'App\Http\Controllers\DataEntryController');
    Route::get('/dataentrys/{companyId}/{eventId}', [App\Http\Controllers\DataEntryController::class, 'index'])->name('dataentrys');
    Route::get('/dataentry-add/{companyId}/{eventId}', [App\Http\Controllers\DataEntryController::class, 'dataEntryAdd'])->name('dataentryAdd');
    Route::get('/dataentry-edit/{id}/{companyId}/{eventId}', [App\Http\Controllers\DataEntryController::class, 'edit'])->name('dataentryEdit');
    Route::get('dataentryController/reset_password/{id}/{password}', 'App\Http\Controllers\DataEntryController@resetPassword')->name('resetDataEntryPassword');
	Route::get('getSubCompnayCities/{country_id}', [App\Http\Controllers\CompanyAdminController::class, 'getSubCompnayCities'])->name('getSubCompnayCities');
});

Route::group(['middleware' => 'role:event-admin'], function () {

    Route::resource('eventAdminController', 'App\Http\Controllers\EventAdminController');
    Route::get('/event-admin', [App\Http\Controllers\EventAdminController::class, 'index'])->name('event-admin');
    Route::get('/event-companies/{id}', [App\Http\Controllers\EventAdminController::class, 'eventCompanies'])->name('eventCompanies');
    Route::get('/event-companies/{id}/{values}', [App\Http\Controllers\EventAdminController::class, 'getData'])->name('eventCompaniesData');
    Route::get('/event-company-participants/{companyId}/{eventId}', [App\Http\Controllers\EventAdminController::class, 'eventCompanyParticipants'])->name('eventCompanyParticipants');
    Route::get('/event-company-participants/{companyId}/{eventId}/{values}', [App\Http\Controllers\EventAdminController::class, 'getPaticipantsData'])->name('eventCompanyParticipantsData');
    Route::get('eventAdminController/Invite/{companyId}/{eventId}', 'App\Http\Controllers\EventAdminController@Invite')->name('eventAdminControllerInvite');
    Route::get('eventAdminController/Approve/{staffId}', 'App\Http\Controllers\EventAdminController@Approve')->name('eventAdminControllerApprove');
    Route::get('eventAdminController/Reject/{staffId}', 'App\Http\Controllers\EventAdminController@Reject')->name('eventAdminControllerReject');
    Route::get('eventAdminController/RejectToCorrect/{staffId}/{reason}', 'App\Http\Controllers\EventAdminController@RejectToCorrect')->name('eventAdminControllerRejectToCorrect');

    Route::resource('companyController', 'App\Http\Controllers\CompanyController');

    Route::get('/companies', [App\Http\Controllers\CompanyController::class, 'index'])->name('companies');
    Route::get('/company-add/{eventid}', [App\Http\Controllers\CompanyController::class, 'companyAdd'])->name('companyAdd');
    Route::get('/company-edit/{id}/{eventid}', [App\Http\Controllers\CompanyController::class, 'edit'])->name('companyEdit');
    Route::get('/company-accreditation-size-new/{id}/{eventid}', [App\Http\Controllers\CompanyController::class, 'companyAccreditCat'])->name('companyAccreditCat');
    Route::get('companyController/editCompanyAccreditSize/{id}', 'App\Http\Controllers\CompanyController@editCompanyAccreditSize')->name('companyControllerEditCompanyAccreditSize');
    Route::get('companyController/storeCompanyAccrCatSize/{id}/{accredit_cat_id}/{size}/{company_id}/{event_id}', 'App\Http\Controllers\CompanyController@storeCompanyAccrCatSize')->name('companyControllerStoreCompanyAccrCatSize');
    Route::get('companyController/destroyCompanyAccreditCat/{id}', 'App\Http\Controllers\CompanyController@destroyCompanyAccreditCat')->name('companyControllerDestroyCompanyAccreditCat');
    Route::get('companyController/Approve/{companyId}/{eventId}', 'App\Http\Controllers\CompanyController@Approve')->name('companyControllerApprove');
	Route::get('getCities/{country_id}', [App\Http\Controllers\CompanyController::class, 'getCities'])->name('getCities');
    Route::get('badge-generate/{staffId}', 'App\Http\Controllers\GenerateBadgeController@generate')->name('badgeGenerate');
    Route::get('badge-preview/{staffId}', 'App\Http\Controllers\GenerateBadgeController@getBadgePath')->name('badgePreview');
    Route::get('badge-print/{staffId}', 'App\Http\Controllers\GenerateBadgeController@printBadge')->name('badgePrint');
    Route::get('/event-participant-details/{participant_id}', [App\Http\Controllers\EventAdminController::class, 'details'])->name('participantDetails');

    Route::resource('fullFillmentController', 'App\Http\Controllers\FullFillmentController');
    Route::get('/selections', [App\Http\Controllers\FullFillmentController::class, 'index'])->name('Selections');
    Route::get('fullFillmentController/getCompanies/{field_id}', [App\Http\Controllers\FullFillmentController::class, 'getCompanies'])->name('getCompanies');
    Route::get('/all-participants/{event_id}/{company_id}/{accredit_id}/{checked}', [App\Http\Controllers\FullFillmentController::class, 'allParticipants'])->name('allParticipants');
    Route::get('fullFillmentController/getParticipants/{event_id}/{company_id}/{accredit_id}', [App\Http\Controllers\FullFillmentController::class, 'getParticipants'])->name('getParticipants');

    Route::get('fullFillmentController/getParticipantsData/{event_id}/{company_id}/{accredit_id}', [App\Http\Controllers\FullFillmentController::class, 'getParticipantsData'])->name('getParticipantsData');

    Route::post('/pdf-generate', [App\Http\Controllers\pdfController::class, 'generate'])->name('pdf-generate');
    Route::post('/fullFillment', [App\Http\Controllers\FullFillmentController::class, 'fullFillment'])->name('fullFillment');
    Route::get('fullFillmentController/getEventACs/{event_id}', [App\Http\Controllers\FullFillmentController::class, 'getEventACs'])->name('getEventACs');
    Route::get('fullFillmentController/getEventCompanyACs/{event_id}/{company_id}', [App\Http\Controllers\FullFillmentController::class, 'getEventCompanyACs'])->name('getEventCompanyACs');

    Route::get('/event-participnat-add/{template_id}/{companyId}/{eventId}', [App\Http\Controllers\EventAdminController::class, 'eventParticipantAdd'])->name('eventParticipantAdd');
    Route::post('eventContoller/eventStoreParticipant', [App\Http\Controllers\EventAdminController::class, 'eventStoreParticipant'])->name('eventStoreParticipant');
});

Route::group(['middleware' => 'role:super-admin'], function () {
    Route::resource('EventController', 'App\Http\Controllers\EventController');
    Route::get('eventsData/{all}/{values}', 'App\Http\Controllers\EventController@getData')->name('eventsData');

    Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events');
    Route::get('/eventsShowAll/{status}', [App\Http\Controllers\EventController::class, 'showAll'])->name('eventsShowall');
    Route::get('/event-admins/{event_id}', [App\Http\Controllers\EventController::class, 'eventAdmins'])->name('eventAdmins');
    Route::get('/event-admins-remove/{id}', [App\Http\Controllers\EventController::class, 'eventAdminsRemove'])->name('eventAdminsRemove');
    Route::post('/event-admins-add', [App\Http\Controllers\EventController::class, 'eventAdminsAdd'])->name('eventAdminsAdd');
    Route::get('/event-security-officers/{id}', [App\Http\Controllers\EventController::class, 'eventSecurityOfficers'])->name('eventSecurityOfficers');
    Route::get('/event-security-officers-remove/{id}', [App\Http\Controllers\EventController::class, 'eventSecurityOfficersRemove'])->name('eventSecurityOfficersRemove');
    Route::post('/event-security-officers-add', [App\Http\Controllers\EventController::class, 'eventSecurityOfficersAdd'])->name('eventSecurityOfficersAdd');
    Route::get('/event-security-categories/{id}', [App\Http\Controllers\EventController::class, 'eventSecurityCategories'])->name('eventSecurityCategories');
    Route::get('/event-security-categories-remove/{id}', [App\Http\Controllers\EventController::class, 'eventSecurityCategoriesRemove'])->name('eventSecurityCategoriesRemove');
    Route::post('/event-security-categories-add', [App\Http\Controllers\EventController::class, 'eventSecurityCategoriesAdd'])->name('eventSecurityCategoriesAdd');
    Route::get('/event-check-same-organizer/{id}', [App\Http\Controllers\EventController::class, 'eventCheckSameEventOrganizer'])->name('eventCheckSameEventOrganizer');
    Route::get('/event-accreditation-categories/{id}', [App\Http\Controllers\EventController::class, 'eventAccreditationCategories'])->name('eventAccreditationCategories');
    Route::get('/event-accreditation-categories-remove/{id}', [App\Http\Controllers\EventController::class, 'eventAccreditationCategoriesRemove'])->name('eventAccreditationCategoriesRemove');
    Route::post('/event-accreditation-categories-add', [App\Http\Controllers\EventController::class, 'eventAccreditationCategoriesAdd'])->name('eventAccreditationCategoriesAdd');
    Route::get('/event-add', [App\Http\Controllers\EventController::class, 'eventAdd'])->name('eventAdd');
    Route::get('/event-edit/{id}', [App\Http\Controllers\EventController::class, 'edit'])->name('eventEdit');
    Route::get('/titles', [App\Http\Controllers\TitleController::class, 'index'])->name('titles');
    Route::get('/companyCategories', [App\Http\Controllers\CompanyCategoryController::class, 'index'])->name('companyCategories');

    Route::post('/event-update-logo', [App\Http\Controllers\EventController::class, 'updateLogo'])->name('updateLogo');


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
    //Route::get('/participant-add', [App\Http\Controllers\ParticipantController::class, 'participantAdd'])->name('participantAdd');
    Route::get('/participant-edit/{id}', [App\Http\Controllers\ParticipantController::class, 'edit'])->name('participantEdit');

    Route::get('/templates', [App\Http\Controllers\TemplateController::class, 'index'])->name('templates');
    Route::get('/template-add', [App\Http\Controllers\TemplateController::class, 'templateAdd'])->name('templateAdd');
    Route::resource('templateController', 'App\Http\Controllers\TemplateController');
    Route::get('templateController/destroy/{id}', 'App\Http\Controllers\TemplateController@destroy');
    Route::get('templateController/changeStatus/{id}/{status}', 'App\Http\Controllers\TemplateController@changeStatus')->name('templateControllerChangeStatus');
    Route::get('templateController/changeLock/{id}/{status}', 'App\Http\Controllers\TemplateController@changeLock')->name('templateControllerChangeLock');

	Route::resource('emailTemplateController', 'App\Http\Controllers\EmailTemplateController');
    Route::get('email-templates', [App\Http\Controllers\EmailTemplateController::class, 'index'])->name('emailTemplates');


    Route::resource('userController', 'App\Http\Controllers\UserController');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/users-add', [App\Http\Controllers\UserController::class, 'userAdd'])->name('userAdd');
    Route::get('/users-edit/{id}', [App\Http\Controllers\UserController::class, 'userEdit'])->name('userEdit');
    Route::get('userController/reset_password/{id}/{password}', 'App\Http\Controllers\UserController@resetPassword')->name('userControllerResetPassword');

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
    Route::get('templateBadgeFieldController/destroy/{field_id}', 'App\Http\Controllers\TemplateBadgeFieldController@destroy')->name('TemplateBadgeFieldControllerDestroy');
    Route::get('badge-design-generate/{badgeId}', 'App\Http\Controllers\GenerateBadgeController@generatePreview')->name('badgeDesignGenerate');

	Route::get('/template-badge-bg/{badge_id}', [App\Http\Controllers\TemplateBadgeBGController::class, 'index'])->name('templateBadgeBGs');
    Route::resource('templateBadgeBGController', 'App\Http\Controllers\TemplateBadgeBGController');

	Route::get('/contact-titles/{contact_id}', [App\Http\Controllers\ContactTitleController::class, 'index'])->name('contactTitles');
    Route::resource('contactTitlesController', 'App\Http\Controllers\ContactTitleController');
    Route::get('contactTitlesController/destroy/{field_id}', 'App\Http\Controllers\ContactTitleController@destroy')->name('removeContactTitle');
    Route::get('contactTitlesController/store/{contact_id}/{title_id}', 'App\Http\Controllers\ContactTitleController@store')->name('storeContactTitle');

    Route::get('templatesData/{values}', 'App\Http\Controllers\TemplateController@getData1')->name('templatesData1');
    Route::get('/allEvents', [App\Http\Controllers\EventController::class, 'index'])->name('allEvents');
    Route::get('/eventComplete/{eventId}', [App\Http\Controllers\EventController::class, 'eventComplete'])->name('eventComplete');
});

Route::group(['middleware' => 'role:security-officer'], function () {

    Route::resource('securityOfficerAdminController', 'App\Http\Controllers\SecurityOfficerAdminController');
    Route::get('/security-officer-admin', [App\Http\Controllers\SecurityOfficerAdminController::class, 'index'])->name('security-officer-admin');
    Route::get('/security-officer-companies/{id}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'securityOfficerCompanies'])->name('securityOfficerCompanies');
    Route::get('/security-officer-companies/{id}/{values}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'getData'])->name('securityOfficerCompaniesData');
    Route::get('/security-officer-company-participants/{id}/{companyId}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'securityOfficerCompanyParticipants'])->name('securityOfficerCompanyParticipants');
    Route::get('/security-officer-company-participants/{companyId}/{eventId}/{values}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'getPaticipantsData'])->name('securityOfficerCompanyParticipantsData');
    Route::get('securityOfficerAdminController/Approve/{staffId}', 'App\Http\Controllers\SecurityOfficerAdminController@Approve')->name('securityOfficerAdminControllerApprove');
    Route::get('securityOfficerAdminController/Reject/{staffId}', 'App\Http\Controllers\SecurityOfficerAdminController@Reject')->name('securityOfficerAdminControllerReject');
    Route::get('securityOfficerAdminController/RejectToCorrect/{staffId}/{reason}', 'App\Http\Controllers\SecurityOfficerAdminController@RejectToCorrect')->name('securityOfficerAdminControllerRejectToCorrect');
    Route::get('/security-officer-participant-details/{participant_id}', [App\Http\Controllers\SecurityOfficerAdminController::class, 'details'])->name('securityParticipantDetails');

});

Route::group(['middleware' => 'role:data-entry'], function () {
    Route::get('/data-entry', [App\Http\Controllers\DataEntryController::class, 'dataEntryEvents'])->name('dataEntryEvents');
    Route::get('/dataentry-participants/{companyId}/{eventId}', [App\Http\Controllers\DataEntryController::class, 'dataEntryParticipants'])->name('dataEntryParticipants');
    Route::get('/dataentry-participants/{companyId}/{eventId}/{values}', [App\Http\Controllers\DataEntryController::class, 'getPaticipantsData'])->name('dataEntryParticipantsData');
    //Route::resource('dataentryController', 'App\Http\Controllers\DataEntryController');
    Route::get('/dataentry-participnat-add/{template_id}/{companyId}/{eventId}', [App\Http\Controllers\DataEntryController::class, 'participantAdd'])->name('participantAdd');
    Route::post('dataentryContoller/storeParticipant', [App\Http\Controllers\DataEntryController::class, 'storeParticipant'])->name('storeParticipant');
});

Route::post('upload-file', 'App\Http\Controllers\FileUploadController@store');
Route::post('upload-logo', 'App\Http\Controllers\FileUploadController@eventLogoUpload')->name('uploadLogo');

Route::get('/send-notification', [App\Http\Controllers\NotificationController::class, 'sendAlertNotification']);
Route::get('/get-notification', [App\Http\Controllers\NotificationController::class, 'getNotifications']);
Route::get('/markAsRead-notification/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead']);

Route::resource('focalpointController', 'App\Http\Controllers\FocalPointController');
Route::get('/focalpoints', [App\Http\Controllers\FocalPointController::class, 'index'])->name('focalpoints');
Route::get('/focalpoint-add', [App\Http\Controllers\FocalPointController::class, 'focalpointAdd'])->name('focalpointAdd');
Route::get('/focalpoint-edit/{id}', [App\Http\Controllers\FocalPointController::class, 'edit'])->name('focalpointEdit');
Route::get('focalpointController/reset_password/{id}/{password}', 'App\Http\Controllers\FocalPointController@resetPassword')->name('focalPointControllerResetPassword');

Route::get('/search-participant/{fullName}/{companyId}', [App\Http\Controllers\TemplateFormController::class, 'searchParticipants'])->name('searchParticipants');

//Route::any('{query}',
//    function () {
//        return redirect('/');
//    })
//    ->where('query', '.*');

