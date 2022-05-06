<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class GoogleRecaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'g-recaptcha-response.required' => 'Please solve the captcha.'
		];
	}

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'remoteip' => request()->ip(),
                    'response' => $value
                ]
            ]
        );
        $body = json_decode((string)$response->getBody());
        return $body->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please solve the captcha.';
    }
}
