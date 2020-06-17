<?php

namespace Source\Controllers;

class BaseController {

    protected function getParams(array $requiredFields)
    {
        // Get params sended by json body
        $params = json_decode(file_get_contents("php://input"), true);

        // Handle params
        $params = (isset($params[0])) ? $params[0] : $params;

        // Checking required fields are in params
        foreach ($requiredFields as $field) {
            
            // Check if required field exists in params
            if (!array_key_exists($field, $params)) {

                // Return Bad Request status code
                $this->respond(400, "Invalid parameters");
            }
        }

        return $params;
    }

    protected function respond(int $statusCode, $message)
    {
        // Return status code
        http_response_code($statusCode);

        // Return JSON message
        echo json_encode([ "response" => $message ]);

        // Stop execution
        exit();
    }
}