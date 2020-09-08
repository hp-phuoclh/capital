<?php
namespace App\Helpers;
use GuzzleHttp\Client;
use App\Notification_new;

class NotificationAPI
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
    const key = 'AAAAXME-Sj8:APA91bG1Eaa0Xu3krnlzQQLG5JSxRWA5C0Whkozjg97D9Fiw_4AO-KI5NQZ0B6q5zkOUDNoLzBnFfYKZ4HvGrmjTZWBGYI8hGpeo9WRH_x9e7nEpDeyIfiR3S1VeQ-KvDD8tW7S3Csc6';

    /**
     * send notify to app for user
     *
     * @param array $option
     * @return boot|mix
     */
    public static function send_notification_for_app($option, $registration_ids)
    {
        if (!$option['title'] || !$option['content'] || !$registration_ids || ($registration_ids && $registration_ids->count() == 0)) {
            return false;
        }

        $id = rand(-2147483640, 2147483647);
        $body = [
            "registration_ids" => $registration_ids,
            "collapse_key" => "type_a",
            "priority" => "high",
            "data" => [
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                // "type" => "OPEN_SCREEN",
                // "title" => $option['title'],
                // "hasImage" => "false",
                // "image" => "",
                // "content" => $option['content'],
                // "screenDestination" => "screen_notification",
                // "payload" => $notiId,
                // "notificationId" => $id,
                // "id" => $notiId
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
