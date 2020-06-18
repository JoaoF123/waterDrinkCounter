# WaterDrinkCounterAPI

WaterDrinkAPI is a study project to know more about RESTful APIs with PHP.

if you want test, i uploaded in Heroku and you can access using base url: https://waterdrinkcounter.herokuapp.com + endpoint.

Ex.: GET https://waterdrinkcounter.herokuapp.com/users/ (To create a new user)


## Endpoints

------------------------------------------------------------------------------------------------------------

### Create user
    Create a new user

#### POST 	/users/

Params

	No parameters

Request body
	A JSON object with the user name (required), email (required) and password (required)
	{
		"name": "User Name",
		"email": "user@email.com",
		"password": "userpassword"
    }

Responses

	200 OK
		JSON object with a response message
		{
  			"response": "User created successfully"
        }

    500 Internal Server Error
	    No response message


------------------------------------------------------------------------------------------------------------

### Login
    Log in to the API

#### POST 	/login

Params

	No parameters

Request body

	A JSON object with the user email (required) and password (required)
	{
		"email": "user@email.com",
		"password": "userpassword"
    }

Responses

	200 OK
		JSON object with a JWT token and user infos
        {
            "response": {
                "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTI1MTMxNzAsInVpZCI6MX0=.KaORRbvgZrxvtdjgvLIAdhnKH68qwXr\/qr8rzP0\/dt4=",
                "data": {
                "iduser": "1",
                "email": "user@email.com",
                "name": "User Name",
                "drink_counter": 0
                }
            }
        }

    401 Unauthorized
        JSON Object with a response message
        {
        "response": "User does not exist or invalid password."
        }


------------------------------------------------------------------------------------------------------------

### Get a user
Get infos from a specific user

#### GET 	/users/{id}

Params

	Id : Integer | Required | User id


Headers
	Authorization: “Beare “ + JWT token generated by login endpoint
	
	JWT Token example:
        eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTI1MTQ4MjksInVpZCI6MX0=.BS39OvQnIK2EDwNXGJvX0QJW9854DPl1sv2abVAw2Ng=

Responses

    401 Unauthorized (When invalid token)
            JSON object with a response message
        {
        "response": "Action not permitted."
    }

	200 OK
		JSON object with user infos
	{
        "response": {
                "data": {
                "iduser": "1",
                "email": "user@email.com",
                "name": "User Name.",
                "drink_counter": 0
            }
        }
    }

	204 No Content
		No response message


------------------------------------------------------------------------------------------------------------


### Get all users
    Get infos from all users registered

#### GET 	/users/{?page=1&per_page=2}

Params

    page: Integer | Optional | Index of page do you want
    per_page: Integer | Optional | Quantity of registers per page do you want

Headers
	Authorization: “Beare “ + JWT token generated by login endpoint
	
	JWT Token example:
        eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTI1MTQ4MjksInVpZCI6MX0=.BS39OvQnIK2EDwNXGJvX0QJW9854DPl1sv2abVAw2Ng=

Responses

    401 Unauthorized (When invalid token)
		JSON object with a response message
	    {
 	        "response": "Action not permitted."
        }

	200 OK
		Total number of users and an array of JSON objects with user infos
        {
            "response": {
                "totalCount": 15,
                "data": [
                    {
                        "iduser": "1",
                        "email": "user@email.com",
                        "name": "User Name",
                        "drink_counter": 0
                    },
                    {
                        "iduser": "2",
                        "email": "user2@email.com",
                        "name": "User Name 2",
                        "drink_counter": 0
                    }
                ]
            }
        }

	204 No Content
		No response message


------------------------------------------------------------------------------------------------------------


### Update
    Update my own user

#### PUT	/users/{id}

Params

	Id : Integer | Required | User id

Request body

	A JSON object with the user email (optional), name (optional) and password (optional)
	{
		“name”: “User Name”,
		"email": "user@email.com",
		"password": "userpassword"
    }

