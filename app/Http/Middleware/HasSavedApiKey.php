<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HasSavedApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $apiKey = DB::table('keys')->first();
            if ($apiKey == null) {
                Log::debug('No valid api key saved.');
                return redirect('/?no-key=1');
            }
        } catch (\Exception $exception) {
            Log::debug('Error fetching key: '.$exception->getMessage());
            return redirect('/?no-key=1');
        }
        return $next($request);
    }
}
