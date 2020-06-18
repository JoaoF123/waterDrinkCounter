<?php

namespace Source\Controllers;

use Source\Models\UserDrinkModel;
use Source\Models\UserModel;
use Source\Services\ConnectionCreator;

class DrinkCounter extends BaseController {

    private $connection;

    public function __construct()
    {
        // Create DB Connection
        $this->connection = ConnectionCreator::create();
    }

    public function add($data)
    {
        // Verify user authenticated
        $this->authenticatedUser();

        // Get user id sent by url
        $userId = $data['id'];

        // Get params sent by json body
        $params = $this->getParams(["drink_ml"]);

        // Load user model
        $userModel = new UserModel($this->connection);

        // Get user by id
        $user = $userModel->getById($userId);

        // Check if user exists
        if ($user) {

            // Update entity data
            $user->setDrinkCounter($user->getDrinkCounter() + $params['drink_ml']);

            // Start transaction
            $this->connection->beginTransaction();

            // Check if user was update
            if ($userModel->update($user)) {

                // Load user drink model
                $userDrinkSaved = (new UserDrinkModel($this->connection))
                    ->save(
                        $user->getId(),
                        $params['drink_ml']
                    );

                // Verify user drink was save
                if ($userDrinkSaved) {
                    
                    // Commit transaction
                    $this->connection->commit();

                    // Respond request with Ok status
                    $this->respond(200, "User drink saved successfully");
                }
            }

            // Rollback transaction
            $this->connection->rollBack();

            // Respond request with Internal Server Error status
            $this->respond(500);
        }

        // Return Not Acceptable code
        $this->respond(406, "User not found");
    }

    public function ranking()
    {
        // Get ranking data
        $ranking = (new UserDrinkModel($this->connection))->getRanking();

        // Respond request with Ok status
        $this->respond(200, [ "ranking" => $ranking ]);
    }
}