<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use MailerLiteApi\MailerLite;

class ValidApiKey implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        try {
            $mailerliteClient = new MailerLite($value);
            $stats = $mailerliteClient->stats()->get();
            if (isset($stats->error)){
                Log::error('api key invalid.');
                return false;
            }
            Log::debug('api key valid.');
            return true;
        } catch (\Exception $exception) {
            Log::error('error validating api key: '.$exception->getMessage());
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The API Key entered is invalid';
    }
}
