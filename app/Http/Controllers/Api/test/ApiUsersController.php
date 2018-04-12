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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUser(Request $request)
    {
        $encoder = new EncodingService();
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
				return response($encoder->successWrapper('Account created successfully'));
			}
		}else{
			return response($encoder->failureWrapper('Email Id exist'));
		}
		return response($encoder->failureWrapper($encoder->error404()),404);	
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
