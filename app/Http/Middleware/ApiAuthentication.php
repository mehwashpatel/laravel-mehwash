<?php namespace App\Http\Middleware;

use Closure;

//checks the supplied email and access code against the database
class ApiAuthentication {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		//find the user
		$userrepo = new UserRepository();
		$user = $userrepo->findByEmail($request->input('email'));
		if ($user->brand->api_access_code !== $request->input('access_code'))
		{
			return response('Unauthorized.', 401);
		}

		return $next($request);
	}
}