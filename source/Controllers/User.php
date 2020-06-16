<?php

namespace Source\Controllers;

class User
{
    public function create()
    {
        echo "Endpoint to create user.";
    }

    public function getAll()
    {
        echo "Endpoint to load all users.";    
    }

    public function getById($params)
    {
        var_dump($params);
        echo "Endpoint to load a specific user.";
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