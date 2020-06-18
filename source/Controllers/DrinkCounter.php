<?php

namespace Source\Controllers;

use Source\Models\UserModel;
use Source\Services\Validation;
use Source\Models\UserDrinkModel;
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

        // Validate params data
        $this->validateData($params);

        // Load user model
        $userModel = new UserModel($this->connection);

        // Get user by id
        $user = $userModel->getById($userId);

        // Check if user exists
        if ($user) {

            // Start transaction
            $this->connection->beginTransaction();

            // Load user drink model
            $userDrinkSaved = (new UserDrinkModel($this->connection))
                ->save(
                    $user->getId(),
                    $params['drink_ml']
                );

            // Verify user drink was save
            if ($userDrinkSaved) {
                
                // Update user entity to return
                $user->setDrinkCounter($user->getDrinkCounter() + $params['drink_ml']);

                // Commit transaction
                $this->connection->commit();

                // Respond request with Ok status
                $this->respond(200, [ "data" => $user->toArray() ]);
            }

            // Rollback transaction
            $this->connection->rollBack();

            // Respond request with Internal Server Error status
            $this->respond(500);
        }

        // Return Not Acceptable code
        $this->respond(406, "User not found");
    }

    private function validateData($params)
    {
        if (!Validation::requiredInt($params['drink_ml'])) {
            $this->respond(400, "Param 'drink_ml' must be a integer value.");
        }
    }

    public function ranking()
    {
        // Get ranking data
        $ranking = (new UserDrinkModel($this->connection))->getRanking();

        // Respond request with Ok status
        $this->respond(200, [ "ranking" => $ranking ]);
    }
}