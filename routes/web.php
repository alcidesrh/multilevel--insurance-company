<?php

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

Route::post('api/authenticate', 'UserController@authenticate')->name('authenticate');

Route::post('api/user-reset-password', 'UserController@resetPassword')->name('user.reset_password');

Route::get('users-select-test', 'UserController@usersForSelectTest')->name('user_test.users-select');//Test users

Route::group(['middleware' => 'auth:web', 'prefix' => 'api'], function () {

    Route::post('logout', 'UserController@logout')->name('logout');

    Route::get('user/', 'UserController@user')->name('user');

    Route::get('user/edit', 'UserController@edit')->name('user.edit');
    
    Route::post('user', 'UserController@save')->name('user.save');

    Route::post('user/pay', 'UserController@pay')->name('user.pay');

    Route::get('user-profile', 'UserController@profile')->name('user.item');

    Route::get('users', 'UserController@list')->name('user.list');

    Route::delete('users/{id}', 'UserController@delete')->name('users.delete');

    Route::post('users-select', 'UserController@usersForSelect')->name('user.users-select');

    Route::post('send-email', 'UserController@sendEmail')->name('user.sen_email');

    // Route::get('companies', 'CompanyController@list')->name('company.list');

    // Route::post('company-dinamic-list', 'CompanyController@dinamicList')->name('company.dinamic-list');

    // Route::get('company', 'CompanyController@edit')->name('company.edit');
    
    // Route::get('company/show', 'CompanyController@show')->name('company.show');

    // Route::get('company/personal', 'CompanyController@personal')->name('company.personal');

    // Route::post('company', 'CompanyController@save')->name('company.save');

    // Route::delete('company/{id}', 'CompanyController@delete')->name('company.delete');

    Route::get('computer', 'ComputerController@list')->name('computer.list');

    Route::post('computer', 'ComputerController@save')->name('computer.save');

    Route::post('computer/avialable', 'ComputerController@avialable')->name('computer.avialable');

    Route::delete('computer/{id}', 'ComputerController@delete')->name('computer.delete');

    Route::get('recruitment', 'RecruitmentController@list')->name('recruitment.list');

    Route::post('recruitment', 'RecruitmentController@save')->name('recruitment.save');

    Route::delete('recruitment/{id}', 'RecruitmentController@delete')->name('recruitment.delete');

    Route::get('recruitment-item/{id?}', 'RecruitmentController@item')->name('recruitment.item');

    Route::get('finance', 'PaymentController@list')->name('payment.list');

    Route::post('send-invitation', 'RecruitmentController@sendInvitation')->name('user.send-invitation');

    Route::get('categories', 'ProductController@listCategories')->name('category.list');

    Route::get('category', 'ProductController@category')->name('category.show');

    Route::post('category', 'ProductController@saveCategory')->name('category.save');

    Route::get('subscription', 'SubscriptionController@show')->name('subscription.show');

    Route::post('subscription', 'SubscriptionController@save')->name('subscription.save');

    Route::post('subscription/renew', 'SubscriptionController@renew')->name('subscription.renew');

    Route::get('subscriptions', 'SubscriptionController@list')->name('subscription.list');

    Route::delete('subscription/{id}', 'SubscriptionController@destroy')->name('subscription.delete');

    Route::get('license', 'LicenseController@show')->name('license.show');

    Route::post('license', 'LicenseController@save')->name('license.save');

    Route::get('licenses', 'LicenseController@list')->name('license.list');

    Route::delete('license/{id}', 'LicenseController@destroy')->name('license.delete');

    // Route::prefix('generic')->group(function () {
        
    //     Route::post('list', 'GenericController@list')->name('generic.list');

    //     Route::post('item', 'GenericController@item')->name('generic.item');
    // });

    Route::prefix('insurance')->group(function () {
        
        Route::get('obama-care', 'InsuranceController@listObamaCareInsurance')->name('insurance.listObamaCareInsurance');

        Route::post('obama-care', 'InsuranceController@saveObamaCareInsurance')->name('insurance.saveObamaCareInsurance');

        Route::delete('obama-care/{id}', 'InsuranceController@deleteObamaCareInsurance')->name('insurance.deleteObamaCareInsurance');

        Route::get('obama-care/{id}', 'InsuranceController@editObamaCareInsurance')->name('insurance.editObamaCareInsurance');

        Route::post('car', 'InsuranceController@saveCarInsurance')->name('insurance.saveCarInsurance');

        Route::get('car', 'InsuranceController@listCarInsurance')->name('insurance.listCarInsurance');

        Route::delete('car/{id}', 'InsuranceController@deleteCarInsurance')->name('insurance.deleteCarInsurance');

        Route::get('car/{id}', 'InsuranceController@editCarInsurance')->name('insurance.editCarInsurance');

        Route::post('live', 'InsuranceController@saveLiveInsurance')->name('insurance.saveLiveInsurance');

        Route::get('live', 'InsuranceController@listLiveInsurance')->name('insurance.listLiveInsurance');

        Route::delete('live/{id}', 'InsuranceController@deleteLiveInsurance')->name('insurance.deleteLiveInsurance');

        Route::get('live/{id}', 'InsuranceController@editLiveInsurance')->name('insurance.editLiveInsurance');

        Route::post('home', 'InsuranceController@saveHomeInsurance')->name('insurance.saveHomeInsurance');

        Route::get('home', 'InsuranceController@listHomeInsurance')->name('insurance.listHomeInsurance');

        Route::get('home/{id}', 'InsuranceController@editHomeInsurance')->name('insurance.editHomeInsurance');

        Route::delete('home/{id}', 'InsuranceController@deleteHomeInsurance')->name('insurance.deleteHomeInsurance');

        Route::post('business', 'InsuranceController@saveBusinessInsurance')->name('insurance.saveBusinessInsurance');

        Route::get('business', 'InsuranceController@listBusinessInsurance')->name('insurance.listBusinessInsurance');

        Route::get('business/{id}', 'InsuranceController@editBusinessInsurance')->name('insurance.editBusinessInsurance');

        Route::delete('business/{id}', 'InsuranceController@deleteBusinessInsurance')->name('insurance.deleteBusinessInsurance');

    });
    
});

Route::get('application', 'RecruitmentController@application')->name('application');

Route::post('application', 'RecruitmentController@saveApplication')->name('application.post');

Route::get('insurance/pdf/{type}/{id}/{show?}', 'InsuranceController@pdf')->name('insurance.pdfHomeInsurance');

Route::get('insurance/obama-care-pdf', 'InsuranceController@pdfObamaInsurance')->name('insurance.pdfObamaInsurance');

Route::get('insurance/obama-care-pdf/{id?}/{show?}', 'InsuranceController@pdfObamaInsurance')->name('insurance.pdfObamaInsurance');

Route::get('finance/pdf/{download?}', 'PaymentController@pdf')->name('payment.pdf');

Route::get('/test', function () {
    return view('emails.christmas', ['message2' =>'Hola', 'user' => User::find(20), 'number_account' => 90]);
});

Route::get('/file/download/{id}', function ($id) {
    $file = File::find($id);
    return Storage::disk('files')->download($file->path, $file->name);
});

Route::get('/token/{token}', function ($token) {
    $data = DB::table('reset_password')->where('token', $token)->first();
    return view('index', ['data' => ['email' => $data->email ?? null]]);
});

Route::get('/{route?}/{route2?}/{route3?}', function () {
    return view('index', ['data' => Route::current()->parameters() ?? null]);
});
