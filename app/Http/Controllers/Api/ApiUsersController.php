<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Services\EncodingService;

class ApiUsersController extends Controller
{
	/**
     * API to create new user
     *
     * @return \Illuminate\Http\Response
     */
    public function createUser(Request $request)
    {
		$encoder = new EncodingService();
		if($request->has("first_name") && $request->has("email") && $request->has("pwd")){
			$hash_password = \Hash::make($request->input('pwd'));
				
			$check_email = \DB::table('users')
						->where('email', $request->input('email'))->count();
			if($check_email == 0){
				$data = new User;
				$data->name = $request->input('first_name');
				$data->email = $request->input('email');
				$data->password = $hash_password;
				$data->api_token = str_random(60);
				if($data->save()) {
					return response($encoder->successWrapper(['message' => 'Account created successfully']));
				}
			}else{
				return response($encoder->failureWrapper(['error' => 'Email Id exist']));
			}
		}
		return response($encoder->failureWrapper(['error' => 'Missing Parameters']));	
	}

    
}
