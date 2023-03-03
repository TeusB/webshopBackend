<?php

namespace controllers;

use main\Error;
use models\CategoryModel;

class Category extends CategoryModel
{
    private object $error;

    public function __construct()
    {
        parent::__construct();
        $this->error = new Error("Category Controller");
    }

    public function getAllCategories(array $fetch): array|false
    {
        $this->getAll($fetch);
        if ($this->checkFetch()) {
            return $this->fetch();
        }
        return false;
    }
}
