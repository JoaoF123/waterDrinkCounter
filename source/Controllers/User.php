<?php

namespace Source\Controllers;

use Source\Services\Token;
use Source\Models\UserModel;
use Source\Entities\UserEntity;
use Source\Models\UserDrinkModel;
use Source\Services\ConnectionCreator;

class User extends BaseController
{
    private $connection;

    public function __construct()
    {
        // Create DB Connection
        $this->connection = ConnectionCreator::create();
    }

    public function create()
    {
        // Get params sent by json body
        $params = $this->getParams(["email", "name", "password"]);

        // Create a user entity
        $userEntity = new UserEntity($params['name'], $params['email'], $params['password']);

        // Validate data
        $this->validateData($userEntity);

        // Insert user in DB and return request
        ((new UserModel($this->connection))->insert($userEntity)) 
            ? $this->respond(200, "User created successfully")
            : $this->respond(500);
    }

    public function getAll()
    {
        // Verify user authenticated
        $this->authenticatedUser();

        // Get page and offset 
        $page = (isset($_GET['page'])) ? $_GET['page'] : null;
        $offset = (isset($_GET['per_page'])) ? $_GET['per_page'] : 10;

        // Load user model
        $dbUser = new UserModel($this->connection);

        // Get all users
        $users = $dbUser->getAll($page, $offset);
        $userList = [];

        // Handle the data
        foreach ($users as $user) {
            $userList[] = $user->toArray();
        }

        // Get users total count
        $usersTotalCount = $dbUser->getTotalCount();

        // Retun request
        ($userList) ? $this->respond(200, [ "totalCount" => $usersTotalCount , "data" => $userList ]) : $this->respond(204);
    }

    public function getById($data)
    {
        // Verify user authenticated
        $this->authenticatedUser();

        // Get params sent by url
        $userId = (int)$data['id'];

        // Load user model
        $userModel = new UserModel($this->connection);

        // Get user by id
        $user = $userModel->getById($userId);

        // Returrn request
        ($user) ? $this->respond(200, [ "data" => $user->toArray() ]) : $this->respond(204);
    }

    public function update($data)
    {
        // Verify user authenticated
        $this->authenticatedUser();

        // Get params sent by json body
        $params = $this->getParams(["name", "email", "password"]);

        // Get user id sent by url
        $userId = (int)$data['id'];

        // Load user model
        $userModel = new UserModel($this->connection);

        // Get user by id
        $user = $userModel->getById($userId);

        // Check if user exists
        if ($user) {

            // Check if updated user is own user
            if ($this->isOwnUser($user)) {

                // Check if new email is unique
                if (!$this->isUniqueEmail($params['email'], $user->getId())) {
                    $this->respond(400, "Email already registered.");
                }

                // Update entity data
                $user->setName($params['name']);
                $user->setEmail($params['email']);
                $user->setPassword($params['password']);

                // Update user in DB and return request
                ($userModel->update($user))
                    ? $this->respond(200, "User updated successfully")
                    : $this->respond(500);
            }

            // Return not unauthorized status
            $this->respond(401, "You don’t have permission to update this user.");

        } else {

            // Return Not Acceptable code
            $this->respond(406, "User not found");
        }
    }

    public function delete($data)
    {
        // Verify user authenticated
        $this->authenticatedUser();

        // Get user id sent by url
        $userId = (int)$data['id'];

        // Load user model
        $userModel = new UserModel($this->connection);

        // Get user by id
        $user = $userModel->getById($userId);

        // Check if user exists
        if ($user) {

            // Check if updated user is own user
            if ($this->isOwnUser($user)) {
            
                // Start a transaction
                $this->connection->beginTransaction();

                // Delete user drink logs
                $deleteUserDrinkLogs = (new UserDrinkModel($this->connection))->deleteByUserId($user->getId());

                // Verify if user drink was deleted
                if ($deleteUserDrinkLogs) {

                    // Delete user
                    $userDeleted = $userModel->delete($user->getId());
    
                    // Check user deleted
                    if ($userDeleted) {
    
                        // Commit transaction
                        $this->connection->commit();
    
                        // Respond request Ok status
                        $this->respond(200, "User deleted successfully");
                    }
                }

                // Rollback transaction
                $this->connection->rollBack();

                // Respond request with Internal Server Error status
                $this->respond(500);
            }

            // Return not unauthorized status
            $this->respond(401, "You don’t have permission to delete this user.");

        } else {

            // Return Not Acceptable code
            $this->respond(406, "User not found");
        }
    }

    public function history($data)
    {
        // Get user id sent by url
        $userId = (int)$data['id'];

        // Get history of user
        $userHistory = (new UserDrinkModel($this->connection))->getUserHistory($userId);

        // Respond request
        ($userHistory) ? $this->respond(200, $userHistory) : $this->respond(204);
    }

    private function validateData(UserEntity $userEntity)
    {
        // Validate required data
        $userValidate = $userEntity->validate();

        // Validate user entity
        if (!empty($userValidate)) {

            // Return bad request status
            $this->respond(400, $userValidate);
        }
        
        // Load user model
        $userModel = new UserModel($this->connection);

        // Verify unique user
        if ($userModel->getByEmail($userEntity->getEmail())) {

            // Return bad request status
            $this->respond(400, "User already registered.");
        }
    }

    private function isOwnUser(UserEntity $userEntity)
    {
        // Get payload data from token
        $payload = Token::getPayload();

        // Compare token id with url id
        return ($payload['uid'] == $userEntity->getId()) ? true : false;
    }

    private function isUniqueEmail(string $email, int $idUser)
    {
        // Search for a user with this email
        $user = (new userModel($this->connection))->getByEmailWithoutUser($email, $idUser);

        return (!empty($user)) ? false : true;
    }
}