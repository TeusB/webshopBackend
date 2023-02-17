<?php

namespace webshop;

abstract class AbstractView
{

    public function showView(string $viewName, array $vars)
    {
        foreach ($vars as $key => $val) {
            $$key = $val;
        }
        include("./views/$viewName.php");
    }

    public function showView2Array(string $viewName, array $vars, array $values)
    {
        foreach ($vars as $key => $val) {
            $$key = $val;
        }
        foreach ($values as $valueKey => $value) {
            $$valueKey = $value;
        }
        include("./views/$viewName.php");
    }
}
