<?php

namespace webshop;
require_once 'classes/Psr4AutoloaderClass.php';

$loader = new PSR\Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('webshop', __DIR__ . '/classes');
$loader->addNamespace('webshop', __DIR__ . '/classes/models');

if (isset($_GET['idproduct'])) {
    $idproduct = $_GET['idproduct'];
    if (is_numeric($idproduct)) {
        $bl = new Cart();
        $bl->toevoegen(new Product($idproduct));
        setcookie('cart', serialize($bl));
    }
    header("Location: Cart.php");
}
header("Location: index.php");
