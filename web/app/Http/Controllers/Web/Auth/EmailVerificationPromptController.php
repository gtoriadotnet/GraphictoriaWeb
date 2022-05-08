<?php

namespace App\Http\Controllers\Web\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email');
    }
}
