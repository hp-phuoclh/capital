<?php
namespace App\Helpers;
use GuzzleHttp\Client;
use App\Models\RegistrationId;

class NotificationAdminAPI
{

    /**
     * end point uri
     *
     * @var string
     */
    const url = 'https://fcm.googleapis.com/fcm/send';

    /**
     * end point uri
     *
     * @var string
     */
    const key = 'AAAAkssAI_U:APA91bHjEU0G17eHyXd7Z4Kdp5NDaTyavWIWRd36c1On2hvsj6p8X2RJBCHwzpCxW6KfGQxF8BD3vd-NZR1VBXP2F-0pAiI9UcmjSkaHyWzI46BRNbJljkg67lD3L1k8aMVSe8NAwT5f';

    /**
     * send notify to app for user
     *
     * @param array $option
     * @return boot|mix
     */
    public static function send_notification_for_app($option, $registration_ids, $order_id)
    {
        if(!$registration_ids) {
            $registration_ids = array_values(RegistrationId::where('type', 'admin')->get()->pluck('id')->toArray());
        }
        if (!$option['title'] || !$option['content'] || !$registration_ids) {
            return false;
        }

        $id = rand(-2147483640, 2147483647);
        $body = [
            "registration_ids" => $registration_ids,
            "collapse_key" => "type_a",
            "priority" => "high",
            "data" => [
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "type" => "OPEN_URL",
                // "title" => $option['title'],
                // "hasImage" => "false",
                // "image" => "",
                // "content" => $option['content'],
                // "screenDestination" => "screen_notification",
                "payload" => $option['url'], // http.....
                // "notificationId" => $id,
                "order_id" => $order_id
            ],
            "notification" => [
                "body" => $option['content'],
                "title"=> $option['title']
            ]
        ];
        $client = new Client([
            'headers' => ['Authorization' => 'key='.self::key],
            'defaults' => [ 'exceptions' => false ]
        ]);

        $response = $client->request('POST', self::url, [
            'json'    => $body
        ]);

        return $response->getStatusCode();
    }
}
