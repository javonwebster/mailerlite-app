<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MailerLiteApi\MailerLite;

class SubscriberExists implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            //get key from DB
            $key = DB::table('keys')->whereNotNull('created_at')->first();
            $mailerliteClient = new MailerLite($key->api_key);
            //check if subscriber email already exists
            $subscriber = $mailerliteClient->subscribers()->find($value);
            if (isset($subscriber->error)){
                Log::debug($subscriber->error->message);
                return true;
            }
            Log::error('subscriber already exists.');
            return false;
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
        return 'Subscriber already exists';
    }
}
