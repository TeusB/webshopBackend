<?php

namespace models;

use Dotenv\Parser\Value;
use main\Error;
use main\Model;
use main\Validate;

class shoppingCartModel extends Model
{
    public string $tableName = "shoppingcart";
    public int|string $identifierColumn = "idShoppingCart";
    private object $error;
    public object $validate;
    protected array $validatedArray = array();


    public function validateDataGet(array $post, array $useArray): void
    {
        $validateArray = array(
            'idShoppingCart' => array(
                'required' => true,
                'onlyDigit' => true,
            ),
            'idUser' => array(
                'required' => true,
                'onlyDigit' => true,
            )
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
            'idShoppingCart' => array(
                'required' => true,
                'onlyDigit' => true,
            ),
            'idUser' => array(
                'required' => true,
                'onlyDigit' => true,
            )
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
