<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        $rules = [
            'g-recaptcha-response' => 'required|captcha',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        return $next($request);
    }
}
