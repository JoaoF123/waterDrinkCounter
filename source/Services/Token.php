<?php

namespace Source\Services;

use DateInterval;
use DateTime;

class Token {

    private static $key = "waterdrinkcounter@9as89d5qw9";

    public function generate(Int $userId)
    {
        // Header Token
        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];

        // Payload - Content
        $payload = [
            "exp" => (new DateTime("now"))->add(new DateInterval('PT2H'))->getTimestamp(),
            "uid" => $userId
        ];

        // JSON
        $header = base64_encode(json_encode($header));
        $payload = base64_encode(json_encode($payload));

        // Sign
        $sign = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
        $sign = base64_encode($sign);

        // Token
        $token = $header . '.' . $payload . '.' . $sign;

        return $token;
    }

    public static function check()
    {
        // Get requisition header
        $httpHeader = apache_request_headers();

        // Check authorization isn´t null
        if (isset($httpHeader['Authorization']) && !empty($httpHeader['Authorization'])) {

            // Explode authorization string to get JWT token
            $bearer = explode(" ", $httpHeader['Authorization']);

            // Split JWT token
            $splitedToken = explode(".", $bearer['1']);

            $header = $splitedToken[0];
            $payload = $splitedToken[1];
            $sign = str_replace("\\/", "/", $splitedToken[2]);

            // Handle payload
            $payloadHandled = json_decode(base64_decode($payload), true);

            // Validate expiration date
            $currentDate = strtotime(date('Y-m-d H:i:s'));

            // Verify if token isn't expired
            if ($currentDate <= $payloadHandled['exp']) {

                // Validate sign
                $valid = hash_hmac('sha256', $header . "." . $payload, self::$key, true);
                $valid = base64_encode($valid);

                if ($sign == $valid) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function getPayload()
    {
        // Get requisition header
        $httpHeader = apache_request_headers();

        // Explode authorization string to get JWT token
        $bearer = explode(" ", $httpHeader['Authorization']);

        // Get data from payload
        $payload = explode(".", $bearer['1'])[1];

        // Return handled data
        return json_decode(base64_decode($payload), true);
    }
}