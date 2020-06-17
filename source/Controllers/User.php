<?php

namespace Source\Controllers;

use Source\Models\UserModel;
use Source\Services\Token;

class User
{
    public function create()
    {
        echo "Endpoint to create user.";
    }

    public function getAll()
    {
        if (!Token::check()) {
            http_response_code(401);
            echo json_encode([ "response" => "Action not permitted." ]);
            exit();
        }
        
        // Load user model
        $dbUser = new UserModel();

        // Get all users
        $users = $dbUser->getAll();
        $userList = [];

        // Handle the data
        foreach ($users as $user) {
            $userList[] = $user->toArray();
        }

        if ($userList) {

            // Set return status code
            http_response_code(200);

            // Return data
            echo json_encode([ "response" => $userList ]);
        
        } else {

            // Set return status code
            http_response_code(204);
        }

    }

    public function getById($params)
    {
        if (!Token::check()) {
            http_response_code(401);
            echo json_encode([ "response" => "Action not permitted." ]);
            exit();
        }

        // Get user id passed by url
        $userId = $params['id'];

        // Load user model
        $userModel = new UserModel();

        // Get user by id
        $user = $userModel->getById($userId);

        // Handle response array
        $responseUser = [];
        $responseCode = 500;

        if ($user) {

            // Setting HTTP status code 200
            $responseCode = 200;

            // Print a JSON message return
            $responseUser = $user->toArray();
        
        } else {

            // Setting HTTP status code 200
            $responseCode = 204;
        }

        http_response_code($responseCode);
        echo json_encode([ "response" => $responseUser ]);
    }

    public function update($params)
    {
        var_dump($params);
        echo "Endpoint to update own user";
    }

    public function delete($params)
    {
        var_dump($params);
        echo "Endpoint to remove own user";
    }

    public function drink($params)
    {
        var_dump($params);
        echo "Endpoint to add how many mls of water user drinked";
    }
}