<?php

namespace main;

use main\DataBase;

class Model extends DataBase
{
    private object $stmt;
    private object $error;
    private $result;
    private object $mysqli;

    //gets database connection
    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("Model");
        $this->mysqli = $this->createConnection();
    }


    private function prepareStmt($query, string $paraString, array $bindColumns): bool
    {
        $this->stmt = $this->mysqli->prepare($query);
        $this->stmt->bind_param($paraString, ...$bindColumns);
        if ($this->stmt->execute()) {
            return true;
        }
        $this->error->maakError("kon query niet uitvoeren");
    }

    // //get by id
    // public function getByID(array $fetchColumns): bool
    // {
    // }

    //get data from database
    public function get(array $fetchColumns, array $identifiers): bool
    {
        $stringColumns = implode(", ", $fetchColumns);
        $identifierColumns = array_keys($identifiers);
        $valuesColumnsIdentifiers = array_values($identifiers);
        $stringIdentifiers = implode(" = ? AND ", $identifierColumns);
        $stringIdentifiers .= " = ?";
        $query = "SELECT $stringColumns FROM $this->tableName WHERE $stringIdentifiers";
        $paraString = $this->createParaString($valuesColumnsIdentifiers);
        return $this->prepareStmt($query, $paraString, $valuesColumnsIdentifiers);
    }



    public function getOr(array $fetchColumns, array $identifiers): bool
    {
        $stringColumns = implode(", ", $fetchColumns);
        $identifierColumns = array_keys($identifiers);
        $valuesColumnsIdentifiers = array_values($identifiers);
        $stringIdentifiers = implode(" = ? OR ", $identifierColumns);
        $stringIdentifiers .= " = ?";
        $query = "SELECT $stringColumns FROM $this->tableName WHERE $stringIdentifiers";
        $paraString = $this->createParaString($valuesColumnsIdentifiers);
        return $this->prepareStmt($query, $paraString, $valuesColumnsIdentifiers);
    }

    protected function getHandler(string $query, array $values = null)
    {
        if ($values) {
            $paraString = $this->createParaString($values);
            return $this->prepareStmt($query, $paraString, $values);
        } else {
            $this->stmt = $this->mysqli->prepare($query);
            if ($this->stmt->execute()) {
                return true;
            }
            $this->error->maakError("kon query niet uitvoeren");
        }
    }

    //get all data from a table
    public function getAll(array $fetchColumns): bool
    {
        $stringColumns = implode(", ", $fetchColumns);
        $query = "SELECT $stringColumns FROM $this->tableName";
        $this->stmt = $this->mysqli->prepare($query);
        if ($this->stmt->execute()) {
            return true;
        }
        $this->error->maakError("kon query niet uitvoeren");
    }

    //update columns
    public function update(array $updateColumns, array $identifiers)
    {
        $columns = array_keys($updateColumns);
        $valuesColumns = array_values($updateColumns);
        $stringColumns = implode(" = ?, ", $columns);
        $stringColumns .= " = ?";

        $identifierColumns = array_keys($identifiers);
        $valuesColumnsIdentifiers = array_values($identifiers);
        $stringIdentifiers = implode(" = ? AND ", $identifierColumns);
        $stringIdentifiers .= " = ?";

        $query = "UPDATE $this->tableName SET $stringColumns WHERE $stringIdentifiers";
        $arrayMerge = array_merge($valuesColumns, $valuesColumnsIdentifiers);
        $paraString = $this->createParaString($arrayMerge);
        return $this->prepareStmt($query, $paraString, $arrayMerge);
    }




    //delete
    public function delete(array $identifiers): bool
    {
        $identifierColumns = array_keys($identifiers);
        $valuesColumnsIdentifiers = array_values($identifiers);
        $stringIdentifiers = implode(" = ? AND ", $identifierColumns);
        $stringIdentifiers .= " = ?";
        $query = "DELETE FROM $this->tableName WHERE $stringIdentifiers";
        $paraString = $this->createParaString($valuesColumnsIdentifiers);
        return $this->prepareStmt($query, $paraString, $valuesColumnsIdentifiers);
    }

    //inserts into database
    public function insert(array $insertColumns): bool
    {
        $keysArray = array_keys($insertColumns);
        $valuesArray = array_values($insertColumns);
        $stringColumns = implode(", ", $keysArray);

        $questionMarks = "";
        foreach ($valuesArray as $value) {
            $questionMarks .= ", ?";
        }
        $questionMarks = substr($questionMarks, 2);

        $query = "INSERT INTO $this->tableName ($stringColumns) VALUES ($questionMarks)";
        $paraString = $this->createParaString($valuesArray);
        return $this->prepareStmt($query, $paraString, $valuesArray);
    }

    //checks if there is any data to be fetched
    public function checkFetch(): bool
    {
        $this->result = $this->stmt->get_result();
        if ($this->result->num_rows === 0) {
            return false;
        }
        return true;
    }

    //returns array of fetched data
    public function fetch(): array
    {
        return $this->result->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchRow(): array
    {
        return $this->result->fetch_all(MYSQLI_ASSOC)[0];
    }

    //returns last id
    public function returnLastID(): string|int
    {
        return $this->mysqli->insert_id;
    }

    public function checkExist(array $identifiers): bool
    {
        $identifierColumns = array_keys($identifiers);
        $valuesColumnsIdentifiers = array_values($identifiers);
        $stringIdentifiers = implode(" = ? AND ", $identifierColumns);
        $stringIdentifiers .= " = ?";
        $query = "SELECT $this->identifierColumn FROM $this->tableName WHERE $stringIdentifiers";
        $paraString = $this->createParaString($valuesColumnsIdentifiers);
        if ($this->prepareStmt($query, $paraString, $valuesColumnsIdentifiers)) {
            return $this->checkFetch();
        }
    }

    //cheks the type of a variable and puts the binding letter in paraType
    private function createParaString(array $valuesArray): string
    {
        $paraString = "";
        foreach ($valuesArray as $value) {
            $value = getType($value);
            switch ($value) {
                case "string":
                    $paraString .= "s";
                    break;
                case "integer":
                    $paraString .= "i";
                    break;
                case "double":
                    $paraString .= "d";
                    break;
                case "blob":
                    $paraString .= "b";
                    break;
                default:
                    $this->error->log->error("unknown paratype " . $value);
                    $this->error->maakError("ongeldige invoer gegevens");
            }
        }
        return $paraString;
    }
}
