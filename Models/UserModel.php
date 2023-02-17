<?php

namespace models;

use Dotenv\Parser\Value;
use main\Error;
use main\Model;
use main\Validate;


class UserModel extends Model
{
    public string $tableName = "user";
    public int|string $identifierColumn = "idUser";
    private object $error;
    public object $validate;
    protected array $validatedArray = array();

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("User Model");
        $this->validate = new Validate();
    }

    public function validateDataGet(array $post, array $useArray): void
    {
        $validateArray = array(
            'idUser' => array(
                'required' => true,
            ),
            'firstName' => array(
                'required' => true,
            ),
            'lastName' => array(
                'required' => true,
            ),
            'email' => array(
                'required' => true,
                'email' => true,
            ),
            'phone' => array(
                'required' => true,
            ),
            'level' => array(
                'required' => true,
            ),
            'postalCode' => array(
                'required' => true,
            ),
            'adress' => array(
                'required' => true,
            ),
            'houseNumber' => array(
                'required' => true,
            ),
            'houseNumberExtra' => array(
                'required' => true,
            ),
            'password' => array(
                'required' => true,
            ),
            'confirmPassword' => array(
                'required' => true,
            ),
        );
        $useArray = array_flip($useArray);

        $validationArray = $this->validate->getValidationArray($validateArray, $useArray);
        $this->validate->checkValidation($post, $validationArray);

        if ($this->validate->checkErrorsValidate()) {
            $this->validatedArray = $this->validate->getValidatedArray($post, $validationArray);
        } else {
            $this->error->maakError("validation");
        }
    }

    public function validateDataInput(array $post, array $useArray): void
    {
        $validateArray = array(
            'idUser' => array(
                'required' => true,
                'onlyDigit' => true,
            ),
            'firstName' => array(
                'required' => true,
            ),
            'lastName' => array(
                'required' => true,
            ),
            'email' => array(
                'required' => true,
                'email' => true,
            ),
            'phone' => array(
                'required' => true,
            ),
            'level' => array(
                'required' => true,
                'onlyDigit' => true,
            ),
            'postalCode' => array(
                'required' => true,
            ),
            'adress' => array(
                'required' => true,
            ),
            'houseNumber' => array(
                'required' => true,
            ),
            'houseNumberExtra' => array(
                'required' => true,
            ),
            'password' => array(
                'required' => true,
                'max' => 254,
                'min' => 6,
                'uppercase' => true,
                'lowercase' => true,
                'symbol' => true,
                'digit' => true,
            ),
            'confirmPassword' => array(
                'required' => true,
                'matches' => "password",
            ),
        );
        $useArray = array_flip($useArray);

        $validationArray = $this->validate->getValidationArray($validateArray, $useArray);
        $this->validate->checkValidation($post, $validationArray);

        if ($this->validate->checkErrorsValidate()) {
            $this->validatedArray = $this->validate->getValidatedArray($post, $validationArray);
        } else {
            $this->error->maakError("validation");
        }
    }
}
