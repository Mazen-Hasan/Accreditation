<?php

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




Route::group(['middleware' => 'role:company-admin'], function() {


    Route::resource('companyAdminController', 'App\Http\Controllers\CompanyAdminController');
    Route::get('/company-admin', [App\Http\Controllers\CompanyAdminController::class, 'index'])->name('company-admin');
    Route::get('/company-participants', [App\Http\Controllers\CompanyAdminController::class, 'companyParticipants'])->name('companyParticipants');
    Route::get('/company-participant-add', [App\Http\Controllers\CompanyAdminController::class, 'companyParticipantAdd'])->name('companyParticipantAdd');
    Route::get('/company-participant-edit/{id}', [App\Http\Controllers\CompanyAdminController::class, 'edit'])->name('companyParticipantEdit');
    Route::get('/company-accreditation-size/{eventId}', [App\Http\Controllers\CompanyAdminController::class, 'companyAccreditCategories'])->name('companyAccreditCategories');
    Route::get('companyAdminController/editCompanyAccreditSize/{id}', 'App\Http\Controllers\CompanyAdminController@editCompanyAccreditSize');
    Route::get('companyAdminController/storeCompanyAccrCatSize/{id}/{accredit_cat_id}/{size}/{company_id}/{event_id}', 'App\Http\Controllers\CompanyAdminController@storeCompanyAccrCatSize');
    Route::get('companyAdminController/destroyCompanyAccreditCat/{id}', 'App\Http\Controllers\CompanyAdminController@destroyCompanyAccreditCat');
    Route::get('companyAdminController/sendApproval/{companyId}/{eventId}', 'App\Http\Controllers\CompanyAdminController@sendApproval');
//    Route::resource('participantController', 'App\Http\Controllers\ParticipantController');
//    Route::get('/participants', [App\Http\Controllers\ParticipantController::class, 'index'])->name('participants');
//    Route::get('/participant-add', [App\Http\Controllers\ParticipantController::class, 'participantAdd'])->name('participantAdd');
//    Route::get('/participant-edit/{id}', [App\Http\Controllers\ParticipantController::class, 'edit'])->name('participantEdit');
});

Route::group(['middleware' => 'role:event-admin'], function() {

//    Route::resource('EventController', 'App\Http\Controllers\EventController');
//    Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events');
//    Route::get('/event-add', [App\Http\Controllers\EventController::class, 'eventAdd'])->name('eventAdd');
//    Route::get('/event-edit/{id}', [App\Http\Controllers\EventController::class, 'edit'])->name('eventEdit');
//    Route::get('EventController/remove/{event_security_category_id}', 'App\Http\Controllers\EventController@remove');
//    Route::get('EventController/storeEventSecurityCategory/{event_id}/{security_category_id}', 'App\Http\Controllers\EventController@storeEventSecurityCategory');
    Route::resource('eventAdminController', 'App\Http\Controllers\EventAdminController');
    Route::get('/event-admin', [App\Http\Controllers\EventAdminController::class, 'index'])->name('event-admin');
    Route::get('/event-companies/{id}', [App\Http\Controllers\EventAdminController::class, 'eventCompanies'])->name('eventCompanies');

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

});

Route::group(['middleware' => 'role:super-admin'], function() {
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
//    Route::get('contactController/storeContactTitle', 'App\Http\Controllers\ContactController@storeContactTitle');

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

    Route::resource('userController', 'App\Http\Controllers\UserController');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/users-add', [App\Http\Controllers\UserController::class, 'userAdd'])->name('userAdd');
    Route::get('/users-edit/{id}', [App\Http\Controllers\UserController::class, 'userEdit'])->name('userEdit');
    Route::get('userController/reset_password/{id}/{password}', 'App\Http\Controllers\UserController@resetPassword');

});

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
