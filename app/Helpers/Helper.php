<?php

namespace App\Helpers;
use Config;
use GuzzleHttp\Client;
use App\Models\Admin;
use App\Models\User;

class Helper
{
    /**
     * generate random numbers in php with no repeats
     *
     * @param int $length
     * 
     * @return String
     */
    public static function randomDigits($length){
        $numbers = range(0,9);
        shuffle($numbers);
        $digits = '';
        for($i = 0;$i < $length;$i++)
           $digits .= $numbers[$i];
        return $digits;
    }

     /**
     * send sms
     *
     * @param int $message
     * 
     * @return String
     */
    public static function sendCodeSms($code, $phone){

        $phone = ltrim($phone, '0');
        $sns = \AWS::createClient('Sns');

        $args = array(
            "MessageAttributes" => [
                        'AWS.SNS.SMS.SenderID' => [
                            'DataType' => 'String',
                            'StringValue' => 'SENDERID'
                        ],
                        'AWS.SNS.SMS.SMSType' => [
                            'DataType' => 'String',
                            'StringValue' => 'Transactional'
                        ]
                    ],
            "Message" => "Code: ".$code,
            "PhoneNumber" => "+84".$phone
        );

        $result = $sns->publish($args);
        return $result;
    }

    /**
     * get notification types
     *
     * @return array
     */
    public static function notificationTypes() {
        return [
            "orderCreate" => 'App\Notifications\OrderCreate',
        ];
    }

    /**
     * get user by id
     *
     * @return array
     */
    public static function getUserById($id) {
        $user = User::find($id);
        return $user ?: new User;
    }

    /**
     * diff date time with now
     *
     * @param datetime $time
     * @return void
     */
    public static function diffTimeWithNow($time)
    {
        $now = \Carbon\Carbon::now();
        $time_diff = \Carbon\Carbon::parse($time);
        $diffYears = $time_diff->diffInYears($now);
        if($diffYears > 0) {
            return $diffYears." ". __("year ago");
        }
        $diffMonths = $time_diff->diffInMonths($now);
        if($diffMonths > 0) {
            return $diffMonths." ". __("month ago");
        }
        $diffDays = $time_diff->diffInDays($now);
        if($diffDays > 0) {
            return $diffDays." ". __("day ago");
        }
        $diffHours = $time_diff->diffInHours($now);
        if($diffHours > 0) {
            return $diffHours." ". __("hour ago");
        }
        $diffMinutes = $time_diff->diffInMinutes($now);
        if($diffMinutes > 0) {
            return $diffMinutes." ". __("minute ago");
        }
        $diffSeconds = $time_diff->diffInSeconds($now);
        if($diffSeconds > 0) {
            return $diffSeconds." ". __("second ago");
        }
    }

    /**
     * show badge html by status
     *
     * @return void
     */
    public static function get_status_badge_order($status)
    {
        $status_text = '<small class="badge badge-secondary">'.__("Order New").'</small>';
        switch ($status){
            case \OrderStatus::AddNew: $status_text = '<small class="badge badge-secondary">'.__("Order New").'</small>'; break;
            case \OrderStatus::Confirmed: $status_text = '<small class="badge badge-info">'.__("Order Confirmed").'</small>'; break;
            case \OrderStatus::Shipping: $status_text = '<small class="badge badge-warning">'.__("Order Shipping").'</small>'; break;
            case \OrderStatus::Done: $status_text = '<small class="badge badge-success">'.__("Order Done").'</small>'; break;
            case \OrderStatus::Cancel: $status_text = '<small class="badge badge-danger">'.__("Order Cancel").'</small>';
        }
        return $status_text;
    }

    /**
     * show badge text by status id
     *
     * @return void
     */
    public static function get_status_text_order($status)
    {
        $status_text = __("Order New");
        switch ($status){
            case \OrderStatus::AddNew: $status_text = __("Order New"); break;
            case \OrderStatus::Confirmed: $status_text = __("Order Confirmed"); break;
            case \OrderStatus::Shipping: $status_text = __("Order Shipping"); break;
            case \OrderStatus::Done: $status_text = __("Order Done"); break;
            case \OrderStatus::Cancel: $status_text = __("Order Cancel");
        }
        return $status_text;
    }
}