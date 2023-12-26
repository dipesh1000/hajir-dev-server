<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FirebaseNotification extends Notification
{

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

   

    public function toFirebase($notifiable)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = $notifiable->device_token;
        // Replace with your FCM server key
        // $serverKey = 'AAAAVWtqYtY:APA91bGY_1xbTjOAWzjJgWYrFKTT5tGil6PSd43uvenm1_D39lYrJQ5Nvw66INpcW0nJ-duebfRKZQe7I-3CZ3DkPlWiY-0ISG-XGkBQT5doN5QfKnUrLg45O6CaP5mJFE-BnQ7v9yHh'; 

        $serverKey = 'AAAA_fobcNs:APA91bE7hDFXaVC-i9OTAG7CyTAhfx6Pnj7G45HRtA9mYLZCA7a6no_upstla3ElhSxQ62SnEi34Bacllt5cfWBDYHmrLiiHULf76as6d2P1Kr_yjvHch0EbqnXkhO2-csTvU54DYOp0';

        $data = [
            "to" => $FcmToken,
            "notification" => [
                "type" => $this->data['type'],
                'type_id' => $this->data['type_id'],
                "title" => $this->data['title'],
                "body" => $this->data['body'],
            ]
        ];

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }
}
