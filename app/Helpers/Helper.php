<?php 
namespace App\Helpers;
use App\User;
class Helper {

    public static function sendNotification($title, $details)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAAmb9zww0:APA91bF0IqHAGvXzYsOXfvkgqs80f_OuJqpVKyWc_Tz35qhNB5O5HIM7Y5dJISuT96o6shXH-LSMltOlwuYywlq5OYsLs4C5g4OQ7cNJe-dd-th-XIG8IPxv6HC7WWIrDebmwq_a5clJ';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $details,
                "content_available" => true,
                "priority" => "high",
                "show_in_foreground" => true,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }

    public static function sendNotificationContribution($title, $details,$member_id)
    {
        $firebaseToken = User::whereNotNull('device_token')
        ->where('member_id',$member_id)
        ->pluck('device_token')
        ->all();

        $SERVER_API_KEY = 'AAAAmb9zww0:APA91bF0IqHAGvXzYsOXfvkgqs80f_OuJqpVKyWc_Tz35qhNB5O5HIM7Y5dJISuT96o6shXH-LSMltOlwuYywlq5OYsLs4C5g4OQ7cNJe-dd-th-XIG8IPxv6HC7WWIrDebmwq_a5clJ';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $details,
                "content_available" => true,
                "priority" => "high",
                "show_in_foreground" => true,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }

}