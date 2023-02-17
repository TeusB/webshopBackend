<?php

namespace webshop;

class WaardeBon extends AbstractView
{
    private $w_model;
    public $validate;
    private $error;

    public function __construct()
    {
        $this->w_model = new WaardeBon_Model();
        $this->validate = new Validate();
        $this->error = new Error("Waardebon");
    }

    public function WaardeBonErrorLog(string $messages): void
    {
        $this->error->logError($messages);
    }

    public function validateInsertWaardebon(array $POST): bool
    {
        $this->validate->check($POST, array(
            'idWaardeBon' => array(
                'required' => true,
                'max' => 5,
                'min' => 1,
                'onlyDigit' => true,
            ),
            'name' => array(
                'required' => true,
                'max' => 50,
                'min' => 2,
            ),
            'percentageOff' => array(
                'required' => true,
                'max' => 7,
                'min' => 1,
                'onlyDigit' => true,
            ),
            'minOrderValue' => array(
                'required' => true,
                'max' => 7,
                'min' => 1,
                'price' => true,
            ),
            'code' => array(
                'required' => true,
                'max' => 20,
                'min' => 1,
            ),
        ));
        return $this->validate->checkErrorsValidate();
    }



    public function validateUpdateWaardeBon(array $PUT): bool
    {
        $this->validate->check($PUT, array(
            'idWaardeBon' => array(
                'required' => true,
                'max' => 5,
                'min' => 1,
                'onlyDigit' => true,
            ),
            'name' => array(
                'required' => true,
                'max' => 50,
                'min' => 2,
            ),
            'percentageOff' => array(
                'required' => true,
                'max' => 7,
                'min' => 1,
                'onlyDigit' => true,
            ),
            'minOrderValue' => array(
                'required' => true,
                'max' => 7,
                'min' => 1,
                'price' => true,
            ),
            'expires' => array(
                'required' => true,
                'max' => 20,
                'min' => 1,
            ),
            'code' => array(
                'required' => true,
                'max' => 20,
                'min' => 1,
            ),
        ));
        return $this->validate->checkErrorsValidate();
    }


    public function validateDeleteWaardeBon(array $DELETE): bool
    {
        $this->validate->check($DELETE, array(
            'idWaardeBon' => array(
                'required' => true,
                'max' => 5,
                'min' => 1,
                'onlyDigit' => true,
            )
        ));
        return $this->validate->checkErrorsValidate();
    }


    public function validateShowWaardeBon(array $GET): bool
    {
        $this->validate->check($GET, array(
            'idWaardeBon' => array(
                'required' => true,
                'max' => 5,
                'min' => 1,
                'onlyDigit' => true,
            )
        ));
        return $this->validate->checkErrorsValidate();
    }


    public function insertWaardeBon(string $name, string $description, int $idCategory, float $price, int $stock)
    {
        return $this->w_model->insertWaardeBon($name, $description, $idCategory, $price, $stock);
    }

    public function updateWaardeBon(int $idWaardeBon, string $name, string $percentageOff, int $minOrderValue, string $expires, string $code)
    {
        if ($this->w_model->updateWaardeBon($name, $percentageOff, $idWaardeBon, $minOrderValue, $expires, $code)) {
            return true;
        }
        return false;
    }


    public function checkWaardeBonExist(int $idWaardeBon): bool
    {
        return $this->w_model->checkWaardeBonExist($idWaardeBon);
    }


    public function SoftDeleteWaardeBon(int $idWaardeBon)
    {
        if ($this->w_model->SoftDeleteWaardeBon($idWaardeBon)) {
            return true;
        }
        return false;
    }

    public function loadWaardeBonPage(array $idWaardeBon)
    {
        $idWaardeBon = intval($idWaardeBon["idWaardeBon"]);
        $this->showView('waardeBon', ['waardeBon' => $this->w_model->getWaardeBonByID($idWaardeBon)]);
    }

    public function loadWaardeBonnenPage()
    {
        $this->showView('waardeBonnen', ['waardeBonnen' => $this->w_model->listWaardeBonnen()]);
    }
}
