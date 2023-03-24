<?php

namespace models;

use main\Error;
use main\Model;
use main\Validate;

class shoppingCartItemModel extends Model
{
    public string $tableName = "shoppingcartItem";
    public int|string $identifierColumn = "idProduct";
    private object $error;
    public object $validate;
    protected array $validatedArray = array();

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("shoppingCartItemModel Model");
        $this->validate = new Validate();
    }

    public function validateDataGet(array $post, array $useArray): void
    {
        $validateArray = array(
            'idProduct' => array(
                    'required' => true,
            ),
            'idShoppingCart' => array(
                'required' => true,
            ),
            'amount' => array(
                'required' => true,
            ),
        );
        $useArray = array_flip($useArray);

        $validationArray = $this->validate->getValidationArray($validateArray, $useArray);
        $this->validate->checkValidation($post, $validationArray);

        if ($this->validate->checkErrorsValidate()) {
            $this->validatedArray = $this->validate->getValidatedArray($post, $validationArray);
        } else {
            $this->error->log->error($this->validate->returnErrorValidate());
            $this->error->maakError("something went wrong with the validation");
        }
    }

    public function validateDataInput(array $post, array $useArray): void
    {
        $validateArray = array(
            'idProduct' => array(
                'required' => true,
            ),
            'idShoppingCart' => array(
                'required' => true,
            ),
            'amount' => array(
                'onlyDigit' => true,
            ),
        );
        $useArray = array_flip($useArray);

        $validationArray = $this->validate->getValidationArray($validateArray, $useArray);
        $this->validate->checkValidation($post, $validationArray);

        if ($this->validate->checkErrorsValidate()) {
            $this->validatedArray = $this->validate->getValidatedArray($post, $validationArray);
        } else {
            $this->error->log->error($this->validate->returnErrorValidate());
            $this->error->maakError("something went wrong with the validation");
        }
    }
}