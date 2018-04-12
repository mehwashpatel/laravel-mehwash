<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Services\EncodingService;
use Illuminate\Http\Request;
class ApiBasicAuthentication {
	public function handle($request, Closure $next) {
		$encoder = new EncodingService();
		if(null!==$request->header('X-Authorization-Key')&&$request->header('X-Authorization-Key')!=""){ 
			$channel_parter_key = '7ccUTwINU713rppI4uzLqSaDYM6RE7YH1R8tUZXt4jyHhmHnpqDc2UymxMeWMe2J'; 
			$channel_partner = 0;
			if($request->header('X-Authorization-Key') == $channel_parter_key){
				$channel_partner = 1;
			}
			
			if($channel_partner == 1){
				$channel_partner_id = 1;
				$request->session()->put('channel_partner_id', $channel_partner_id);
				if(null!==$request->header('X-Authorization') && $request->header('X-Authorization')!=""){
					$result = \App\User::where('api_token','=',$request->header('X-Authorization'))->first();
					if($result){
						return $next($request);
					}else{
						return response($encoder->failureWrapper($encoder->error403()),403); //Access token mismatch error
					}
				}else if($this->ifShouldGetThrough($request)) { // login/register user exceptions
						return $next($request);
				}else{
					if($request->path()=="api/login"){
						return response($encoder->failureWrapper(['error' => 'Please enter both email and password.']));
					}else{
						return response($encoder->failureWrapper($encoder->error403()),403); //no access token found
					}
				}
			}else{
				return response($encoder->failureWrapper($encoder->error403()),403); //Project key mismatch error
			}

		}else{
			return response($encoder->failureWrapper($encoder->error403()),403);	//no X-Authorization-Key found
		}
	}
	
	public function ifShouldGetThrough($request){
		$allowed_path_array = ["api/create_user"];
		
		if($request->path()=="api/login" && $request->has("email")&&$request->has("pwd")){
			return true;
		}else if(in_array($request->path(), $allowed_path_array)){
			return true;
		}
		return false;
	}
}