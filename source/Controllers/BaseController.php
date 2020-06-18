<?php

namespace Source\Controllers;

use Source\Models\UserModel;
use Source\Services\ConnectionCreator;
use Source\Services\Token;

class BaseController {

    protected function getParams(array $requiredFields, bool $acceptNull = false)
    {
        // Get params sended by json body
        $params = json_decode(file_get_contents("php://input"), true);

        // Handle params
        $params = (isset($params[0])) ? $params[0] : $params;

        // Check if params is empty
        if (empty($params) && !$acceptNull) {

            // Return Bad Request status code
            $this->respond(400, "Invalid parameters");
        }

        // Checking required fields are in params
        foreach ($requiredFields as $field) {
            
            // Check if required field exists in params
            if (!array_key_exists($field, $params)) {

                // Return Bad Request status code
                $this->respond(400, "The parameter $field is required.");
            }
        }

        return $params;
    }

    protected function respond(int $statusCode, $message = "")
    {
        // Return status code
        http_response_code($statusCode);

        // Return JSON message
        echo json_encode([ "response" => $message ]);

        // Stop execution
        exit();
    }

    protected function authenticatedUser() : void
    {
        if (!Token::check())
            $this->respond(401, "Action not permitted.");

        // Get user id at token
        $userId = Token::getPayload()['uid'];
        $user = (new UserModel(ConnectionCreator::create()))->getById($userId);

        if (!$user)
            $this->respond(401, "Action not permitted.");
    }
}