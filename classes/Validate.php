<?php

namespace main;

class Validate
{
    private $error;
    private $validateErrors = array();

    public function __construct()
    {
        $this->error = new Error("Validate Class");
    }

    public function getValidationArray(array $validateArray, $useArray): array
    {
        $validationArray = array();
        foreach ($useArray as $key => $value) {
            if (array_key_exists($key, $validateArray)) {
                $validationArray += [$key => $validateArray[$key]];
            } else {
                $this->error->maakError("kan $key niet vinden in validate");
            }
        }
        return $validationArray;
    }

    public function getValidatedArray(array $source, array $validationArray): array
    {
        $validatedArray = [];
        foreach ($validationArray as $key => $value) {
            if (array_key_exists($key, $source)) {
                $validatedArray += [$key => $source[$key]];
            } else {
                $this->error->maakError("kon $key niet vinden in request");
            }
        }
        return $validatedArray;
    }

    //validator for every class
    public function checkValidation(array $source, array $items): void
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                if (!empty($source[$item])) {
                    $value = $source[$item];
                } else {
                    $value = null;
                }
                $item = htmlentities($item, ENT_QUOTES, 'UTF-8');
                switch ($rule) {
                    case 'required':
                        if (empty($value)) {
                            $this->pushError("{$item} can not be empty");
                        }
                        break;

                    case 'requiredWizzard':
                        if (empty($value)) {
                            if (!$value === 0) {
                                $this->pushError("{$item} can not be empty");
                            }
                        }
                        break;

                    case 'minNumber':
                        if ($value < $rule_value) {
                            $this->pushError("fill in a higher number than {$rule_value}");
                        }
                        break;

                    case 'price':
                        if (!preg_match('/^[0-9]+(\.[0-9]{2})?$/', $value)) {
                            $this->pushError("{$item} has to be in the price format");
                        }
                        break;

                    case 'min':
                        if (strlen($value) < $rule_value) {
                            $this->pushError("{$item} needs to atleast have {$rule_value} characters");
                        }
                        break;

                    case 'max':
                        if (strlen($value) > $rule_value) {
                            $this->pushError("{$item} can not have more than {$rule_value} characters");
                        }
                        break;

                    case 'matches':
                        if ($value != $source[$rule_value]) {
                            $this->pushError("{$item} doesn't match given input");
                        }
                        break;

                    case 'uppercase':
                        if (!preg_match('/[A-Z]/', $value)) {
                            $this->pushError("{$item} needs to contain atleast one captital letter");
                        }
                        break;

                    case 'lowercase':
                        if (!preg_match('/[a-z]/', $value)) {
                            $this->pushError("{$item} needs to contain atleast one lower caps letter");
                        }
                        break;

                    case 'symbol':
                        if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_!+¬-]/', $value)) {
                            $this->pushError("{$item} needs to contain a special character");
                        }
                        break;

                    case 'digit':
                        if (!preg_match('~[0-9]+~', $value)) {
                            $this->pushError("{$item} needs to contain a number");
                        }
                        break;

                    case 'onlyDigit':
                        if (!is_int($value)) {
                            if (intval($value) === 0) {
                                $this->pushError("{$item} can only contain numbers");
                            }
                        }
                        break;

                    case 'whiteSpace':
                        if (preg_match('/\s/', $value)) {
                            $this->pushError("{$item} can not contain any spaces");
                        }
                        break;

                    case 'email':
                        $sanitizedEmail = filter_var($value, FILTER_SANITIZE_EMAIL);
                        if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
                            $this->pushError("{$value} is not a valid email adress");
                        }
                        break;

                    default:
                        $this->error->maakError("{$rule} is not a known validation rule");
                        break;
                }
            }
        }
    }

    //store validate error
    public function pushError($message): void
    {
        array_push($this->validateErrors, $message);
    }

    //check if any errors have been stored
    public function checkErrorsValidate(): bool
    {
        if (empty($this->validateErrors)) {
            return true;
        }
        return false;
    }

    //return stored errors
    public function returnErrorsValidate(): array
    {
        return $this->validateErrors;
    }


    public function returnErrorValidate(): int|string|float
    {
        return $this->validateErrors[0];
    }
}
