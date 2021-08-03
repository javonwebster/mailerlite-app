<?php


namespace App\Util;


class Helpers
{
    /**
     * @param $subscriber
     * @return string
     * Gets the country from a subscriber object
     */
    public static function getSubscriberCountry($subscriber):string
    {
        $country = '';
        foreach ($subscriber->fields as $field){
            if ($field->key == 'country') {
                $country = $field->value;
            }
        }
        return $country;
    }

    /**
     * @param $subscriber
     * @param false $timeOnly
     * @return false|string
     * Gets the date subscribed. If that is not set, it will use the date created
     */
    public static function getSubscriberDateSubscribed($subscriber, $timeOnly = false){
        if (isset($subscriber->date_subscribe)){
            return $timeOnly ? date('H:i:s', strtotime($subscriber->date_subscribe)) : date('j/n/Y', strtotime($subscriber->date_subscribe));
        }
        return $timeOnly ? date('H:i:s', strtotime($subscriber->date_created)) : date('j/n/Y', strtotime($subscriber->date_created));
    }
}
