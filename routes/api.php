<?php


use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\Public\CatalogController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Application\FormController;
use App\Http\Controllers\Application\OrganizationOwnersDirectorController;
use App\Http\Controllers\Application\FormLocationController;
use App\Http\Controllers\Application\FormManufacturingData;
use App\Http\Controllers\Application\FormFinancialInformationController;
use App\Http\Controllers\Application\ApplianceBurnerController;
use App\Http\Controllers\Application\FormApplianceAndBurnerInfo;
use App\Http\Controllers\Application\FormExpectedGasNeed;
use App\Http\Controllers\Application\FormIngredientsInfoProductionsController;
use App\Http\Controllers\Application\FormAuthorityContactDetailsController;
use App\Http\Controllers\Application\FormAttachmentsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FeeRemindersController;
use App\Http\Controllers\IpnController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\OauthController;
//Auth Api
//labib
Route::controller(UserAuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/send-verify-mail/{email}', 'sendVerifyMail');
    Route::get('verify-mail/{token}', 'verificationMail');
    Route::post('/forgot-password', 'forgot');
    Route::post('/change-password','changePass');

});


Route::get('permissions', [RoleController::class, 'permissions'])->middleware(['auth:api', 'permission:permission.show']);
// Labib

Route::middleware('auth:api')->group(function () {
    Route::get('profiledata', [UserAuthController::class, 'user']);

});


Route::group(["middleware" => "auth:api"], function () {
    Route::get('user', [UserAuthController::class, 'user']);
    Route::post('logout', [UserAuthController::class, 'logout']);
    Route::resource('locations', LocationController::class);
    Route::resource('organizations', OrganizationController::class);
    Route::resource('activity_logs', ActivityLogController::class);
    Route::resource('settings', SettingsController::class);

    Route::put('payment-getaway', [SettingsController::class, 'paymentGetawayUpdate'])->name('payment-getaway.update');


    Route::group(['prefix' => 'applicant', 'as' => 'applicant'], function () {
        Route::resource('forms', FormController::class);
        Route::resource('owners-directors', OrganizationOwnersDirectorController::class);
        Route::resource('form-locations', FormLocationController::class);
        Route::resource('form-manufacturing', FormManufacturingData::class);
        Route::resource('form-financial-information', FormFinancialInformationController::class);
        Route::resource('form-appliance-burner', ApplianceBurnerController::class);
        Route::resource('form-appliance-burner-info', FormApplianceAndBurnerInfo::class);
        Route::resource('form-expected-gas-need', FormExpectedGasNeed::class);
        Route::resource('form-ingredients-info-production', FormIngredientsInfoProductionsController::class);
        Route::resource('form-authority-contact-details', FormAuthorityContactDetailsController::class);
        Route::resource('form-attachments', FormAttachmentsController::class);
        Route::post('form-attachments-update/{id}', [FormAttachmentsController::class, 'update'])->name('form-attachments-update');

    });

    Route::group(['prefix' => 'admin', 'as' => 'admin'], function () {
        Route::get('applicant-request-list', [ApplicationController::class, 'index'])
            ->middleware(['permission:user-application-request.show'])
            ->name('application-request-list');
        Route::get('applicant-request-show/{id}', [ApplicationController::class, 'show'])
            ->middleware(['permission:user-application-request.show'])
            ->name('applicant-request-show');
        Route::put('applicant-request-update/{id}', [ApplicationController::class, 'update'])
            ->middleware('permission:user-application-request.edit')
            ->name('applicant-request-update');
        Route::get('member-list', [ApplicationController::class, 'member'])
            ->middleware(['permission:member-list'])
            ->name('member-list');
        Route::get('member-show/{id}', [ApplicationController::class, 'memberShow'])
            ->middleware(['permission:member.show'])
            ->name('member-show');
        Route::put('member-update/{id}', [ApplicationController::class, 'memberUpdate'])
            ->middleware('permission:member.edit')
            ->name('member-update');
        Route::resource('fee-reminder', FeeRemindersController::class);


    });


    Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
        Route::post('send-message', [MessageController::class, 'sendMessage']);
        Route::get('/get-recent-conversation', [MessageController::class, 'getRecentConversation']);
        Route::get('/conversation-show/{receiver_id}', [MessageController::class, 'conversationShow']);
        Route::get('/user-show/{id}', [MessageController::class, 'userShow']);
    });


    //for application fee
    Route::get('application-fee-process', [PaymentsController::class, 'applicationFeeProcess']);
    Route::get('application-fee-verification/{token}', [PaymentsController::class, 'storeApplicationFee']);

    // for fee remainder payments
    Route::get('service-fee-process/{id}', [PaymentsController::class, 'serviceFeeProcess']);
    Route::get('service-fee-verification/{token}', [PaymentsController::class, 'storeServiceFee']);
    Route::resource('payments', PaymentsController::class);
    Route::get('application-payment-check', [PaymentsController::class, 'applicationPaymentCheck']);


    //application_profile
    Route::get('application-form-view', [FormController::class, 'applicationFormView']);
    Route::post('/profile/image', [UserAuthController::class, 'updateImage']);
    Route::post('/profile/change-password', [UserAuthController::class, 'changePassword']);
    Route::post('/profile/update-content', [UserAuthController::class, 'updateContent']);






});

Route::group(["prefix" => "public", "name" => "public"], function () {
    // arun
    Route::get('organization-categories', [CatalogController::class, 'organizationCategories']);

    // apurbo
    Route::get('organization-ownership-types', [CatalogController::class, 'organizationOwnershipTypes']);
    Route::get('industry-types', [CatalogController::class, 'industryTypes']);
    Route::get('production-types', [CatalogController::class, 'productionTypes']);
    Route::get('land-ownerships', [CatalogController::class, 'landOwnerships']);

    Route::post('spg-ipn',[IpnController::class, 'spgIpn']);

    //labib
    Route::post('/token',[OauthController::class,'token'])->name('token');
    Route::group(['middleware' => 'auth:external'], function () {
        Route::post('/external-info-submission', [OauthController::class, 'store'])->name('submission');
        Route::get('/external-info-submission/status', [OauthController::class, 'getStatus']);
    });


});


