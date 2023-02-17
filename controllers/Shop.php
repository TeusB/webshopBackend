<?php

namespace webshop;

class Shop extends AbstractView
{
    private $products = [];

    public function __construct()
    {
        $p_model = new Product_Model();
        $this->products = $p_model->listProducts();
        // Verklaring regel hieronder: Open de view (shop.php) en vul de variabele $products die daar in voorkomt met $this->products
        // Stuur die het resultaat vervolgens naar de client zodat de gebruiker de shop met producten te zien krijgt.
        $this->showView('shop', ['products' =>$this->products]);
    }

}