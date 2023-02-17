<?php

namespace webshop;


class User_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setPassword(int $idUser, string $pnew)
    {
        $values = [password_hash($pnew, PASSWORD_DEFAULT), $idUser];
        $this->update("UPDATE user SET password=? WHERE iduser=?", $values, "ss");
    }


    public function getId(string $email): int
    {
        if ($idUserArray = $this->select("SELECT idUser FROM user WHERE email=?", $email, "s")) {
            return $this->makeInt($this->returnSingleValue($idUserArray));
        }
        return false;
    }


    public function getEmail(int $idUser)
    {
        if ($emailArray = $this->select("SELECT idUser FROM user WHERE email=?", [$idUser], "i")) {
            return $this->returnSingleValue($emailArray);
        }
        return false;
    }

    public function getPassword(string $email): string
    {
        return $this->returnSingleValue($this->select("SELECT password FROM User WHERE email = ?", $email, "s"));
    }

    public function updateUser(string $firstName, string $lastName, string $email, int $idUser)
    {
        $values = [$firstName, $lastName, $email, $idUser];
        return $this->update('UPDATE user SET firstName=?, lastName =? WHERE idUser = ?', $values, "sssi");
    }

    public function listUsers()
    {
        return $this->selectEmpty(
            'SELECT idUser, firstName, lastName, email FROM user'
        );
    }

    public function getLevel(int $idUser)
    {
        if ($levelArray = $this->select("SELECT level FROM User WHERE idUser = ?", [$idUser], "i")) {
            return $this->makeInt($this->returnSingleValue($levelArray));
        }
        return false;
    }

    public function checkEmailExist(string $email): bool
    {
        return $this->selectBool("SELECT iduser FROM user WHERE email=?", $email, "s");
    }

    public function insertUser(string $email, string $password)
    {
        $values = [$email, $password];
        return $this->insertReturnID('INSERT INTO user (email, password) VALUES (?, ?)', $values, "ss");
    }

    public function dynamicInsert(array $data){ // bij checkout
        $allowedKeys = ['password','firstName','lastName','adress','postalCode','houseNumber','houseNumberExtra','email','phone'];

        foreach($data as $dataKey => $value){
            if (!in_array($dataKey,$allowedKeys)){
                unset($data[$dataKey]);
            }
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $keyString = '(';
        $questionMarkString = '(';
        $typeString = '';
        $valueArr = [];
        $i = 0;
        foreach ($data as $dataKey => $value){
            if ($i === 0){ // eerste key van array
                $keyString .= $dataKey;
                $questionMarkString .= '?';
            } else {
                $keyString .= ',' .$dataKey;
                $questionMarkString .= ',?';
            }
            if (gettype($value) === 'string'){
                $typeString .= 's';
            } elseif (gettype($value) === 'integer'){
                $typeString .= 'i';
            }
            $valueArr[] = $value;
            $i++;
        }
        $keyString .= ' )';
        $questionMarkString .= ')';
        return $this->insertReturnID('insert into user '.$keyString.' values '.$questionMarkString,$valueArr,$typeString);
    }

    public function dynamicUpdate(array $data,int $userID){

        $allowedKeys = ['firstName','lastName','adress','postalCode','houseNumber','houseNumberExtra','email','phone'];

        foreach($data as $dataKey => $value){
            if (!in_array($dataKey,$allowedKeys)){
                unset($data[$dataKey]);
            }
        }

        $sqlString = 'UPDATE USER SET ';
        $dataArray = [];
        $typeString = '';
        $i = 0;

        foreach ($data as $dataKey => $value){
            if ($i === 0){
                $sqlString .= $dataKey . ' = ?';
            } else {
                $sqlString .=  ','.$dataKey . ' = ?';
            }
            $dataArray[] = $value;
            if (gettype($value) === 'string'){
                $typeString .= 's';
            } elseif (gettype($value) === 'integer'){
                $typeString .= 'i';
            }
            $i++;
        }
        $sqlString .= ' WHERE idUser = ?';
        $typeString .= 'i';
        $dataArray[] = $userID;
        return $this->update($sqlString,$dataArray,$typeString);
    }

    public function getUserProfileData(int $idUser){
        return $this->select('SELECT firstName,lastName,email,phone,postalCode,houseNumber,houseNumberExtra,adress from user where idUser = ?', [$idUser],'i');
    }
}
