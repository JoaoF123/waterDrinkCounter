<?php

namespace Source\Controllers;

use Source\Services\Token;
use Source\Models\UserModel;
use Source\Services\ConnectionCreator;

class Login extends BaseController {

    private $connection;

    public function __construct()
    {
        // Create DB Connection
        $this->connection = ConnectionCreator::create();
    }

    public function execute()
    {
        // Get params sended by json body
        $params = $this->getParams(["email", "password"]);

        // Load User Model
        $userModel = new UserModel($this->connection);

        // Get use by email and password
        $user = $userModel->getByEmailPassword($params['email'], $params['password']);

        // verify user exists
        if ($user) {

            // Load token service
            $tokenService = new Token();

            // Generate token
            $token = $tokenService->generate($user->getId());

            // Return sucess status with token
            $this->respond(200, [ "token" => $token ]);

        } else {

            // Return Unauthorized status
            $this->respond(401, "User does not exist or invalid password.");
        }
    }
}