Headers
	Authorization: “Beare “ + JWT token generated by login endpoint
	
	JWT Token example:
    eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTI1MTQ4MjksInVpZCI6MX0=.BS39OvQnIK2EDwNXGJvX0QJW9854DPl1sv2abVAw2Ng=

Responses

	401 Unauthorized (When invalid token)
		JSON object with a response message
        {
            "response": "Action not permitted."
        }

    200 OK
		JSON object with a response message
		{
  			"response": "User updated successfully"
        }

    400 Bad Request
		JSON object with a response message
        {
            "response": "Email already registered."
        }

    401 Unauthorized
		JSON object with a response message
        {
            "response": "You don’t have permission to update this user."
        }

	406 Not Acceptable
		JSON object with a response message
        {
            "response": "User not found"
        }

    500 Internal Server Error
        No response message

------------------------------------------------------------------------------------------------------------

### Delete
    Delete my own user

#### DELETE	/users/{id}

Params

	Id : Integer | Required | User id


Headers
	Authorization: “Beare “ + JWT token generated by login endpoint
	
	JWT Token example:
        eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTI1MTQ4MjksInVpZCI6MX0=.BS39OvQnIK2EDwNXGJvX0QJW9854DPl1sv2abVAw2Ng=

Responses

	401 Unauthorized (When invalid token)
		JSON object with a response message
        {
            "response": "Action not permitted."
        }

    200 OK
        JSON object with a response message
        {
            "response": "User updated successfully"
        }


    401 Unauthorized
		JSON object with a response message
        {
            "response": "You don’t have permission to delete this user."
        }

    500 Internal Server Error
        No response message

------------------------------------------------------------------------------------------------------------

### Drink
    Increment the water drink counter

#### POST	/users/{id}/drink

Params

	Id : Integer | Required | User id

Request body
	A JSON object with mls of water
    {
        "drink_ml": "100"
    }

Headers
	Authorization: “Beare “ + JWT token generated by login endpoint
	
	JWT Token example:
        eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE1OTI1MTQ4MjksInVpZCI6MX0=.BS39OvQnIK2EDwNXGJvX0QJW9854DPl1sv2abVAw2Ng=

Responses

	401 Unauthorized (When invalid token)
		JSON object with a response message
        {
            "response": "Action not permitted."
        }

	200 OK
		JSON object with user infos{
        "response": {
                "data": {
                    "iduser": "1",
                    "email": "felixl.joao@gmail.com",
                    "name": "João Félix Lopes Jr.",
                    "drink_counter": 500
                }
            }
        }

    500 Internal Server Error
	    No response message

------------------------------------------------------------------------------------------------------------

### Ranking
    Get a ranking of users who drank most water today

#### GET 	/ranking

Params

	No Params

Responses

	200 OK
		Array of JSON objects with user infos
        {
            "response": {
                "ranking": [
                    {
                        "name": "João Félix Lopes Jr.",
                        "drink_ml": "400"
                    },
                    {
                        "name": "Wesley Dantas",
                        "drink_ml": "150"
                    }
                ]
            }
        }


	204 No Content
		No response message

------------------------------------------------------------------------------------------------------------

### User history
    Get history of a specific user

#### GET 	/users/{id}/history

Params

	Id : Integer | Required | User id


Responses

	200 OK
		JSON object array with date and mls user drinked
        {
            "response": [
                {
                    "data": "18\/06\/2020",
                    "drink_ml": "100"
                },
                {
                    "data": "18\/06\/2020",
                    "drink_ml": "100"
                },
                {
                    "data": "18\/06\/2020",
                    "drink_ml": "100"
                },
                {
                    "data": "18\/06\/2020",
                    "drink_ml": "100"
                },
                {
                    "data": "18\/06\/2020",
                    "drink_ml": "100"
                }
            ]
        }


	204 No Content
		No response message
