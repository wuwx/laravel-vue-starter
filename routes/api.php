<?php

$api = app('Dingo\Api\Routing\Router');

/*
|--------------------------------------------------------------------------
| Our Main Api Domain if We use api.domain.com
| Avoid Showing the Default Site Content 
|--------------------------------------------------------------------------
|
*/

// $api->version('v1', function ($api) {
// $api->get('/', ['as' => 'api.index', 'uses' => 'Api\DomainController@index']);
// });

/*
|--------------------------------------------------------------------------
| Roles and Abilities Middleware
|--------------------------------------------------------------------------
|
*/
$api->version('v1', function ($api) {
    $api->group(['middleware' => ['auth:api']], function ($api) {
        $api->group(['middleware' => ['roles'],
        'roles' => ['admin','manager'],
        'rolesStrict' => true,
        'prefix' => 'roles'
         ],function ($api) {
         $api->get('/', function(){
        return 'Im an Admin HooRaaaah!';});
        });
        $api->group(['middleware' => ['abilities'],
        'abilities' => ['view-dashboard', 'login'],
        'abilitiesStrict' => true,
        'prefix' => 'abilities'
        ], function ($api) {
         $api->get('/', function(){
        return 'Power!';});
        });
    }); // End Auth:Api Group Routes

});

/*
|--------------------------------------------------------------------------
| Authentication , Recheck MIddleware for Guest in Logout
| Also We Must Use Auth Not JWT anymore since we make JWT as OUr Default
|--------------------------------------------------------------------------
|
*/
$api->version('v1', function ($api) {
$api->group(['prefix' => 'auth'], function($api) {
        // Working Tested , We Added check in exception in guest middleware for Unwated Behaviour
        $api->post('check', 'Api\V1\Auth\Controllers\LoginController@check');
        $api->post('logout', 'Api\V1\Auth\Controllers\LoginController@logout');
        $api->post('login', 'Api\V1\Auth\Controllers\LoginController@login');
        $api->post('signup', 'Api\V1\Auth\Controllers\SignUpController@signUp');
        $api->post('recover', 'Api\V1\Auth\Controllers\ForgotPasswordController@sendResetEmail');

        // This is the Link we Need to Add in Our Vue Router
        // forgotpassword/{token}

        $api->post('reset', 'Api\V1\Auth\Controllers\ResetPasswordController@resetPassword');
        // we get token from the url segment
        // required: email, password , and password_confirmation , token
    });
});