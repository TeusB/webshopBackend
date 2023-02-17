<?php

namespace webshop;

use Exception;

class Model
{
    protected $DB;
    private $error;

    public function __construct()
    {
        $DB = DB::getInstance('mysql', 'root', 'root', 'webshop');
        $this->DB = $DB->getConnection();
        mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
        $this->error = new Error("Model Class");
    }

    private function prepare(string $sql)
    {
        try {
            $stmt = $this->DB->prepare($sql);
        } catch (Exception $e) {
            $this->error->logError($e->getMessage());
            $this->error->maakError("kon query niet uitvoeren");
        }
        return $stmt;
    }

    private function stmtHandler(string $sql, $values, string $params)
    {
        if (is_string($values)) {
            $values = ["$values"];
        }
        $stmt = $this->prepare($sql);
        try {
            $stmt->bind_param($params, ...$values);
            if ($stmt->execute()) {
                return $stmt->get_result();
            }
            return false;
        } catch (Exception $e) {
            $this->error->logError($e->getMessage());
            $this->error->maakError("query niet uitgevoerd");
        }
    }

    private function stmtHandlerCheck(string $sql, $values, string $params): bool
    {
        if (is_string($values)) {
            $values = ["$values"];
        }

        $stmt = $this->prepare($sql);
        $stmt->bind_param($params, ...$values);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function stmtHandlerEmpty(string $sql)
    {
        $stmt = $this->prepare($sql);
        try {
            if ($stmt->execute()) {
                return $stmt->get_result();
            }
        } catch (Exception $e) {
            $this->error->logError($e->getMessage);
            $this->error->maakError("query niet uitgevoerd");
        }
    }

    protected function selectEmpty(string $sql)
    {
        if ($result = $this->stmtHandlerEmpty($sql)) {
            if ($result->num_rows === 0) {
                return false;
            } else {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
        return false;
    }


    protected function select(string $sql, $values, string $params)
    {
        if ($result = $this->stmtHandler($sql, $values, $params)) {
            if ($result->num_rows !== 0) {
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        }
        return false;
    }


    protected function selectBool(string $sql, $values, string $params): bool
    {
        if ($result = $this->stmtHandler($sql, $values, $params)) {
            if ($result->num_rows !== 0) {
                return true;
            }
        }
        return false;
    }

    protected function insert(string $sql, $values, string $params): bool
    {
        if ($this->stmtHandlerCheck($sql, $values, $params)) {
            return true;
        }
        return false;
    }

    protected function insertReturnID(string $sql, $values, string $params)
    {
        $this->stmtHandler($sql, $values, $params);
        return $this->DB->insert_id;
    }

    protected function update(string $sql, $values, string $params)
    {
        if ($this->stmtHandlerCheck($sql, $values, $params)) {
            return true;
        }
        return false;
    }

    protected function delete(string $sql, $values, string $params)
    {
        if ($this->stmtHandlerCheck($sql, $values, $params)) {
            return true;
        }
        return false;
    }

    protected function returnSingleValue(array $values)
    {
        foreach ($values[0] as $value) {
            return $value;
        }
    }

    protected function makeInt(string $value): int
    {
        return intval($value);
    }
}
