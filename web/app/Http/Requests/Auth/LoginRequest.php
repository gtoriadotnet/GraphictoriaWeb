<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Username;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

	/**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
			'username.exists' => 'That user doesn\'t seem to exist.'
		];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string'],//, 'exists:users,username'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
		
		$login_type = filter_var($this->input('username'), FILTER_VALIDATE_EMAIL) 
			? 'email' 
			: 'username';
		
		$this->merge([
			$login_type => $this->input('username')
		]);
		$loginModel = ($login_type == 'username' ? 'App\\Models\\Username' : 'App\\Models\\User');
		
		$previousUsername = $loginModel::where($login_type, $this->only($login_type))->first();
		if(!$previousUsername) {
			throw ValidationException::withMessages([
                'username' => $this->messages()['username.exists'],
            ]);
		}
		
		if($login_type == 'username')
		{
			$this->merge([
				'username' => $previousUsername->user->username
			]);
		}
		
        if(!Auth::attempt($this->only($login_type, 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('username')).'|'.$this->ip();
    }
}
