<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Services\EncodingService;

class ApiAccessController extends Controller
{
	public function login(Request $request) {
		$encoder = new EncodingService();
		$email = $request->input("email"); //$_POST["username"]; 
		$password = $request->input("pwd");
		//$channelpartnerid = $request->session()->get('channel_partner_id');
		
		$users = User::where('email','=',$email)->first();
		$number = count($users);
		if(isset($users->password)){
			if (\Hash::check($password, $users->password)){
				$result_array = $encoder->loginInfo($users);
				return $encoder->successWrapper($result_array);
			}else {
				return $encoder->failureWrapper(['error' => 'Email and Password do not match.']);
			}
        }else {
			return $encoder->failureWrapper(['error' => 'Email and Password do not match.']);
		}

		
		
	}
}
