<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(array(
	'prefix' => 'api',
	'middleware' => 'apiauth'
) ,
function ()
{
	
	Route::post('login', ['uses' => 'Api\ApiAccessController@login']);
	Route::post('create_user', ['uses' => 'Api\ApiUsersController@createUser']);
	Route::get('view_files/{userid}', ['uses' => 'Api\ApiFilesFoldersListController@viewFiles']);
	Route::post('upload_files', ['uses' => 'Api\ApiFilesFoldersListController@uploadFiles']);
	Route::post('create_folder', ['uses' => 'Api\ApiFilesFoldersListController@createFolders']);

});

