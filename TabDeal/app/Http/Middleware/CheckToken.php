<?php

namespace App\Http\Middleware;

use App\Models\Front_user;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {
        $bearer = $request->bearerToken();
        if ($bearer != '') {
            // return User::find($token->tokenable_id);
            $token = DB::table('personal_access_tokens')->where('token', $bearer)->first();
            if ($user = Front_user::find($token->tokenable_id)) {
                Auth::login($user);
                $request["user_id"]=$user->id;
                DB::table('personal_access_tokens')->where('token', $bearer)->update(array(
                    'last_used_at'=> Carbon::now()->toDateTimeString()));
                return $next($request);
            }
        }

        return response()->json([
            'success' => false,
            'error' => 'Access denied.',
        ]);
    }
}
