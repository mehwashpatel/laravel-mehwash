<?php

/*
 * Class devoted to formatting and serializing data for returning in the Public API.
 * All returns are currently set up for json return format.
 */
namespace App\Services;
use Illuminate\Support\Facades\Log;

class EncodingService {
	public function loginInfo( \App\User $user){
		$result_array = array();
		$result_array['api_session_token'] 	=$user->api_token; //\Hash::make($brand->api_session_token);
		$result_array['first_name']	 		=$user->name;
		$result_array['email']	 			=$user->email;
		return $result_array;
		
	}
	
	public function operationSuccess(){
		return ["operation_performed"=>true];
	}

	public function operationFail(){
		return ["operation_performed"=>false];
	}

	public function error401(){
		return array('error' => 'Access denied 401');
	}

	public function error403(){
		return array('error' => 'Access denied 403');
	}
	
	public function error404(){
		return array('error' => 'Not Found 404');
	}

	public function successWrapper($result_array){
		return json_encode( array('status'=>'1', 'data'=> $result_array));
	}

	public function failureWrapper($result_array, $status_code = '0'){
		return json_encode( array('status'=>$status_code, 'data'=> $result_array));

	}
}