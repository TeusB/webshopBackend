<?php

namespace models;

use Dotenv\Parser\Value;
use main\Error;
use main\Model;
use main\Validate;


class CategoryModel extends Model
{
    public string $tableName = "category";
    public int|string $identifierColumn = "idCategory";
    private object $error;
    public object $validate;
    protected array $validatedArray = array();

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("Category Model");
        $this->validate = new Validate();
    }

    public function validateDataGet(array $post, array $useArray): void
    {
        $validateArray = array(
            'idCategory' => array(
                'required' => true,
            ),
            'name' => array(
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
            'idCategory' => array(
                'required' => true,
            ),
            'name' => array(
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
}
