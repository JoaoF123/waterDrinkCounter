<?php

namespace Source\Controllers;

use Source\Services\Token;
use Source\Models\UserModel;

class Login {

    public function execute()
    {
        // Get params sended by json body
        $params = json_decode(file_get_contents("php://input"), true);

        // Handle params
        $params = (isset($params[0])) ? $params[0] : $params;

        if ($params && (isset($params['email']) && isset($params['password']))) {

            // Load User Model
            $userModel = new UserModel();

            // Get use by email and password
            $user = $userModel->getByEmailPassword($params['email'], $params['password']);

            // verify user exists
            if ($user) {

                // Load token service
                $tokenService = new Token();

                // Generate token
                $token = $tokenService->generate($user->getId());

                // Return sucess status with token
                http_response_code(200);
                echo json_encode([ "response" => [ "token" => $token ]]);

            } else {

                // Return Unauthorized status
                http_response_code(401);
            }

        } else {

            // Return Bad Request status
            http_response_code(400);
        }
    }
}