<?php

namespace webshop;

class Validate extends Model
{
    private $error;
    private $validateErrors = array();

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("Validate Class");
    }

    //validator for every class
    public function check($source, array $items)
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = $source[$item];
                $item = htmlentities($item, ENT_QUOTES, 'UTF-8');
                switch ($rule) {

                    case 'required':
                        if (empty($value)) {
                            $this->pushError("{$item} kan niet leeg zijn");
                        }
                        break;

                    case 'requiredWizzard':
                        if (empty($value)) {
                            if (!$value === 0) {
                                $this->pushError("{$item} kan niet leeg zijn");
                            }
                        }
                        break;

                    case 'price':
                        if (!preg_match('/^[0-9]+(\.[0-9]{2})?$/', $value)) {
                            $this->pushError("{$item} moet prijs formaat hebben");
                        }
                        break;

                    case 'min':
                        if (strlen($value) < $rule_value) {
                            $this->pushError("{$item} moet te minstens {$rule_value} characters hebben");
                        }
                        break;

                    case 'max':
                        if (strlen($value) > $rule_value) {
                            $this->pushError("{$item} can niet meer dan {$rule_value} characters hebben");
                        }
                        break;

                    case 'matches':
                        if ($value != $rule_value) {
                            $this->pushError("{$item} matched niet met het wachtwoord");
                        }
                        break;

                    case 'uppercase':
                        if (!preg_match('/[A-Z]/', $value)) {
                            $this->pushError("{$item} moet te minste een hoofd letter bevatten");
                        }
                        break;

                    case 'lowercase':
                        if (!preg_match('/[a-z]/', $value)) {
                            $this->pushError("{$item} moet te minste een kleine letter bevatten");
                        }
                        break;

                    case 'symbol':
                        if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_!+¬-]/', $value)) {
                            $this->pushError("{$item} moet te minste een speciaal teken bevatten");
                        }
                        break;

                    case 'digit':
                        if (!preg_match('~[0-9]+~', $value)) {
                            $this->pushError("{$item} moet te minste een cijfer hebben");
                        }
                        break;

                    case 'onlyDigit':
                        if (!is_int($value)) {
                            if (intval($value) === 0) {
                                $this->pushError("{$item} kan alleen cijfers bevatten");
                            }
                        }
                        break;

                    case 'whiteSpace':
                        if (preg_match('/\s/', $value)) {
                            $this->pushError("{$item} can geen spaties bevatten");
                        }
                        break;

                    case 'email':
                        $sanitizedEmail = filter_var($value, FILTER_SANITIZE_EMAIL);
                        if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
                            $this->pushError("{$value} is geen geldig email adres");
                        }
                        break;

                    default:
                        $this->error->maakError("{$rule} is geen bekende valideer regel");
                        break;
                }
            }
        }
    }

    //store validate error
    protected function pushError($message)
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
//    public function returnErrorsValidate()
//    {
//        return $this->validateErrors;
//    }


    public function returnErrorValidate()
    {
        return $this->validateErrors[0];
    }
}
