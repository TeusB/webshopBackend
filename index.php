<?php

namespace webshop;

require_once 'Psr4AutoloaderClass.php';
$loader = new \PSR\Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('webshop', __DIR__ . '/classes');
$loader->addNamespace('webshop', __DIR__ . '/models');
$loader->addNamespace('webshop', __DIR__ . '/controllers');


function correctClassName($s)
{
    return ucfirst(strtolower($s));
}

function correctMethodName($s)
{
    return strtolower($s);
}

function queryToArray($qry)
{
    $result = array();
    //string must contain at least one = and cannot be in first position
    if (strpos($qry, '=')) {
        if (strpos($qry, '?') !== false) {
            $q = parse_url($qry);
            $qry = $q['query'];
        }
    } else {
        return false;
    }

    foreach (explode('&', $qry) as $couple) {
        list($key, $val) = explode('=', $couple);
        $result[$key] = $val;
    }

    return empty($result) ? false : $result;
}

// Start controller (and method when given)
$qs = queryToArray($_SERVER['QUERY_STRING']);
if (!$qs) {
    new Shop();
} else {

    $controller = (isset($qs['c'])) ? $qs['c'] : false;
    $controller = ($controller !== false) ? __NAMESPACE__ . "\\" . correctClassName(
        $controller
    ) : false;

    //user


    $method = (isset($qs['m'])) ? $qs['m'] : false;
    $method = ($method !== false) ? correctMethodName($method) : false;

    //login
    $params = array_slice($qs, 2);
    $params = (count($params) > 0) ? $params : false;

    if ($controller && $method && $params) {
        // bv. localhost/index.php?c=shop&m=add&id=3&name=test
        $o = new $controller();
        $o->$method($params);
    } elseif ($controller && $method) {
        // bv. localhost/index.php?c=shop&m=show
        $o = new $controller();
        $o->$method();
    } elseif ($controller) {
        // bv. localhost/index.php?c=shop
        new $controller();
    }
}
